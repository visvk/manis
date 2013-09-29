<?php
use Nette\Application\UI\Form;
use Grido\grid,
    Grido\Components\Filters\Filter,
    Grido\Components\Columns\Column,
    Nette\Utils\Html;
class Proj_usPresenter extends BasePresenter
{
private $taskRepository;
private $proj_usRepository;
private $userRepository;
private $projectRepository;
private $project;

    public function startup()
	{		parent::startup();		
			if (!$this->user->isLoggedIn()) {
				$this->redirect('Sign:in', array(
					'backlink' => $this->storeRequest()));

			} else {
				if (!$this->user->isAllowed('Reg')) {
					$this->flashMessage('Access denied');
					$this->redirect('Sign:in'); }
			}		
        }

public function inject(Project\proj_usRepository $proj_usRepository,Project\userRepository $userRepository,
        Project\projectRepository $projectRepository)
{


$this->proj_usRepository = $proj_usRepository;
$this->userRepository = $userRepository;
$this->projectRepository = $projectRepository;
}

	public function actionDefault($id)
	{//$this->project = $this->projectRepository->findAll()->where('project_id', $id)->fetch();
		$this->project = $this->projectRepository->findBy(array('id' => $id))->fetch();
		if ($this->project === FALSE) {
			$this->setView('notFound');
		}
	}


	public function renderDefault()
	{
		$this->template->project = $this->project;
		//$this->template->tasks = $this->projectRepository->tasksOf($this->project);
	}
        
   
        
}