<?php

namespace Project;

use Nette;


class Proj_usRepository extends Repository
{
	public function findAllUsersInProj()
	{
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

	public function getProjectsByUserId($userId)
	{
		return $this->getTable()
			->select('project.id AS id,project.text,project.created,project.submitted,project.acronym,project.grade')
			->where('user_subj.user_id', $userId);
	}

	public function getAssignedUsersByProjectId($projectId)
	{
		return $this->getTable()
			->select('user_subj_id,user_subj.user.login')
			->where('project_id', $this->idproj)
			->fetchPairs('user_subj_id', 'login');
	}

	public function getUsersAndManagerByProjectId($projectId)
	{
		return $this->getTable()
			->select('user_subj.user.login, manager')
			->where('project_id',$projectId);
	}
}