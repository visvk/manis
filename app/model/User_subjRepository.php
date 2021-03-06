<?php

namespace Project;

use Nette;


class User_subjRepository extends Repository
{
	public function findAllUsersInSubj()
	{
		return $this->findAll();
	}

	public function connectToSubj($prezyvka_id, $predmet_id)
	{
		// $this->getTable()->select($columns)
		$this->getTable()->insert(array(
				'user_id' => $prezyvka_id,
				'subject_id' => $predmet_id,
			)
		);

	}

	public function getUsersBySubjectId($subjectId)
	{
		return $this->getTable()
			->select('user_subj.id,user.login')
			->where('subject_id', $subjectId)->fetchPairs('id', 'login');
	}

	public function getUsersInformationBySubjectId($subjectId)
	{
		return $this->getTable()->select('user.login, user.name, user.id AS usid, user.surname, user.email')
			->where('subject_id', $subjectId);
	}

	public function getSubjectsByUserId($userId)
	{
		return $this->getTable()->select('subject.*')
			->where('user_id', $userId);
	}

}