<?php

namespace Project;

use Nette;


class School_yearRepository extends Repository
{
	public function yearRegistration($year, $start, $end)
	{


		return $this->getTable()->insert(array(
			'year' => $year,
			'term_start' => $start,
			'term_end' => $end
		));
	}

	public function getYears()
	{
		return $this->getTable()->select('id, year')->fetchPairs('id', 'year');
	}

	public function getYearsInfo()
	{
		return $this->getTable()->select('year, term_start,term_end');
	}
}