<?php
use Grido\grid,
	Grido\Components\Filters\Filter,
	Grido\Components\Columns\Column,
	Nette\Utils\Html;

/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{
	public function startup()
	{
		parent::startup();

		if (!$this->user->isLoggedIn()) {

			$this->redirect('Sign:in', array(
				'backlink' => $this->storeRequest()
			));

		}

	}

	private $taskRepository;
	private $projectUserReposiroty;


	public function inject(Project\TaskRepository $taskRepository, Project\Proj_usRepository $projUs)
	{
		$this->taskRepository = $taskRepository;
		$this->projectUserReposiroty = $projUs;
	}


	public function renderDefault()
	{
		$this->template->tasks = $this->taskRepository->findAll();
	}

	protected function createComponentTaskGrid($name)
	{
		$grid = new Grid($this, $name);
		$grid->translator->setLang('sk');
		$grid->setModel($this->taskRepository->getTasksByUserId($this->user->getId()))
			->setDefaultPerPage(5);


		$grid->addColumn('text', 'Úloha')
			->setSortable()
			->setReplacement(array('published' => 'ok'))
			->setCustomRender(function ($item) {
				$baseUr = Html::el('a')->href('?presenter=TaskDetail&action=default&id=' . $item->id)->setText($item->text);

				return $baseUr;
			});

		$grid->addColumn('projtext', 'Projekt')
			->setCustomRender(function ($item) {
				$baseUr = Html::el('a')->href('?presenter=Task&action=default&id=' . $item->project_id)->setText($item->projtext);

				return $baseUr;
			})
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
			->setDefaultSort('ASC')
			->setCustomRender(function ($item) {
				$perc = $item->grade . '%';

				return $perc;
			})
			->setSortable();
		return $grid;
	}

	protected function createComponentProjectGrid($name)
	{
		$grid = new Grid($this, $name);
		// $grid->translator->setLang('sk');
		$grid->setModel($this->projectUserReposiroty->getProjectsByUserId($this->user->getId()))
			->setDefaultPerPage(5);


		$grid->addColumn('text', 'Projekt')
			->setSortable()
			->setReplacement(array('published' => 'ok'))
			->setCustomRender(function ($item) {
				$baseUr = Html::el('a')->href('?presenter=Task&action=default&id=' . $item->id)->setText($item->text);

				return $baseUr;
			});


		$grid->addColumn('created', 'Riešenie od:', Column::TYPE_DATE)
			->setSortable()
			->setDateFormat(Grido\Components\Columns\Date::FORMAT_TEXT);
		//->setFilter(Filter::TYPE_DATE);
		$grid->addColumn('submitted', 'Riešenie do:', Column::TYPE_DATE)
			->setSortable()
			->setDateFormat(Grido\Components\Columns\Date::FORMAT_TEXT);
		//  ->setFilter(Filter::TYPE_DATE);


		$grid->addColumn('grade', 'Stav')
			->setDefaultSort('ASC')
			->setCustomRender(function ($item) {
				$perc = $item->grade . '%';

				return $perc;
			})
			->setSortable();
		return $grid;
	}

}
