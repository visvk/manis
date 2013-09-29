<?php
use Nette\Application\UI\Form;
use Nette\Application\UI;



class RegistrationPresenter extends BasePresenter
{
    public function startup()
	{
		parent::startup();

		
			if (!$this->user->isLoggedIn()) {

				$this->redirect('Sign:in', array(
					'backlink' => $this->storeRequest()
				));

			} else {
				if (!$this->user->isAllowed('Reg')) {
					$this->flashMessage('Access denied');
					$this->redirect('Sign:in');
				}
			}
		
	}

private $userRepository;
private $roleRepository;


public function inject(Project\UserRepository $userRepository,Project\RoleRepository $roleRepository )
{

$this->userRepository = $userRepository;
$this->roleRepository = $roleRepository;
}

 
	
	protected function createComponentRegistrationForm()
    {  $skupina = $this->roleRepository->findAllRoles()->fetchPairs('id','role');
          
            
        $form = new Form();
        $form->addText('username', 'Login (LDAP):', 30, 40)
            ->addRule(Form::FILLED, 'Je nutné zadať username.');
        $form->addText('name', 'Krstné meno:', 30, 40)
            ->addRule(Form::FILLED, 'Je nutné zadať krstné meno.');
        $form->addText('surname', 'Priezvisko:', 30, 40)
            ->addRule(Form::FILLED, 'Je nutné zadať priezvisko.');
        $form->addText('email', 'e-mail:', 30, 40)
            ->addRule(Form::FILLED, 'Je nutné zadať email.')
                ->addRule(Form::EMAIL, 'Neplatný formát pre email.');
        
      /*** Ak je potrebne, pre offline pracu pri registracii je treba zadat heslo, aby sa mohol pouzivatel autentifikovat.
       * Ak sa pridaju tieto riadky, pre spravnost je nutne upravit RegistrationFormSubmitted, registry.latte a UserRepository.
         $form->addPassword('newPassword', 'Heslo:', 30)
            ->addRule(Form::MIN_LENGTH, 'Nové heslo musí mat alespoň %d znakov.', 4);
        $form->addPassword('confirmPassword', 'Potvrdenie hesla:', 30)
            ->addRule(Form::FILLED, 'Nové heslo je nutné zadat ešte raz pre potvdenie.')
            ->addRule(Form::EQUAL, 'Zadané hesla sa musia zhodovat.', $form['newPassword']);*/
       
       
             $form->addSelect('rola','Rola', $skupina)
                     ->setPrompt('Zvoľte rolu')
                     ->addRule(Form::FILLED, 'Musite zadať rolu.');
            
        $form->addSubmit('set', 'Registrovat');
        $form->onSuccess[] = $this->RegistrationFormSubmitted;
    
        return $form;
    
    }
    
    public function RegistrationFormSubmitted(Form $form)
    {
        $values = $form->getValues();
          
            $this->userRepository->userRegistration($values->username, $values->name,$values->surname, $values->rola, $values->email);
            $this->flashMessage('Registrácia prebehla úspešne.', 'success');
            $this->redirect('Homepage:');
    
    }
    
   




}
