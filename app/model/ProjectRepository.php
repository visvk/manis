<?php

namespace Project;

use Nette;


class ProjectRepository extends Repository
{

	public function tasksOf(Nette\Database\Table\ActiveRow $project)
	{
		return $project->related('Task:project_id')->order('created');
	}

	public function findByName($text)
	{

		return $this->findAll()->where('text', $text)->fetch();
	}

	public function findByID($project_id)
	{
		return $this->findAll()->where('id', $project_id)->fetch();
	}


	public function projectRegistration($text, $acronym, $subject, $solver, $created, $submitted)
	{


		return $this->getTable()->insert(array(
			'text' => $text,
			'acronym' => $acronym,
			'subject_id' => $subject,
			'solver' => $solver,
			'created' => $created,
			'submitted' => $submitted
		));
	}

	public function projectEdit($id, $text, $acronym, $created, $submitted)
	{
		$this->findBy(array('id' => $id))->update(array(
				'text' => $text,
				'acronym' => $acronym,
				'created' => $created,
				'submitted' => $submitted

			)
		);
	}

	public function mark($id, $mark)
	{
		$this->findBy(array('id' => $id))->update(array(
				'mark' => $mark

			)
		);
	}

	public function getProjects()
	{
		return $this->getTable()
			->select('project.id,text, submitted, created, solver, subject.subject, subject.school_year.year, acronym,grade,mark');
	}



}