<?php

namespace Project;

use Nette;



class FileRepository extends Repository
{ 
   public function fileDone($id)
	{
		$this->findBy(array('id' => $id))->update(array('grade' => 1));
                
	}
public function fileBad($id)
	{
		$this->findBy(array('id' => $id))->update(array('grade' => 2));
                
	}
  public function fileDelete($id)
	{
		$this->findBy(array('id' => $id))->delete("articles");
                
	}
}