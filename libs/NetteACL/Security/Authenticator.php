<?php
// pro PHP 5.3
// use Nette\Object, Nette\Security\IAuthenticator, Nette\Security\AuthenticationException, Nette\Security\Identity;
namespace NetteACL\Security;

use Nette;
use Nette\Security\Identity;
use Nette\Object,
	Nette\Environment;
use Nette\Security\AuthenticationException;
use Nette\Database\Connection;


class Authenticator extends Nette\Object implements Nette\Security\IAuthenticator
{

	/** @var Nette\Database\Context */
	private $database;
	private $inRole;

	// Set to FALSE for Local Authenticator
	private $ldap = TRUE;

	/**
	 * @param Nette\Database\Context
	 */
	public function __construct(Connection $database)
	{

		$this->database = new Nette\Database\Context($database);
	}


	/**
	 * Performas an authentication
	 * @param array
	 * @return Nette\Security\Identity
	 */
	public function authenticate(array $credentials)
	{

		list($login, $password) = $credentials;
		$row = $this->database->table('user')->where('login', $login)->fetch();
		$inRole = $this->database->table('role')->where('id', $row->role_id)->fetch();

		if ($this->ldap) {
			//** Samotna authent. cez LDAP
			$ldap_conn = ldap_connect('ldaps://ldap.fei.tuke.sk');
			if ($ldap_conn) {
				$ldapbind = @ldap_bind($ldap_conn, 'uid=' . $login . ',ou=People,dc=fei,dc=tuke,dc=sk', $password);
				ldap_unbind($ldap_conn);
				if ($ldapbind) {
					//return new Identity($login, 'Lecturer', $ldapbind);

					return new Identity($row->id, $inRole->role, $row->toArray(), $ldapbind);

				} else {
					throw new AuthenticationException("Zlé meno alebo heslo (LDAP).", self::INVALID_CREDENTIAL);
				}
			} else {
				throw new AuthenticationException("Overovací server nieje dostupný.", self::IDENTITY_NOT_FOUND);
			}
		} else {

			if (!$row) {
				throw new AuthenticationException('Zlý login.', self::IDENTITY_NOT_FOUND);
			}

			return new Identity($row->id, $inRole->role, $row->toArray());

		}
//		list($login, $password) = $credentials;
//
//		if ($this->ldap) {
//			$ldap_conn = ldap_connect('ldaps://ldap.fei.tuke.sk','636');
//			$baseDn = 'uid='.$login.',ou=People,dc=fei,dc=tuke,dc=sk';
//			if ($ldap_conn) {
//				$ldapbind = @ldap_bind ($ldap_conn,$baseDn, $password);
//				if ($ldapbind) {
//					$row = $this->database->table('user')->where('login', $login)->fetch();
//					$filter="(objectclass=*)";
//					$justthese = array("uid","uidnumber","employeetype", "givenname", "sn", "mail");
//					$sr=ldap_read($ldap_conn, $baseDn, $filter,$justthese);
//					$entry = ldap_get_entries($ldap_conn, $sr);
//					$userId = $entry[0]["uidnumber"][0];
//					$userRole = $entry[0]["employeetype"][0];
//					$role_id = $this->database->table('role')->where('role', $userRole)->fetch();
//					$userData = array(
//						'id' => $entry[0]["uidnumber"][0],
//						'role_id' => $role_id, //student
//						'login' => $entry[0]["uid"][0],
//						'name' => $entry[0]["givenname"][0],
//						'surname' => $entry[0]["sn"][0],
//						'email' => $entry[0]["mail"][0],
//						'created' => new \Nette\DateTime()
//					);
//					if (!$row) {
//						$this->database->table('user')->insert($userData);
//					}
//					ldap_unbind ($ldap_conn);
//					return new Identity($userId, $userRole, $userData);
//
//				} else {
//					ldap_unbind ($ldap_conn);
//					throw new AuthenticationException("Zlé meno alebo heslo (LDAP).", self::INVALID_CREDENTIAL);
//				}
//			} else {
//				throw new AuthenticationException("Overovací server nieje dostupný.", self::IDENTITY_NOT_FOUND);
//			}
//		} else {
//			$row = $this->database->table('user')->where('login', $login)->fetch();
//
//			if (!$row) {
//				throw new AuthenticationException('Zlý login.', self::IDENTITY_NOT_FOUND);
//			}
//			/*
//					if ($row->password !== sha1($password)) {
//							throw new AuthenticationException('Zlé heslo.', self::INVALID_CREDENTIAL);
//					}
//			*/
//			$role = $this->database->table('role')->where('id', $row->role_id)->fetch();
//
//			return new Identity($row->id, $role->role, $row->toArray());
//		}
	}
}