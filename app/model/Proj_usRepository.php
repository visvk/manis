<?php

namespace Project;

use Nette;



class Proj_usRepository extends Repository
{
  public function findAllUsersInProj(){
      return $this->findAll();
  }
  
  public function proj_usRegistration($riesitel, $project, $manager)
{
    
   
        $this->getTable()->insert(array(
        'user_subj_id' => $riesitel,
        'project_id' => $project,
        'manager' => $manager,
        
        
       
    ));
   
}
}