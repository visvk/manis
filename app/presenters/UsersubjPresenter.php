<?php
use Nette\Application\UI\Form;
use Nette\Application\UI;
use Grido\grid,
	Grido\Components\Filters\Filter,
	Grido\Components\Columns\Column,
	Nette\Utils\Html;


class UsersubjPresenter extends BasePresenter
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

	private $user_subjRepository;
	private $subjectRepository;
	private $userRepository;
	private $subject;
	private $subject_id;


	public function inject(Project\User_subjRepository $user_subjRepository,
						   Project\UserRepository $userRepository,
						   Project\SubjectRepository $subjectRepository)
	{

		$this->subjectRepository = $subjectRepository;
		$this->user_subjRepository = $user_subjRepository;
		$this->userRepository = $userRepository;

	}

	public function actionDefault($id)
	{
		$this->subject = $this->subjectRepository->findBy(array('id' => $id))->fetch();
		$this->subject_id = $id;
		if ($this->subject === FALSE || $id === NULL) {
			$this->setView('notFound');
		}
	}


	public function renderDefault()
	{

		$this->template->subjdat = $this->subject;
	}

	public $filterRenderType = Filter::RENDER_INNER;

	protected function createComponentUsersInSubjGrid($name)
	{
		$grid = new Grid($this, $name);
		$grid->setModel($this->user_subjRepository->getUsersInformationBySubjectId($this->subject_id))
			->setDefaultPerPage(10);

		$grid->addColumn('login', 'Login')
			->setCustomRender(function ($item) {
				$baseUr = Html::el('a')->href('?presenter=UserView&action=view&id=' . $item->usid)->setText($item->login);

				return $baseUr;
			})
			->setSortable()
			->setFilter();
		$grid->addColumn('name', 'Meno')
			->setSortable()
			->setFilter();
		$grid->addColumn('surname', 'Priezvisko')
			->setSortable()
			->setFilter();
		$grid->addColumn('email', 'E-mail')
			->setFilter();
		$grid->setFilterRenderType($this->filterRenderType);


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
		$this->flashMessage('Pridali ste používateľa k predmetu ' . $this->subject->subject . '.', 'success');
		$this->redirect('this');
	}


}
