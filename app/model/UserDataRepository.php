<?php

namespace Project;

use Nette;



class UserDataRepository extends Repository
{
    public function findAllUserData()
    {
        return $this->findAll()->where('user.id = id');
    }
}