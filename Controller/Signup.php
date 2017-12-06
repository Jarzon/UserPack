<?php
namespace UserPack\Controller;

use PrimUtilities\Forms;

class Signup extends User
{
    public function getForms() {
        $forms = new Forms($_POST);

        $forms->email('email')->required();
        $forms->text('name')->required();
        $forms->password('password')->required();

        return $forms;
    }

    public function signup()
    {
        $user = $this->getUserModel();

        $forms = $this->getForms();

        if (isset($_POST['submit_signup'])) {
            try {
                $values = $forms->verification();
            }
            catch (\Exception $e) {
                $this->addVar('message', ['error', $e->getMessage()]);
            }

            if(!empty($values)) {
                $values['password'] = hash('sha512', $values['email'].$values['password'].$values['name']);

                if(!$user->exists($values['email'], $values['name'])) {
                    $id = $user->signup($values);

                    $_SESSION['user_id'] = $id;
                    $_SESSION['email'] = $values['email'];
                    $_SESSION['name'] = $values['name'];
                    $_SESSION['level'] = 0;

                    $this->redirect('/');

                } else {
                    $this->addVar('message', ['error', 'that email/name is already used by another account']);
                }
            }
        }

        $this->design('signup', 'UserPack', ['forms' => $forms->getForms()]);
    }
}