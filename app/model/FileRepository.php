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

	public function getFilesByProjectId($projectId)
	{
		$this->getTable()
			->select('file.id AS id,filename,task_id,task.project_id, task.text,task.id AS task_id, task.user_subj.user.login, file.created AS updated, file.grade AS gradf ')
			->where('task.project_id', $projectId);
	}
}