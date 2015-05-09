<?php
use Nette\Application\UI\Form;
use Nette\Application\UI;
use Grido\grid,
	Grido\Components\Filters\Filter,
	Grido\Components\Columns\Column,
	Nette\Utils\Html;


class UserViewPresenter extends BasePresenter
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

	private $userRepository;
	private $user_subjRepository;
	private $subjectRepository;
	private $taskRepository;
	private $users;
	private $user_id;

	public function inject(Project\UserRepository $userRepository,
						   Project\User_subjRepository $user_subjRepository,
						   Project\TaskRepository $taskRepository,
						   Project\SubjectRepository $subjectRepository)
	{

		$this->userRepository = $userRepository;
		$this->user_subjRepository = $user_subjRepository;
		$this->subjectRepository = $subjectRepository;
		$this->taskRepository = $taskRepository;
	}

	public function actionView($id)
	{
		$this->users = $this->userRepository->findBy(array('id' => $id))->fetch();
		$this->user_id = $id;
		if ($this->users === FALSE || $id === NULL) {
			$this->setView('notFound');
		}

	}

	public function renderView()
	{
		$this->template->users = $this->users;
	}

	public $filterRenderType = Filter::RENDER_INNER;

	protected function createComponentUsersGrid($name)
	{
		$grid = new Grid($this, $name);
		$grid->setModel($this->userRepository->getUserInformation());

		$grid->addColumn('login', 'Login')
			->setSortable()
			->setCustomRender(function ($item) {
				$baseUr = Html::el('a')->href('?presenter=UserView&action=view&id=' . $item->id)->setText($item->login);

				return $baseUr;
			});
		$grid->addColumn('name', 'Meno');

		$grid->addColumn('surname', 'Priezvisko')
			->setSortable();
		$grid->addColumn('role', 'Rola')
			->setSortable();
		$grid->addColumn('email', 'E-mail');
		// $grid->setFilterRenderType($this->filterRenderType);

		$operations = $this->subjectRepository->findAll()->fetchPairs('id', 'subject');
		$grid->setOperations($operations, callback($this, 'gridOperationsHandler'));
		return $grid;

	}

	public function gridOperationsHandler($operation, $id)
	{
		if (!$this->user->isAllowed('Reg')) {
			$this->flashMessage('Access denied');
			$this->redirect('Sign:in');
		}
		if ($id) {
			$b = count($id);
			foreach ($id as $id) {
				if ($this->user_subjRepository->findAll()->where('user_id = ? AND subject_id = ?', $id, $operation)->count('*') == 0) {
					$this->user_subjRepository->connectToSubj($id, $operation);
				}
			}

			$a = ($b == 1) ? "$b študenta" : "$b študentov";
			$this->flashMessage("Priradili ste $a.", 'info');
		} else {
			$this->flashMessage('No rows selected.', 'error');
		}
	}

	protected function createComponentUserViewGrid($name)
	{
		$grid = new Grid($this, $name);
		$grid->translator->setLang('sk');
		$grid->setModel($this->taskRepository->getTasksByUserId($this->user_id))
			->setDefaultPerPage(5);


		$grid->addColumn('text', 'Úloha')
			->setSortable()
			->setReplacement(array('published' => 'ok'))
			->setCustomRender(function ($item) {
				$baseUr = Html::el('a')->href('?presenter=TaskDetail&action=default&id=' . $item->id)->setText($item->text);

				return $baseUr;
			});

		$grid->addColumn('login', 'Riešitel')
			->setSortable();
		$grid->addColumn('created', 'Riešenie od:', Column::TYPE_DATE)
			->setSortable()
			->setDateFormat(Grido\Components\Columns\Date::FORMAT_TEXT);
		$grid->addColumn('submitted', 'Riešenie do:', Column::TYPE_DATE)
			->setSortable()
			->setDateFormat(Grido\Components\Columns\Date::FORMAT_TEXT);

		$grid->addColumn('numfiles', 'Počet výstupov')
			->setSortable();
		$grid->addColumn('grade', 'Stav')
			->setCustomRender(function ($item) {
				$perc = $item->grade . '%';

				return $perc;
			})
			->setSortable();

		$grid->addAction('markDone', 'OK')
			->setCustomRender(function ($item) {
				$baseUr = Html::el('a')->href('?taskId=' . $item->id . '&id=' . $item->project_id . '&do=markDone&presenter=Task')->setText('OK');
				$spolu = $item->grade != 100 ? ' <img src="images/Check.png">' . $baseUr : '';
				return $spolu;
			});

		return $grid;
	}

	protected function createComponentAkceForm()
	{
		$form = new Form();

		$form->addUpload('obrazek', 'CSV:')
			->addRule(Form::MAX_FILE_SIZE, 'Soubor je príliš veľký! Povolená veľkosť je 2M.', 2 * 1024 * 1024);

		$form->addSubmit('save', 'Uložiť')->setAttribute('class', 'default');


		$form->onSuccess[] = callback($this, 'akceFormSubmitted');
		$form->addProtection('Vypršal časový limit, odošlite formulár znovu.');

		return $form;
	}

	public function akceFormSubmitted(Form $form)
	{

		$file = $form['obrazek']->getValue();
		$shortFilename = $file->name;
		$imgUrl = $this->context->getParameters()['wwwDir'] . '/images/upload/csv/' . $shortFilename;

		if ($file->move($imgUrl)) {
			$subor = fopen($imgUrl, "r");
			$a = 0;

			while (!feof($subor)) {
				setlocale(LC_ALL, 'sk_SK.utf-8', 'sk_SK@euro', 'sk_SK', 'sk', 'Slovak');
				$zaznam = fgetcsv($subor);
				if ($zaznam[0] != NULL && $zaznam[1] != NULL) {
					if ($a == 0) {
						$a = 1;
					} else {
						setlocale(LC_ALL, 'sk_SK.utf-8', 'sk_SK@euro', 'sk_SK', 'sk', 'Slovak');
						$this->userRepository->userCsv($zaznam[0], $zaznam[1], '6');

					}
				}
			}


			fclose($subor);

			$this->flashMessage('Súbor pridaný.', 'success');


		}
	}


}
