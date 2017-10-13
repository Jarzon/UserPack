<?php
namespace UserPack\Controller;

use Prim\Controller;

use PrimUtilities\Forms;

class User extends Controller
{
    use \UserPack\Service\Controller;

    public function signup()
    {
        $user = $this->getModel('UserModel');

        $forms = new Forms($_POST);

        $forms->email('', 'email', '', '', false, 0, ['required' => null]);
        $forms->text('', 'name', '', '', false, 0, ['required' => null]);
        $forms->password('', 'password', '', '', false, 0, ['required' => null]);

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
                    $this->addVar('message', ['error', 'that email is already used by another account']);
                }
            }
        }

        $this->design('signup', 'UserPack', ['forms' => $forms->getForms()]);
    }

    public function signin()
    {
        $user = $this->getModel('UserModel');

        $forms = new Forms($_POST);

        $forms->text('', 'name', '', '', false, 0, ['required' => null]);
        $forms->password('', 'password', '', '', false, 0, ['required' => null]);
        $forms->checkbox('remember', '', true, '');

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

    public function signout()
    {
        $this->verification();

        if($this->logged) {
            $_SESSION = [];

            if (ini_get('session.use_cookies')) {
                    $params = session_get_cookie_params();
                    setcookie(session_name(), '', time() - 42000,
                    $params['path'], $params['domain'],
                    $params['secure'], $params['httponly']
                );
            }

            session_destroy();
        }

        $this->redirect('/');
    }

    public function settings()
    {
        $this->verification();

        $user = $this->getModel('UserModel');

        $settings = $user->getUserSettings($this->user_id);

        $forms = new Forms($_POST);

        $forms->email('email', 'mail', '', $settings->email, false, '', ['required' => null]);

        if (isset($_POST['submit_settings'])) {
            try {
                $values = $forms->verification();

                $this->addVar('message', ['ok', 'the settings have been saved']);
            }
            catch (\Exception $e) {
                $this->addVar('message', ['error', $e->getMessage()]);
            }

            if(!empty($values)) {
                $user->saveUserSettings($values, $this->user_id);
            }
        }

        $this->design('settings', 'UserPack', ['forms' => $forms->getForms()]);
    }

}