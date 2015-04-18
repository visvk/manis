<?php

use Nette\Application\UI\Form;
use Nette\Diagnostics;
use Grido\grid,
	Grido\Components\Filters\Filter,
	Grido\Components\Columns\Column,
	Nette\Utils\Html;

class TaskPresenter extends BasePresenter
{

	private $taskRepository;
	private $fileRepository;
	private $proj_usRepository;
	private $userRepository;
	private $user_subjRepository;
	private $projectRepository;
	private $project;
	private $idproj;
	private $projEd;


	public function startup()
	{
		parent::startup();
		if (!$this->user->isLoggedIn()) {
			$this->redirect('Sign:in', array(
				'backlink' => $this->storeRequest()));
		} /*else {
            if (!$this->user->isAllowed('Reg')){
                $this->flashMessage('Access denied');
                $this->redirect('Sign:in');
            }
        }*/
	}

	public function inject(Project\FileRepository $fileRepository, Project\TaskRepository $taskRepository, Project\Proj_usRepository $proj_usRepository, Project\userRepository $userRepository, Project\projectRepository $projectRepository, Project\user_subjRepository $user_subjRepository)
	{

		$this->taskRepository = $taskRepository;
		$this->fileRepository = $fileRepository;
		$this->proj_usRepository = $proj_usRepository;
		$this->userRepository = $userRepository;
		$this->user_subjRepository = $user_subjRepository;
		$this->projectRepository = $projectRepository;
	}

	public function actionDefault($id)
	{

		$this->project = $this->projectRepository->findBy(array('id' => $id))->fetch();
		$brb = $this->proj_usRepository->findBy(array('project_id' => $id,
			'user_subj.user_id' => $this->user->getId()));
		if (!$this->user->isAllowed('Default') && $brb->count() === 0) {
			$this->flashMessage('Access denied');
			$this->redirect('Sign:in');
		}
		$this->idproj = $id;
		if ($this->project === FALSE || $id === NULL) {
			$this->setView('notFound');
		}

		$aleluja = $this->taskRepository->findBy(array('project_id' => $id));
		$suma = $aleluja->aggregation('SUM(grade)');
		$count = $aleluja->count();
		if ($count != 0) {
			$division = (int)$suma / $count;
		} else {
			$division = 0;
		}
		if ($this->project->grade != $division) {
			$this->projectRepository->findBy(array('id' => $id))->update(array(
					'grade' => $division
				)
			);

		}
	}

	public function actionEdit($id)
	{
		if (!$this->user->isAllowed('Reg')) {
			$this->flashMessage('Access denied');
			$this->redirect('Sign:in');
		}

		//  $this->task = $this->taskRepository->findBy(array('id' => $id))->fetch();
		$this->idproj = $id;


		$form = $this['projEditForm'];
		if (!$form->isSubmitted()) {
			$this->projEd = $this->projectRepository->findById($id);
			if (!$this->projEd) {
				$this->error('Zaznam nenajdeny');
			}
			$form->setDefaults($this->projEd);
			$form->getComponent('created')->setValue($this->projEd->created);
		}
	}

	public function actionFiles($id)
	{
		if (!$this->user->isAllowed('Default')) {
			$this->flashMessage('Access denied');
			$this->redirect('Sign:in');
		}
		$this->idproj = $id;

		$this->projEd = $this->projectRepository->findById($id);
		if (!$this->projEd) {
			$this->error('Zaznam nenajdeny');
		}
	}

	public function handleMarkDone($taskId)
	{
		if (!$this->user->isAllowed('Default')) {
			$this->flashMessage('Access denied');
			$this->redirect('Sign:in');
		}
		$this->taskRepository->markDone($taskId);


		if (!$this->presenter->isAjax()) {
			$this->presenter->redirect('this');
		}
		$this->invalidateControl();
	}

	public function handleFileDone($fileId)
	{
		if (!$this->user->isAllowed('Default')) {
			$this->flashMessage('Access denied');
			$this->redirect('Sign:in');
		}
		$this->fileRepository->fileDone($fileId);


		if (!$this->presenter->isAjax()) {
			$this->presenter->redirect('this');
		}

		$this->invalidateControl();
	}

