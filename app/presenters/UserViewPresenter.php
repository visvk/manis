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
	{//$this->project = $this->projectRepository->findAll()->where('project_id', $id)->fetch();
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

	/*public function createComponentUser()
	{

		return new Project\UserListControl($this->userRepository->findAll(), $this->userRepository);

	}*/
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
		//  ->setSuggestion();
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
		//->setFilter()
		// ->setSuggestion();

		$grid->addColumn('created', 'Riešenie od:', Column::TYPE_DATE)
			->setSortable()
			->setDateFormat(Grido\Components\Columns\Date::FORMAT_TEXT);
		//->setFilter(Filter::TYPE_DATE);
		$grid->addColumn('submitted', 'Riešenie do:', Column::TYPE_DATE)
			->setSortable()
			->setDateFormat(Grido\Components\Columns\Date::FORMAT_TEXT);
		//  ->setFilter(Filter::TYPE_DATE);

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
				// http://localhost/New%20Folder/www/?fileId=21&id=41&do=fileBad&presenter=TaskDetail
				$spolu = $item->grade != 100 ? ' <img src="images/Check.png">' . $baseUr : '';
				return $spolu;
			});

		return $grid;
	}

	protected function createComponentAkceForm()
	{
		$form = new Form();
		//  $presenter = $this;

		/*  $form->addCheckbox('agree', '  Přidat nebo změnit obrázek')
					->addCondition($form::EQUAL, TRUE)
					->toggle('newPic');*/
		/*  $form->addGroup()
					->setOption('container', \Nette\Utils\Html::el('td')->id('newPic'));*/
		//$form->addHidden('tid');
		$form->addUpload('obrazek', 'CSV:')
			/* ->addRule(Form::MIME_TYPE, 'Súbor musí byť typu .csv.',
					'text/csv')*/
			->addRule(Form::MAX_FILE_SIZE, 'Soubor je príliš veľký! Povolená veľkosť je 2M.', 2 * 1024 * 1024);
		/*  $form->addTextArea('popis', 'Popis súboru:', 20,2)
					->getControlPrototype()->class('mceEditor');*/

		$form->addSubmit('save', 'Uložiť')->setAttribute('class', 'default');
		/*  $form->addSubmit('cancel', 'Zrušiť')
							->setValidationScope(FALSE)
					->onClick[] = function () use ($presenter) {
						$presenter->redirect('default');
					};*/


		$form->onSuccess[] = callback($this, 'akceFormSubmitted');
		$form->addProtection('Vypršal časový limit, odošlite formulár znovu.');

		return $form;
	}

	public function akceFormSubmitted(Form $form)
	{

		// $id = (int) $this->getParameter('id');
		//$taskId = $form['tid']->getValue();
		// $tasks = $this->context->database->table('task')->select()->where('id', $taskId)->fetch();//findBy(array('id'=>$taskId))->fetch();

//if($this->context->database->table('file')->select('task_id')->where('task_id',$id )->count() < $this->task->numfiles){
		//       $id = (int) $this->getParameter('id');

		/* if ($id > 0) {
				$this->context->database->table('file')->find($id)->update(array(
				 //   'datumOd' => $form->values->datumOd,
				   // 'datumDo' => $form->values->datumDo,
					'desc' => $form->values->popis
				));
					$file = $form['obrazek']->getValue();
					$imgUrl = $this->context->params['wwwDir'] . '/images/upload/' . $id . '_' . $file->name;
					$file->move($imgUrl);

					$this->context->database->table('file')->find($id)->update(array(
						'url' => '/images/upload/' . $id . '_' . $file->name
					));

				$this->flashMessage('Akce upravena.', 'success');
			} else {*/
		/*   $inserted = $this->context->database->table('user')->insert(array(
			   // 'datumOd' => $form->values->datumOd,
			  //  'datumDo' => $form->values->datumDo,
			   'role_id' => '6',
			   'name' => 'nieco',
			   'surname' => 'nieco2'
			   // 'task_id' => $this->idtask,
			 //   'desc' => $form->values->popis,
				//   'created' => new DateTime
					));*/
		//  $newid = $inserted->id;

		$file = $form['obrazek']->getValue();

		//  $projektURL =  Strings::webalize($this->task->project->text, NULL, FALSE);
		//$shortFilename = $newid . '_' . $this->task->project->acronym.'_'.$this->task->user_subj->user->login.'_'.$file->name;
		$shortFilename = $file->name;
		$imgUrl = $this->context->params['wwwDir'] . '/images/upload/csv/' . $shortFilename;

		/*    $this->context->database->table('file')->find($newid)->update(array(
				   'filename' => $shortFilename,
					'url' => '/images/upload/'.$projektURL.'/'. $shortFilename
				));*/
		if ($file->move($imgUrl)) {
			/*require_once '../Classes/PHPExcel/IOFactory.php';

			$objPHPExcel = PHPExcel_IOFactory::load($subor);


			$value = $objPHPExcel->getActiveSheet()->getCell('A1')->getValue();
			echo 'The Value is: '.$value;*/
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
