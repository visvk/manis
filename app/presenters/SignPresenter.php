<?php

use Nette\Application\UI;


/**
 * Sign in/out presenters.
 */
class SignPresenter extends BasePresenter
{


	/**
	 * Sign-in form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm()
	{
		$form = new UI\Form;
		$form->addText('username', 'Prihlasovacie meno(LDAP):')
			->setRequired('Prosím, vložte meno.');

		$form->addPassword('password', 'Heslo:')
			->setRequired('Prosím, vložte heslo.');

		$form->addCheckbox('remember', 'Zostať prihlásený?');

		$form->addSubmit('send', 'Sign in');

		// call method signInFormSubmitted() on success
                $form->addProtection('Vypršal čas na prihlásenie.');
		$form->onSuccess[] = $this->signInFormSubmitted;
		return $form;
	}



	public function signInFormSubmitted($form)
	{
		$values = $form->getValues();

		if ($values->remember) {
			$this->getUser()->setExpiration('+ 14 days', FALSE);
		} else {
			$this->getUser()->setExpiration('+ 30 minutes', TRUE);
		}

		try {
			$this->getUser()->login($values->username, $values->password);
		} catch (Nette\Security\AuthenticationException $e) {
			$form->addError($e->getMessage());
			return;
		}

		$this->redirect('Homepage:');
	}



	public function actionOut()
	{
		$this->getUser()->logout();
		$this->flashMessage('Boli Ste odhlásení.');
		$this->redirect('in');
	}

}
