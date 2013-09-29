<?php

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
private $projectRepository;



	public function injectBase(Project\ProjectRepository $projectRepository)
	{
		$this->projectRepository = $projectRepository;
	}



	public function beforeRender()
	{
		$this->template->lists = $this->projectRepository->findAll()->order('text ASC');
	}
}
