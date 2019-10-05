<?php
namespace UserPack\Controller;

use Jarzon\Form;
use Prim\AbstractController;
use Prim\View;
use UserPack\Model\UserModel;
use UserPack\Service\User;

class Signin extends AbstractController
{
    protected $user;
    protected $userForm;
    protected $userModel;

    public function __construct(View $view, array $options,
                                User $user, UserForm $userForm, UserModel $userModel)
    {
        parent::__construct($view, $options);

        $this->user = $user;
        $this->userForm = $userForm;
        $this->userModel = $userModel;
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

        return $this->user->signin($infos->id, $infos->email, $infos->name, $infos->status, $values['status'] >= 4, $values['remember']);
    }

    protected function redirection() {
        $this->redirect('/');
    }
}