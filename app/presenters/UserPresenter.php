<?php

use Nette\Application\UI\Form;
use Nette\Security as NS;

/**
 */
class UserPresenter extends \BasePresenter
{
    /** @var Project\UserRepository */
    private $userRepository;

    /** @var Project\Authenticator */
    private $authenticator;

   public function startup()
    {
        parent::startup();
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }
        if (!$this->user->isAllowed('Default')){
                $this->flashMessage('Access denied');
                $this->redirect('Sign:in');
            }
        $this->userRepository = $this->context->userRepository;
        $this->authenticator = $this->context->authenticator;
    }

    protected function createComponentPasswordForm()
    {
        $form = new Form();
        $form->addPassword('oldPassword', 'Staré heslo:', 30)
            ->addRule(Form::FILLED, 'Je nutné zadat staré heslo.');
        $form->addPassword('newPassword', 'Nové heslo:', 30)
            ->addRule(Form::MIN_LENGTH, 'Nové heslo musí mat alespoň %d znakov.', 6);
        $form->addPassword('confirmPassword', 'Potvdenie hesla:', 30)
            ->addRule(Form::FILLED, 'Nové heslo je nutné zadat este raz pre potvrdenie.')
            ->addRule(Form::EQUAL, 'Zadané hesla sa musia zhodovat.', $form['newPassword']);
        $form->addSubmit('set', 'Zmenit heslo');
        $form->onSuccess[] = $this->passwordFormSubmitted;
        return $form;
    }
      


    public function passwordFormSubmitted(Form $form)
    {
        $values = $form->getValues();
        $user = $this->getUser();
        
        try {
            $this->authenticator->authenticate(array($user->getIdentity()->login, $values->oldPassword));
            $this->userRepository->setPassword($user->getId(), $values->newPassword);
            $this->flashMessage('Heslo bolo zmenené.', 'success');
            $this->redirect('Homepage:');
        } catch (NS\AuthenticationException $e) {
            $form->addError('Zadané heslo nieje správne.');
        }
       
    }
    
   
    
    
}