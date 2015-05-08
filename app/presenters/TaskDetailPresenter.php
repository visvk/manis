<?php
use Nette\Application\UI\Form;
use Nette\Application\Responses\FileResponse;
use Grido\grid,
	Grido\Components\Filters\Filter,
	Grido\Components\Columns\Column,
	Nette\Utils\Html,
	Nette\Utils\Strings;

class TaskDetailPresenter extends BasePresenter
{
	private $taskRepository;
	private $fileRepository;
	private $proj_usRepository;
	private $task;
	private $idtask;
	private $editID;
	private $taskEd;

	public function startup()
	{
		parent::startup();
		if (!$this->user->isLoggedIn()) {
			$this->redirect('Sign:in', array(
				'backlink' => $this->storeRequest()));

		}/* else {
				if (!$this->user->isAllowed('Reg')) {
					$this->flashMessage('Access denied');
					$this->redirect('Sign:in'); }
			}*/
	}

	public function inject(Project\TaskRepository $taskRepository, Project\FileRepository $fileRepository, Project\Proj_usRepository $proj_usRepository)
	{

		$this->taskRepository = $taskRepository;
		$this->fileRepository = $fileRepository;
		$this->proj_usRepository = $proj_usRepository;
	}

	public function actionDefault($id)
	{
		$brb = $this->taskRepository->findBy(array('task.id' => $id,
			'user_subj.user_id' => $this->user->getId()));
		if (!$this->user->isAllowed('Default') && $brb->count() === 0) {
			$this->flashMessage('Access denied');
			$this->redirect('Sign:in');
		}
//$this->task = $this->taskRepository->findAll()->where('task_id', $id)->fetch();
		$this->task = $this->taskRepository->findBy(array('id' => $id))->fetch();
		$this->idtask = $id;
		if ($this->task === FALSE || $id === NULL) {
			$this->setView('notFound');
		}


	}


	public function renderDefault()
	{
		$this->template->tasks = $this->task;
		$this->template->taskDetail = $this->taskRepository->findAll()->where('id', $this->idtask);
		$this->template->files = $this->fileRepository->findBy(array('task_id' => $this->idtask));
	}

	public function renderEdit()
	{
		$this->template->tasks = $this->taskEd;
	}

	/*    public function handleMarkDone($taskId)
	{
		$this->taskRepository->markDone($taskId);
		
		if (!$this->presenter->isAjax()) {
			$this->presenter->redirect('this');
		}
		$this->invalidateControl();
	}*/

