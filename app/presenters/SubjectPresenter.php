<?php

use Nette\Application\UI\Form;
use Nette\Application\UI;
use Grido\grid,
	Grido\Components\Filters\Filter,
	Grido\Components\Columns\Column,
	Nette\Utils\Html;

class SubjectPresenter extends BasePresenter
{

	public function startup()
	{
		parent::startup();


		if (!$this->user->isLoggedIn()) {

			$this->redirect('Sign:in', array(
				'backlink' => $this->storeRequest()
			));
		} else {
			if (!$this->user->isAllowed('Default')) {
				$this->flashMessage('Access denied');
				$this->redirect('Sign:in');
			}
		}
	}

	private $subjectRepository;
	private $school_yearRepository;
	private $userSubjectRepository;
	private $userRepository;
	private $subject;
	private $subject_id;

	public function inject(Project\SubjectRepository $subjectRepository,
						   Project\User_subjRepository $user_subjRepository,
						   Project\UserRepository $userRepository,
						   Project\School_yearRepository $school_yearRepository)
	{

		$this->subjectRepository = $subjectRepository;
		$this->school_yearRepository = $school_yearRepository;
		$this->userSubjectRepository = $user_subjRepository;
		$this->userRepository = $userRepository;
	}

	public function actionDefault($id)
	{
		//$this->project = $this->projectRepository->findAll()->where('project_id', $id)->fetch();
		$this->subject = $this->subjectRepository->findBy(array('id' => $id))->fetch();
		$this->subject_id = $id;
		if ($this->subject === FALSE || $id === NULL) {
			$this->setView('notFound');
		}
	}

	public function renderDefault()
	{

		$this->template->subjdat = $this->subject; //$this->subjectRepository->subjectAndYear($this->subject_id);
	}

	protected function createComponentSubjRegForm()
	{
		//$roky = $this->school_yearRepository->findAll()->fetchPairs('id','year');
		$roky = $this->school_yearRepository->getYears();

		$form = new Form();
		$form->addText('subject', 'Názov predmetu:', 30, 20)
			->addRule(Form::FILLED, 'Je nutné zadať názov predmetu.');

		$form->addSelect('year', 'Školský rok: ', $roky)
			->setPrompt('Zvolte šk. rok')
			//->skipFirst()
			->addRule(Form::FILLED, 'Musite zadať rok.');

		$form->addSubmit('set', 'Vytvoriť predmet');
		$form->onSuccess[] = $this->SubjRegFormSubmitted;

		return $form;
	}

	public function SubjRegFormSubmitted(Form $form)
	{
		if (!$this->user->isAllowed('Reg')) {
			$this->flashMessage('Access denied');
			$this->redirect('Sign:in');
		}
		$values = $form->getValues();


		$this->subjectRepository->subjectRegistration($values->subject, $values->year);
		$this->flashMessage('Vytvorili ste predmet.', 'success');
		$this->redirect('this');
	}

	/*  public function createComponentProject()
	  {

	  return new Project\ProjectListControl($this->projectRepository->findAll(), $this->projectRepository);

	  } */

	protected function createComponentSubjectGrid($name)
	{
		$grid = new Grid($this, $name);
		$grid->setModel($this->subjectRepository->getSubjects())
			->setDefaultPerPage(10);

		$grid->addColumn('subject', 'Názov')
			->setSortable()
			->setCustomRender(function ($item) {
				$baseUr = Html::el('a')->href('?presenter=Usersubj&action=default&id=' . $item->id)->setText($item->subject);

				return $baseUr;
			});

		$grid->addColumn('year', 'Školský rok:')
			->setSortable();

		return $grid;
	}

	protected function createComponentUsersInSubjGrid($name)
	{
		$grid = new Grid($this, $name);
		$grid->setModel($this->userSubjectRepository->getUsersInformationBySubjectId($this->subject_id))
			->setDefaultPerPage(10);

		$grid->addColumn('login', 'Login');
		$grid->addColumn('name', 'Meno');
		$grid->addColumn('surname', 'Priezvisko');
		$grid->addColumn('email', 'E-mail');

		return $grid;
	}

