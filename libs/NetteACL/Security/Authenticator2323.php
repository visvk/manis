<?php

namespace NetteACL\Security;

use Nette;
use Nette\Security\Identity;
use Nette\Security\AuthenticationException;

class Authenticator222 extends Nette\Object implements Nette\Security\IAuthenticator
{
	/** @var Nette\Database\Connection */
    private $database;
    //private $inRole;

	/**
	 * @param Nette\Database\Connection
	 */
    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
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
        
        
        if (!$row) {
            throw new AuthenticationException('The login is incorrect.', self::IDENTITY_NOT_FOUND);
        }

        if ($row->password !== sha1($password)) {
            throw new AuthenticationException('Wrong password', self::INVALID_CREDENTIAL);
        }
        $inRole = $this->database->table('role')->where('id',$row->role_id)->fetch();     

		unset($row->password);
        return new Identity($row->id, $inRole->role, $row->toArray());
    }

}