	public function actionEdit($id)
	{

		$this->taskEd = $this->taskRepository->findBy(array('id' => $id))->fetch();
		if (!$this->user->isAllowed('Default') && $this->proj_usRepository->findAll()
				->where('manager = ? AND project_id = ? AND user_subj.user_id = ?', 1, $this->taskEd->project_id, $this->user->getId())->count('*') == 0
		) {
			$this->flashMessage('Access denied');
			$this->redirect('Sign:in');
		}
		$this->idtask = $id;
		if ($this->task === FALSE || $id === NULL) {
			$this->setView('notFound');
		}

		$form = $this['taskEditForm'];
		if (!$form->isSubmitted()) {
			$taskEdit = $this->taskRepository->findById($id);
			if (!$taskEdit) {
				$this->error('Zaznam nenajdeny');
			}
			$form->setDefaults($taskEdit);
			$form->getComponent('created')->setValue($taskEdit->created);
			$form->getComponent('submitted')->setValue($taskEdit->submitted);

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
		$akcia = $this->fileRepository->findBy(array('id' => $fileId))->fetch();
		if ($akcia != NULL) {
			unlink($this->context->getParameters()['wwwDir'] . $akcia->url);
			$this->fileRepository->fileDelete($fileId);

			$this->flashMessage('Súbor zmazaný.', 'success');
		} else {
			$this->flashMessage('Problém pri zmazaní súboru (URL = NULL).', 'error');
		}
		if (!$this->presenter->isAjax()) {

			$this->presenter->redirect('this');
		}

		$this->invalidateControl();
	}

	public function handleDownloadFile($id)
	{
		$fileDat = $this->fileRepository->findBy(array('id' => $id))->fetch();
		/*  if( $file!= NULL){
	 $this->terminate(new DownloadResponse($this->context->params['wwwDir'].$file, "$file->filename", 'contenttype'));
	 $this->flashMessage('Súbor stiahnutý.', 'success');
			}else {$this->flashMessage('Problém pri sťahovaní súboru.', 'error');}*/


		$file = $this->context->params['wwwDir'] . $fileDat->url; // soubor může být úplně mimo web root (=nestáhnutelný pomocí URL)
		$fileName = $fileDat->filename;
		$httpResponse = $this->context->getService('httpResponse');
		$httpResponse->setHeader('Pragma', "public");
		$httpResponse->setHeader('Expires', 0);
		$httpResponse->setHeader('Cache-Control', "must-revalidate, post-check=0, pre-check=0");
		$httpResponse->setHeader('Content-Transfer-Encoding', "binary");
		$httpResponse->setHeader('Content-Description', "File Transfer");
		if ($httpResponse->setHeader('Content-Length', filesize($file)) &&
			$this->sendResponse(new FileResponse($file, $fileName, 'application/octet-stream,application/force-download, application/download'))
		) {
			$this->flashMessage('Súbor stiahnut7.', 'success');
		} else {
			$this->flashMessage('Problém pri sťahovaní súboru.', 'error');
		}
		if (!$this->presenter->isAjax()) {

			$this->presenter->redirect('this');
		}

		$this->invalidateControl();
	}

	protected function createComponentTaskEditForm()
	{
		/*    $users = $this->context->database->table('proj_us')->select('user_subj_id,user_subj.user.login')
					   ->where('project_id', ( $this->taskRepository->findBy(array('id' => $this->idtask))->fetch()->project_id))
					   ->fetchPairs('user_subj_id', 'login');*/
		//  $projects = $this->projectRepository->findAll()->fetchPairs('id', 'text');

		$form = new Form();
		$form->addHidden('id');
		$form->addText('text', 'Názov úlohy:', 30, 20)
			->addRule(Form::FILLED, 'Je nutné zadať názov úlohy.');
		$form->addTextArea('desc', 'Popis úlohy:', 75, 13)
			->addRule(Form::FILLED, 'Je nutné zadať názov úlohy.');
		/* $form->addSelect('project', 'Projekt: ', $projects)
		  ->setPrompt('Zvolte projekt')
		  ->addRule(Form::FILLED, 'Musite zadat projekt.'); */
		/*   $form->addSelect('solver', 'Riešiteľ: ', $users)
					->setPrompt('Zvolte riešiteľa')
					->addRule(Form::FILLED, 'Musíte zadať riešiteľa.');*/
		$form->addDatePicker('created', 'Riešenie od:')
			->setAttribute('class', 'created')
			->addRule(Form::VALID, 'Vložený dátum je neplatný!');
		$form->addDatePicker('submitted', 'Riešenie do:')
			->setAttribute('class', 'submitted')
			->addRule(Form::VALID, 'Vložený dátum je neplatný!');
		$form->addText('numfiles', 'Počet výstupov:')
			->setType('number')
			->addRule(Form::RANGE, 'Počet musí byť od %d do %d', array(0, 10))
			->addRule(Form::FILLED, 'Je nutné zadať počet výstupov.');
		$form->addSubmit('set', 'Upraviť');
		$form->onSuccess[] = $this->TaskEditFormSubmitted;

		return $form;
	}

	public function TaskEditFormSubmitted(Form $forma)
	{
		$values = $forma->getValues();
		if ($values->created < $values->submitted) {
			$this->taskRepository
				->taskEdit($values->id, $values->text, $values->created, $values->submitted, $values->desc, $values->numfiles);
			$this->flashMessage('Upravili ste úlohu.', 'success');
			$this->redirect('default', $values->id);
		} else {
			$this->flashMessage('Zlý dátum.', 'error');
		}
	}

	public function createComponentAkceForm()
	{
		$form = new Form();
		$form->addUpload('obrazek', 'Obrázok:')
			->addRule(Form::MIME_TYPE, 'Súbor musí byť typu .zip alebo .gzip.',
				'application/zip')
			->addRule(Form::MAX_FILE_SIZE, 'Soubor je príliš veľký! Povolená veľkosť je 2M.', 2 * 1024 * 1024);
		$form->addTextArea('popis', 'Popis súboru:', 20, 2);

		$form->addSubmit('save', 'Uložiť');

		$form->onSuccess[] =  $this->akceFormSubmitted;

		return $form;
	}

	public function akceFormSubmitted(Form $form)
	{
		$id = (int)$this->getParameter('id');
		//$taskId = $form['tid']->getValue();
		// $tasks = $this->context->database->table('task')->select()->where('id', $taskId)->fetch();//findBy(array('id'=>$taskId))->fetch();

		if ($this->fileRepository->getFilesByTaskId($id)->count() < $this->task->numfiles) {
			$id = (int)$this->getParameter('id');

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
			$inserted = $this->fileRepository->createFile($form->values->popis, $this->idtask);

			$newid = $inserted->id;

			$file = $form['obrazek']->getValue();

			$projektURL = Strings::webalize($this->task->project->text, NULL, FALSE);
			$shortFilename = $newid . '_' . $this->task->project->acronym . '_' . $this->task->user_subj->user->login . '_' . $file->name;
			$imgUrl = $this->context->getParameters()['wwwDir'] . '/images/upload/' . $projektURL . '/' . $shortFilename;

			$this->fileRepository->findById($newid)->update(array(
				'filename' => $shortFilename,
				'url' => '/images/upload/' . $projektURL . '/' . $shortFilename
			));
			$file->move($imgUrl);
			$this->flashMessage('Súbor pridaný.', 'success');

		} else {
			$this->flashMessage('Maximálny počet súborov je ' . $this->task->numfiles . '.', 'error');
		}
	}


}