	protected function createComponentConnectToSubjForm()
	{
		$users = $this->userRepository->getUsers();

		$form = new Form();

		$form->addSelect('login', 'Login:', $users)
			->setPrompt('Zvoľte login')
			->addRule(Form::FILLED, 'Musíte zadať login.');
		$form->addSubmit('set', 'Pripojiť k predmetu');
		$form->onSuccess[] = $this->ConnectToSubjFormSubmitted;

		return $form;
	}

	public function ConnectToSubjFormSubmitted(Form $form)
	{
		$values = $form->getValues();
		$this->user_subjRepository->connectToSubj($values->login, $this->subject_id);
		$this->flashMessage('Pridali ste ' . $values->login . ' k predmetu ' . $this->subject->subject . '.', 'success');
		$this->redirect('this');
	}

	protected function createComponentYearRegForm()
	{
		//$roky = $this->school_yearRepository->findAll()->fetchPairs('id','year');
		$roky = $this->school_yearRepository->getYears();
		$sem = array(
			'LS' => 'LS',
			'ZS' => 'ZS'
		);
		$form = new Form();
		$form->addText('year1', 'Školský rok', 5)
			->setType('number')
			->addRule(Form::RANGE, 'Počet musí byť od %d do %d', array(2012, 2100))
			->addRule(Form::FILLED, 'Je nutné zadať počet výstupov.');
		$form->addText('year2', '', 5)
			->setType('number')
			->addRule(Form::RANGE, 'Počet musí byť od %d do %d', array(2012, 2100))
			->addRule(Form::FILLED, 'Je nutné zadať počet výstupov.');
		$form->addSelect('term', 'Semester ', $sem)
			->setPrompt('Zvolte semester')
			//->skipFirst()
			->addRule(Form::FILLED, 'Musite zadať semester.');
		$form->addDatePicker('start', 'Začiatok semestra')
			->setAttribute('class', 'start')
			->addRule(Form::VALID, 'Vložený dátum je neplatný!');
		$form->addDatePicker('end', 'Koniec semestra')
			->setAttribute('class', 'end')
			->addRule(Form::VALID, 'Vložený dátum je neplatný!');

		$form->addSubmit('set', 'Vytvoriť');
		$form->onSuccess[] = $this->YearFormSubmitted;

		return $form;
	}

	public function YearFormSubmitted(Form $form)
	{
		if (!$this->user->isAllowed('Reg')) {
			$this->flashMessage('Access denied');
			$this->redirect('Sign:in');
		}
		$values = $form->getValues();


		if ($values->year2 - $values->year1 == 1 && $values->start < $values->end) {

			$yearterm = $values->year2 . '/' . $values->year1 . ' ' . $values->term;
			$this->school_yearRepository->yearRegistration($yearterm, $values->start, $values->end);
			$this->flashMessage('Vytvorili ste predmet.', 'success');
			$this->redirect('this');
		} else {
			$this->flashMessage('Zlý formát .', 'error');
			$this->redirect('this');
		}
	}

	protected function createComponentYearsGrid($name)
	{
		$grid = new Grid($this, $name);
		$grid->setModel($this->school_yearRepository->getYearsInfo())
			->setDefaultPerPage(10);

		$grid->addColumn('year', 'Školský rok')
			->setSortable();
		$grid->addColumn('term_start', 'Začiatok semestra', Column::TYPE_DATE)
			->setSortable()
			->setDateFormat(Grido\Components\Columns\Date::FORMAT_TEXT);
		$grid->addColumn('term_end', 'Koniec semestra', Column::TYPE_DATE)
			->setSortable()
			->setDateFormat(Grido\Components\Columns\Date::FORMAT_TEXT);

		return $grid;
	}

}
