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
			->select('project.id AS id,project.text,project.created,project.submitted,project.acronym,project.grade, project.subject.subject AS subject_name, project.subject_id')
			->where('user_subj.user_id', $userId);
	}

	public function getAssignedUsersByProjectId($projectId)
	{
		return $this->getTable()
			->select('user_subj_id,user_subj.user.login')
			->where('project_id', $projectId)
			->fetchPairs('user_subj_id', 'login');
	}

	public function getUsersAndManagerByProjectId($projectId)
	{
		return $this->getTable()
			->select('user_subj.user_id, user_subj.user.login, manager, user_subj.user.surname, user_subj.user.name')
			->where('project_id',$projectId);
	}
}