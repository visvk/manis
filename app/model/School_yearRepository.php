<?php

namespace Project;

use Nette;



class School_yearRepository extends Repository
{
    public function yearRegistration($year,$start,$end)
{
    
   
       return $this->getTable()->insert(array(
        'year' => $year,
           'term_start' => $start,
           'term_end' => $end
           ));
}
}