<?php

namespace Project;

use Nette;
use Nette\DateTime;


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
		return $this->getTable()
			->select('file.id AS id,filename,task_id,task.project_id, task.text,task.id AS task_id, task.user_subj.user.login, file.created AS updated, file.grade AS gradf ')
			->where('task.project_id', $projectId);
	}

	public function getFilesByTaskId($taskId)
	{
		return $this->getTable()->select('task_id')->where('task_id', $taskId);
	}

	public function createFile($description, $taskId)
	{
		return $this->getTable()->insert(array(
			// 'datumOd' => $form->values->datumOd,
			//  'datumDo' => $form->values->datumDo,

			'task_id' =>$taskId,
			'desc' => $description,
			'created' => new DateTime
		));
	}

}