	public function handleFileBad($fileId)
	{
		if (!$this->user->isAllowed('Default')) {
			$this->flashMessage('Access denied');
			$this->redirect('Sign:in');
		}
		$this->fileRepository->fileBad($fileId);


		if (!$this->presenter->isAjax()) {
			$this->presenter->redirect('this');
		}
		$this->invalidateControl();
	}

	public function handleFileDelete($fileId)
	{
		if (!$this->user->isAllowed('Default')) {
			$this->flashMessage('Access denied');
			$this->redirect('Sign:in');
		}

		$akcia = $this->fileRepository->findBy(array('id' => $fileId))->fetch();
		if ($akcia != NULL) {
			unlink($this->context->params['wwwDir'] . $akcia->url);
			$this->fileRepository->fileDelete($fileId);

			$this->flashMessage('Súbor zmazaný.', 'success');
		} else {
			$this->flashMessage('Problém pri zmazaní súboru (URL = NULL) alebo súbot nenájedný.', 'error');
		}
		if (!$this->presenter->isAjax()) {

			$this->presenter->redirect('this');
		}

		$this->invalidateControl();
	}

	public function renderEdit()
	{
		if (!$this->user->isAllowed('Reg')) {
			$this->flashMessage('Access denied');
			$this->redirect('Sign:in');
		}
		$this->template->project = $this->projEd;
	}

	public function renderDefault()
	{
		$this->template->abc = $this->proj_usRepository->findAll()->where("manager = 1 AND project_id = $this->idproj AND user_subj.user_id = ?", $this->user->getId())->count('*');
		$this->template->project = $this->project;
		$this->template->tasks = $this->taskRepository->findAll()->where('project_id', $this->idproj);
	}

	public function renderFiles()
	{
		if (!$this->user->isAllowed('Default')) {
			$this->flashMessage('Access denied');
			$this->redirect('Sign:in');
		}
		$this->template->project = $this->projEd;
	}


	protected function createComponentTaskRegForm()
	{
		$users = $this->proj_usRepository->getAssignedUsersByProjectId($this->idproj);
		//  $projects = $this->projectRepository->findAll()->fetchPairs('id', 'text');

		$form = new Form();
		$form->addText('text', 'Názov úlohy:', 25, 40)
			->addRule(Form::FILLED, 'Je nutné zadať názov úlohy.');
		$form->addText('numfiles', 'Počet výstupov:')
			->setType('number')
			->addRule(Form::RANGE, 'Počet musí byť od %d do %d', array(0, 10))
			->addRule(Form::FILLED, 'Je nutné zadať počet výstupov.');
		/* $form->addTextArea('desc', 'Popis úlohy:', 20, 2)
					->addRule(Form::FILLED, 'Je nutné zadať popis úlohy.');*/

		$form->addSelect('solver', 'Riešiteľ: ', $users)
			->setPrompt('Zvolte riešiteľa')
			->addRule(Form::FILLED, 'Musíte zadať riešiteľa.');
		$form->addDatePicker('birthDate', 'Riešenie od:')
			->setValue($this->project->created)
			->setAttribute('class', 'birthDate')
			->addRule(Form::VALID, 'Vložený dátum je neplatný!');
		$form->addDatePicker('endDate', 'Riešenie do:')
			->setValue($this->project->submitted)
			->setAttribute('class', 'endDate')
			->addRule(Form::VALID, 'Vložený dátum je neplatný!');
		$form->addSubmit('set', 'Vytvoriť');
		$form->onSuccess[] = $this->TaskRegFormSubmitted;

		return $form;
	}

