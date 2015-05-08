<?php

namespace Project;

use Nette;


class Proj_usRepository extends Repository
{
	public function findAllUsersInProj()
	{
		return $this->findAll();
	}

	public function proj_usRegistration($riesitel, $project, $values)
	{


		$this->getTable()->insert(array(
			'user_subj_id' => $riesitel,
			'project_id' => $project,
			'manager' => $values->manager,
			'analytic' => $values->analytic,
			'designer' => $values->designer,
			'programmer' => $values->programmer,
			'tester' => $values->tester


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
			->select('user_subj.user_id, user_subj.user.login, manager, user_subj.user.surname, user_subj.user.name, user_subj.user.email')
			->where('project_id',$projectId);
	}

	public function isManager($projectId, $userId)
	{
		$user = $this->getTable()
			->select('*')
			->where('project_id',$projectId)
			->where('manager',1)
			->where('user_subj.user_id',$userId);

		return $user->count() > 0 ? TRUE : FALSE;
	}
}