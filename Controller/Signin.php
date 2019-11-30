<?php
namespace UserPack\Controller;

use Prim\AbstractController;
use Prim\View;
use UserPack\Form\SignInForm;
use UserPack\Service\User;

class Signin extends AbstractController
{
    protected User $user;
    protected UserForm $userForm;
    protected SignInForm $signInForm;

    public function __construct(View $view, array $options,
                                User $user, UserForm $userForm, SignInForm $signInForm)
    {
        parent::__construct($view, $options);

        $this->user = $user;
        $this->userForm = $userForm;
        $this->signInForm = $signInForm;
    }

    public function index()
    {
        if ($this->userForm->submitted()) {
            try {
                $values = $this->userForm->validation();

                if($this->submit($values)) {
                    $this->redirection();
                }
            }
            catch (\Jarzon\ValidationException $e) {
                $this->message('error', $e->getMessage());
            }
        }

        $this->render('signin', 'UserPack', [
            'form' => $this->userForm->getForm()
        ]);
    }

    protected function submit(array $values)
    {
        if (empty($values)) return false;

        $infos = $this->userModel->signin($values['email']);

        if (!$infos || !password_verify($values['password'], $infos->password)) {
            $this->message('error', 'wrong password or username');
            return false;
        }

        if (password_needs_rehash($infos->password, $this->options['password']['algo'], $this->options['password']['options'])) {
            $this->userModel->saveSettings(['password' => $this->user->hashPassword($values['password'])], $infos->id);
        }

        return $this->user->signin($infos->id, $infos->email, $infos->name, $infos->status, $values['status'] >= 4, $values['remember']);
    }

    protected function redirection() {
        $this->redirect('/');
    }
}