	public function TaskRegFormSubmitted(Form $forma)
	{

		if (!$this->user->isAllowed('Default') && $this->proj_usRepository->findAll()
				->where('manager = ? AND project_id = ? AND user_subj.user_id = ?', 1, $this->idproj, $this->user->getId())->count('*') == 0
		) {
			$this->flashMessage('Access denied');
			$this->redirect('Sign:in');
		}
		$values = $forma->getValues();
		if ($values->birthDate < $values->endDate) {
			$this->taskRepository->taskRegistration($values->text, $this->idproj, $values->solver, $values->birthDate, $values->endDate, $values->numfiles);
			$this->flashMessage('Vytvorili ste úlohu.', 'success');
			$this->redirect('this');
		} else {
			$this->flashMessage('"Riešenie od" je väčsie ako "Riešenie do".', 'error');
		}
	}

	protected function createComponentProj_usRegForm()
	{
		$users = $this->user_subjRepository->getUsersBySubjectId($this->project->subject_id);

		$form = new Form();

		$form->addSelect('solver', 'Riešiteľ', $users)
			->setPrompt('Zvoľte riešiteľa')
			->addRule(Form::FILLED, 'Musíte zadať riešiteľa.');
		$form->addCheckbox('manager', 'Manažér');
		$form->addSubmit('set', 'Pridať k projektu');
		$form->onSuccess[] = $this->Proj_usRegFormSubmitted;

		return $form;
	}

	public function Proj_usRegFormSubmitted(Form $form)
	{
		if (!$this->user->isAllowed('Default')) {
			$this->flashMessage('Access denied');
			$this->redirect('Sign:in');
		}
		$valuesus = $form->getValues();
		$userCount = $this->proj_usRepository->findAll();
		if ($userCount->where('project_id', $this->idproj)->getCount() <= $this->project->solver) {
			if ($userCount->where('project_id = ? AND user_subj_id =?', $this->idproj, $valuesus->solver)->getCount() == 0) {
				$count = $this->proj_usRepository->findAll()
					->where('manager = ? AND project_id = ?', 1, $this->idproj)->getCount('*');


				$manager = $valuesus->manager ? 1 : 0;

				if ($count != 0 && $manager === 1) {
					$this->flashMessage('Úloha už má manažéra.', 'error');
					$this->redirect('this');
				} else {
					$this->proj_usRepository->proj_usRegistration($valuesus->solver, $this->idproj, $manager);
					$this->flashMessage('Pridali ste riešiteľa.', 'success');
					$this->redirect('this');
				}
			} else {
				$this->flashMessage('Riešiteľ už je priradený k projektu.', 'error');
			}
		} else {
			$this->flashMessage('Je dosiahnutý maximálny počet riešiteľov.', 'error');
		}
	}

	protected function createComponentTaskGrid($name)
	{
		$grid = new Grid($this, $name);
		$grid->translator->setLang('sk');
		$grid->setModel($this->taskRepository->getTasksByProjectId($this->idproj))
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
		if ($this->user->isAllowed('Default')) {
			$grid->addAction('markDone', 'OK')
				->setCustomRender(function ($item) {
					$baseUr = Html::el('a')->href('?taskId=' . $item->id . '&id=' . $item->project_id . '&do=markDone&presenter=Task')->setText('OK');
					// http://localhost/New%20Folder/www/?fileId=21&id=41&do=fileBad&presenter=TaskDetail
					$spolu = $item->grade != 100 ? ' <img src="images/Check.png">' . $baseUr : '';
					return $spolu;
				});
		}
		return $grid;
	}


	protected function createComponentProj_usGrid($name)
	{
		$grid = new Grid($this, $name);
		$grid->setModel($this->proj_usRepository->getUsersAndManagerByProjectId($this->idproj))
			->setDefaultPerPage(5);
		$grid->addColumn('login', 'Riešitel');


		$grid->addColumn('manager', 'Manažér')
			->setCustomRender(function ($item) {
				$baseUr = $item->manager == 1 ? 'Áno' : 'Nie';
				return $baseUr;
			})
			->setSortable();


		return $grid;
	}

