<?php
namespace UserPack\Controller;

use Jarzon\Form;

class Signup extends User
{
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

        $this->render('signup', 'UserPack', ['form' => $form]);
    }

    protected function getForm()
    {
        $form = new Form($_POST);

        $form
            ->email('email')->required()
            ->text('name')->required()
            ->password('password')->required()

            ->submit();

        return $form;
    }

    protected function submit(array $values = [])
    {
        if(empty($values)) return false;

        $user = $this->getUserModel();

        if($user->exists($values['email'], $values['name'])) {
            $this->message('error', 'that email/name is already used by another account');
            return false;
        }

        $values['password'] = $this->user->hashPassword($values['password']);

        $id = $user->signup($values);

        $this->welcomeEmail($values);

        return $this->signin($values, $id);
    }

    protected function welcomeEmail(array $user)
    {
        $message = $this->view->fetch('email/signup', 'UserPack', ['user' => $user]);

        $this->sendEmail($user['email'], $user['name'], "{$this->options['project_name']} - Signup", $message);
    }

    protected function signin($values, $id)
    {
        $_SESSION['user_id'] = $id;
        $_SESSION['email'] = $values['email'];
        $_SESSION['name'] = $values['name'];
        $_SESSION['level'] = 0;

        return true;
    }

    protected function redirection()
    {
        $this->redirect('/');
    }
}