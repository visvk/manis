<?php

use Nette\Application\UI\Form;
use Nette\Application\UI;
use Grido\grid,
    Grido\Components\Filters\Filter,
    Grido\Components\Columns\Column,
    Nette\Utils\Html;

class ProjectPresenter extends BasePresenter {

    public function startup() {
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

    private $projectRepository;
    private $subjectRepository;
    private $proj_usRepository;
    private $userSubjectRepository;

    public function inject(Project\ProjectRepository $projectRepository,
						   Project\User_subjRepository $userSubjectRepository,
						   Project\Proj_usRepository $proj_usRepository,
						   Project\SubjectRepository $subjectRepository) {

        $this->projectRepository = $projectRepository;
        $this->subjectRepository = $subjectRepository;
        $this->proj_usRepository = $proj_usRepository;
        $this->userSubjectRepository = $userSubjectRepository;
    }

    protected function createComponentProjRegForm() {
		if ($this->user->isAllowed('Reg')) {
			$predmety = $this->subjectRepository->findAllSubject()->fetchPairs('id', 'subject');
		} else {
			$predmety = $this->subjectRepository->findAllSubjectByUserId($this->user->id)->fetchPairs('id', 'subject');
		}


        $form = new Form();
        $form->addText('text', 'Názov projektu:', 10, 60)
                ->addRule(Form::FILLED, 'Je nutné zadať názov projektu.');
        $form->addText('acronym', 'Akronym:', 5, 20)
                ->addRule(Form::FILLED, 'Je nutné zadať názov projektu.');
        $form->addText('solver', 'Riešitelia:',2)
                ->setType('number')
                
                ->addRule(Form::RANGE, 'Počet musí byť od %d do %d', array(1, 10));
        $form->addSelect('subject', 'Predmet: ', $predmety)
                ->setPrompt('Zvoľte predmet')
                ->addRule(Form::FILLED, 'Musite zadať predmet.');
        $form->addDatePicker('birthDate', 'Riešenie od:')
                ->setAttribute('class', 'birthDate')
                ->setOption('description', 'Defaultý dátum je začiatok semestra.')
                ->addRule(Form::VALID, 'Vložený dátum je neplatný!');
        // ->addRule(Form::RANGE, 'Entered date is not within allowed range.', array(new DateTime('september, -1 year'), new DateTime('July')));
        $form->addDatePicker('endDate', 'Riešenie do:')
                ->setAttribute('class', 'endDate')
                ->setOption('description', 'Defaultý dátum je koniec semestra.')
                ->addRule(Form::VALID, 'Vložený dátum je neplatný!');
        //  ->addRule(Form::RANGE, 'Entered date is not within allowed range.', array(new DateTime('7.9.'), new DateTime('24.5.')));



        $form->addSubmit('set', 'Vytvoriť');
        $form->onSuccess[] = $this->ProjRegFormSubmitted;

        return $form;
    }

    public function ProjRegFormSubmitted(Form $form) {
        if (!$this->user->isAllowed('Default')){
                $this->flashMessage('Access denied');
                $this->redirect('Sign:in');
            }
        $values = $form->getValues();
        if ($values->birthDate == NULL) {
            $bDate = $this->subjectRepository->findByID($values->subject);
            $values->birthDate = $bDate->school_year->term_start;
        }
        if ($values->endDate == NULL) {
            $bDate = $this->subjectRepository->findByID($values->subject);
            $values->endDate = $bDate->school_year->term_end;
        }
        if ($values->birthDate < $values->endDate) {


        $insert = $this->projectRepository->projectRegistration($values->text, $values->acronym, $values->subject, $values->solver, $values->birthDate, $values->endDate);

		$userSubjectId = $this->userSubjectRepository->findBy(array('user_id' => $this->user->id, 'subject_id'=>$values->subject) )->fetch();
		$this->proj_usRepository->proj_usRegistration($userSubjectId->id, $insert->id, array(
			'manager'=>1,
			'programmer'=>0,
			'designer'=>0,
			'tester' => 0,
			'analytic' =>0));
		$this->flashMessage('Vytvorili ste projekt.', 'success');
        $this->redirect('this');
        }else {$this->flashMessage('Zlý dátum.', 'error');}
    }

    /*  public function createComponentProject()
      {

      return new Project\ProjectListControl($this->projectRepository->findAll(), $this->projectRepository);

      } */
/** @var string @persistent - only for demo */
    public $filterRenderType = Filter::RENDER_INNER;
    
    protected function createComponentProjectGrid($name) {
        $grid = new Grid($this, $name);
        $grid->setModel($this->projectRepository->getProjects())
                ->setDefaultPerPage(10);

        $grid->addColumn('text', 'Názov')
                ->setSortable()
                ->setCustomRender(function($item) {
                            $baseUr = Html::el('a')->href('?presenter=Task&action=default&id=' . $item->id)->setText($item->text);

                            return $baseUr;
                        });
                        $grid->addColumn('acronym', 'Akronym');
            $grid->addColumn('solver', 'Riešitelia')
                ->setSortable();
            $grid->addColumn('subject', 'Predmet')
                ->setSortable();
        $grid->getColumn('subject');
        $grid->addColumn('created', 'Riešenie od', Column::TYPE_DATE)
                ->setSortable()
                ->setDateFormat(Grido\Components\Columns\Date::FORMAT_TEXT);
        $grid->addColumn('submitted', 'Riešenie do', Column::TYPE_DATE)
                ->setSortable()
                ->setDateFormat(Grido\Components\Columns\Date::FORMAT_TEXT);

       

        
        $grid->addColumn('year', 'Školský rok')
                ->setSortable();
        $grid->addColumn('grade', 'Stav')
                ->setCustomRender(function($item) {
                            $perc = $item->grade.'%';
                              
                            return $perc;
                        })
                ->setSortable();
        $grid->addColumn('mark', 'Známka')
                
                ->setSortable();
                    
                        
           $operations = array('A' => 'A', 'B' => 'B','C' => 'C', 'D' => 'D','E' => 'E', 'FX' => 'FX');
    $grid->setOperations($operations, callback($this, 'gridOperationsHandler'));
    
      
        

    /**
     * Handler for operations.
     * @param string $operation
     * @param array $id
     */
                  
        return $grid;
    }
    public function gridOperationsHandler($operation, $id)
    {if (!$this->user->isAllowed('Reg')){
                $this->flashMessage('Access denied');
                $this->redirect('Sign:in');
            }
        if ($id) {
            
            $this->projectRepository->mark($id,$operation);
            $b = count($id);
            $a = ( $b ==1) ? "$b projektu" : "$b projektom";
            $this->flashMessage("Známka '$operation' bola pridelená $a.", 'info');
            } else {
            $this->flashMessage('No rows selected.', 'error');
        }

       // $this->redirect($operation, array('id' => $id));
    }
         
      
}
