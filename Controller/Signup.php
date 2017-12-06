<?php
namespace UserPack\Controller;

use PrimUtilities\Forms;

class Signup extends User
{
    public function index()
    {
        $forms = $this->getForms();

        if (isset($_POST['submit_signup'])) {
            try {
                $values = $forms->verification();
            }
            catch (\Exception $e) {
                $this->addVar('message', ['error', $e->getMessage()]);
            }

            if($this->submit($values))
                $this->redirection();
        }

        $this->design('signup', 'UserPack', ['forms' => $forms->getForms()]);
    }

    protected function getForms() {
        $forms = new Forms($_POST);

        $forms->email('email')->required();
        $forms->text('name')->required();
        $forms->password('password')->required();

        return $forms;
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