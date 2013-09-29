<?php

namespace Project;

use Nette;



class RoleRepository extends Repository
{   public function findAllRoles()
{      
    return $this->findAll();
}
    public function findRoles()
{      
    return $this->getTable()->select('role')->fetch()->role;
}

    public function findByRole($role)
{
    return $this->findAll()->where('role' ,$role)->fecth();
}
    
}