	protected function createComponentProjEditForm()
	{

		$form = new Form();
		$form->addHidden('id');
		$form->addText('text', 'Názov projektu:', 30, 20)
			->addRule(Form::FILLED, 'Je nutné zadať názov projektu.');
		$form->addText('acronym', 'Akronym:', 6, 20)
			->addRule(Form::FILLED, 'Je nutné zadať arkonym projektu.');

		$form->addDatePicker('created', 'Riešenie od:')
			->setAttribute('class', 'created')
			->addRule(Form::VALID, 'Vložený dátum je neplatný!');
		$form->addDatePicker('submitted', 'Riešenie do:')
			->setAttribute('class', 'submitted')
			->addRule(Form::VALID, 'Vložený dátum je neplatný!');
		$form->addSubmit('set', 'Upraviť');
		$form->onSuccess[] = $this->ProjEditFormSubmitted;

		return $form;
	}

	public function ProjEditFormSubmitted(Form $forma)
	{
		if (!$this->user->isAllowed('Reg')) {
			$this->flashMessage('Access denied');
			$this->redirect('Sign:in');
		}
		$values = $forma->getValues();
		if ($values->created < $values->submitted) {
			$this->projectRepository->projectEdit($values->id, $values->text, $values->acronym, $values->created, $values->submitted);
			$this->flashMessage('Upravili ste projekt.', 'success');
			$this->redirect('default', $values->id);
		} else $this->flashMessage('Zadaný dátum je zlý.', 'error');
	}

	public $filterRenderType = Filter::RENDER_INNER;

	protected function createComponentFilesGrid($name)
	{
		$grid = new Grid($this, $name);
		$grid->translator->setLang('sk');
		$grid->setModel($this->fileRepository->getFilesByProjectId($this->idproj))
			->setDefaultPerPage(5);
		$grid->addColumn('filename', 'Názov súboru')
			->setCustomRender(function ($item) {
				$baseUr = Html::el('a')->href('?presenter=TaskDetail&do=downloadFile&id=' . $item->id)->setText($item->filename);

				return $baseUr;
			})
			->setSortable();

		$grid->addColumn('text', 'Úloha')
			->setSortable()
			->setCustomRender(function ($item) {
				$baseUr = Html::el('a')->href('?presenter=TaskDetail&action=default&id=' . $item->task_id)->setText($item->text);

				return $baseUr;
			});

		$grid->addColumn('login', 'Riešitel')
			->setSortable();
		$grid->addColumn('updated', 'Pridané:', Column::TYPE_DATE)
			->setSortable()
			->setDateFormat(Grido\Components\Columns\Date::FORMAT_TEXT);

		$grid->addColumn('gradf', 'Stav')
			->setCustomRender(function ($item) {
				if ($item->gradf == 0) {
					$perc = 'Neohodnotené';
				} else {
					$perc = ($item->gradf == 1) ? 'OK' : 'Neprešlo';
				};

				return $perc;
			})
			->setSortable();
		$grid->addAction('fileDone', 'OK')
			->setCustomRender(function ($item) {
				$baseUr = Html::el('a')->href('?fileId=' . $item->id . '&id=' . $item->project_id . '&do=fileDone&action=files&presenter=Task')->setText('OK');

				$spolu = $item->gradf != 1 ? ' <img src="images/Check.png">' . $baseUr : '';
				return $spolu;
			});
		$grid->addAction('fileBad', 'Neprešlo')
			->setCustomRender(function ($item) {
				$baseUr = Html::el('a')->href('?fileId=' . $item->id . '&id=' . $item->project_id . '&do=fileBad&action=files&presenter=Task')->setText('Neprešlo');

				$spolu = $item->gradf != 2 ? ' <img src="images/Cancel.png">' . $baseUr : '';
				return $spolu;
			});
		$grid->addAction('fileDelete', 'Zmazať')
			->setCustomRender(function ($item) {
				$baseUr = Html::el('a')->href('?fileId=' . $item->id . '&id=' . $item->project_id . '&do=fileDelete&action=files&presenter=Task')->setText('Zmazať');

				$spolu = ' <img src="images/Delete.png">' . $baseUr;
				return $spolu;
			});

		return $grid;
	}
}
