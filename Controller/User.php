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

        $forms->email('', 'email', '', '', false, 0, ['required' => true]);
        $forms->text('', 'name', '', '', false, 0, ['required' => true]);
        $forms->password('', 'password', '', '', false, 0, ['required' => true]);

        if (isset($_POST['submit_signup'])) {
            try {
                $params = $forms->verification();
            }
            catch (\Exception $e) {
                $this->addVar('message', ['error', $e->getMessage()]);
            }

            if(isset($params)) {
                list($email, $name, $password) = $params;

                $password = hash('sha512', $email.$password.$name);

                if(!$user->exists($email, $name)) {
                    $id = $user->signup([$email, $name, $password]);

                    $_SESSION['user_id'] = $id;
                    $_SESSION['email'] = $email;
                    $_SESSION['name'] = $name;
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

        $forms->text('', 'name', '', '', false, 0, ['required' => true]);
        $forms->password('', 'password', '', '', false, 0, ['required' => true]);
        $forms->checkbox('remember', '', true, '');

        if (isset($_POST['submit_signin'])) {
            try {
                $params = $forms->verification();
            }
            catch (\Exception $e) {
                $this->addVar('message', ['error', $e->getMessage()]);
            }

            if(isset($params)) {
                list($name, $password, $remember) = $params;

                if($infos = $user->signin([$name])) {
                    $password = hash('sha512', $infos->email.$password.$infos->name);

                    if($password === $infos->password) {
                        $_SESSION['user_id'] = $infos->id;
                        $_SESSION['email'] = $infos->email;
                        $_SESSION['name'] = $infos->name;
                        $_SESSION['level'] = $infos->status;

                        if($remember) {
                            $params = session_get_cookie_params();
                            setcookie(session_name(), $_COOKIE[session_name()], time() + 60*60*24*30*3, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
                        }

                        $this->redirect('/');
                    }
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

        $forms->email('email', 'mail', '', $settings->email);

        if (isset($_POST['submit_settings'])) {
            try {
                $params = $forms->verification();

                $this->addVar('message', ['ok', 'the settings have been saved']);
            }
            catch (\Exception $e) {
                $this->addVar('message', ['error', $e->getMessage()]);
            }

            array_push($params, $this->user_id);

            $user->saveUserSettings(...$params);
        }

        $this->design('settings', 'UserPack', ['forms' => $forms->getForms()]);
    }

}