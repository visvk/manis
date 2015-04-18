<?php

namespace Project;

use Nette;


class SubjectRepository extends Repository
{
	public function findAllSubject()
	{
		return $this->findAll();
	}

	public function findByID($subject_id)
	{
		return $this->findAll()->where('id', $subject_id)->fetch();
	}

	public function subjectRegistration($subject, $year)
	{

		return $this->getTable()->insert(array(
			'subject' => $subject,
			'school_year_id' => $year
		));
	}

	public function subjectAndYear($id)
	{
		return $this->getTable()->select('subject, school_year.year')->where('id = ?', $id);
	}

	public function getSubjects()
	{
		return $this->getTable()->select('subject.id,subject, school_year.year');
	}
}