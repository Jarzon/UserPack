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
            catch (\Exception $e) {
                $this->addVar('message', ['error', $e->getMessage()]);
            }
        }

        $this->design('signup', 'UserPack', ['form' => $form]);
    }

    protected function getForm() {
        $form = new Form($_POST);

        $form
            ->email('email')->required()
            ->text('name')->required()
            ->password('password')->required()

            ->submit();

        return $form;
    }

    protected function submit(array $values = []) {
        if(empty($values)) return false;

        $user = $this->getUserModel();

        $values['password'] = hash('sha512', $values['email'].$values['password'].$values['name']);

        if($user->exists($values['email'], $values['name'])) {
            $this->addVar('message', ['error', 'that email/name is already used by another account']);
            return false;
        }

        $id = $user->signup($values);

        return $this->signin($values, $id);
    }

    protected function signin($values, $id) {
        $_SESSION['user_id'] = $id;
        $_SESSION['email'] = $values['email'];
        $_SESSION['name'] = $values['name'];
        $_SESSION['level'] = 0;

        return true;
    }

    protected function redirection() {
        $this->redirect('/');
    }
}