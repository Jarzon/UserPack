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
    protected $userModel;

    public function __construct(View $view, array $options,
                                User $user, UserModel $userModel)
    {
        parent::__construct($view, $options);

        $this->user = $user;
        $this->userModel = $userModel;
    }

    public function index()
    {
        $form = $this->getForm();

        if ($form->submitted()) {
            try {
                $values = $form->validation();

                if($this->submit($values)) {
                    $this->redirection();
                }
            }
            catch (\Jarzon\ValidationException $e) {
                $this->message('error', $e->getMessage());
            }
        }

        $this->render('signin', 'UserPack', ['form' => $form]);
    }

    protected function getForm()
    {
        $form = new Form($_POST);

        $form
            ->text('name')->required()
            ->password('password')->required()
            ->checkbox('remember')->value(true)->selected()

            ->submit();

        return $form;
    }

    protected function submit(array $values)
    {
        if (empty($values)) return false;

        $infos = $this->userModel->signin([$values['name']]);

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