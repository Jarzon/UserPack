<?php
namespace UserPack\Controller;

use PrimUtilities\Forms;

class Signin extends User
{
    public function getForms() {
        $forms = new Forms($_POST);

        $forms->text('name')->required();
        $forms->password('password')->required();
        $forms->checkbox('remember')
            ->label('remember me')
            ->value(true);

        return $forms;
    }

    public function signin()
    {
        $user = $this->getUserModel();

        $forms = $this->getForms();

        if (isset($_POST['submit_signin'])) {
            try {
                $values = $forms->verification();
            }
            catch (\Exception $e) {
                $this->addVar('message', ['error', $e->getMessage()]);
            }

            if(!empty($values)) {
                if($infos = $user->signin([$values['name']])) {
                    $password = hash('sha512', $infos->email.$values['password'].$infos->name);

                    if($password === $infos->password) {
                        $_SESSION['user_id'] = $infos->id;
                        $_SESSION['email'] = $infos->email;
                        $_SESSION['name'] = $infos->name;
                        $_SESSION['level'] = $infos->status;

                        if($values['remember']) {
                            $params = session_get_cookie_params();
                            setcookie(session_name(), $_COOKIE[session_name()], time() + 60*60*24*30*3, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
                        }

                        $this->redirect('/');
                    } else {
                        $this->addVar('message', ['error', 'wrong password or username']);
                    }
                } else {
                    $this->addVar('message', ['error', 'wrong password or username']);
                }
            }
        }

        $this->design('signin', 'UserPack', ['forms' => $forms->getForms()]);
    }
}