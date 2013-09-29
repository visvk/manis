<?php

namespace Project;

use Nette;



class UserRepository extends Repository
{   
    public function findByExtra($login)
{
    //return $this->findAll()->where('user_id', $this-;
}
    public function findByName($login)
{
    return $this->findAll()->where('login', $login)->fetch();
}
    public function findByID($user_id)
{
    return $this->findAll()->where('$id', $user_id)->fetch();
}

public function setPassword($user_id, $password)
{
    $this->getTable()->where(array('id' => $user_id))->update(array(
        'password' => sha1($password)
    ));
}
/// Pre Offline system je potrebne pridat parameter $heslo.
public function userRegistration($prezyvka, $krstne, $priezvisko, $rola, $email) 
{
    
   
        $this->getTable()->insert(array(
        'login' => $prezyvka,
            /** Pre pripadne registrovanie pre Offline system.*/
        //'password' => sha1($heslo),
            'name' => $krstne,
            'surname' => $priezvisko,
            'email' => $email,
        'role_id' => $rola,
        
        
       
    ));
   
}
public function userCsv($krstne, $priezvisko, $rola) 
{
    
   
        $this->getTable()->insert(array(
      
            'name' => $krstne,
            'surname' => $priezvisko,
        'role_id' => $rola,
        
        
       
    ));
   
}
   
}
