<?php
namespace UserPack\Controller;

use Prim\Controller;

use PrimUtilities\Forms;

/**
 * Class User
 *
 */
class User extends Controller
{
    use \UserPack\Service\Controller;

    public function signup()
    {
        $user = new $this->getModel('UserModel');

        $forms = new Forms($this->view);

        $forms->forms = [
            ['label' => '', 'name' => 'email', 'value' => '', 'required' => true],
            ['label' => '', 'name' => 'name', 'value' => '', 'required' => true],
            ['label' => '', 'type' => 'password', 'name' => 'password', 'value' => '', 'required' => true],
        ];

        $this->addVar('message', false);

        if (isset($_POST['submit_signup'])) {
            $email = $_POST['email'];
            $name = $_POST['name'];
            $password = $_POST['password'];

            $password = hash('sha512', $email.$password.$name);

            if(!$user->exists([$email, $name])) {
                $id = $user->signup([$email, $name, $password]);

                $_SESSION['user_id'] = $id;
                $_SESSION['email'] = $_POST['email'];
                $_SESSION['name'] = $_POST['name'];
                $_SESSION['level'] = 0;

                $this->redirect('/');

            } else {
                $this->addVar('message', true);
            }
        }

        $this->addVar('forms', $forms);

        $this->design('signup');
    }

    public function signin()
    {
        $user = new UserModel($this->db);

        $this->addVar('message', false);

        if (isset($_POST['submit_signin'])) {
            $name = $_POST['name'];
            $password = $_POST['password'];
            $remember = false;
            if(isset($_POST['remember'])) {
                $remember = true;
            }

            if($infos = $user->signin([$name])) {
                $password = hash('sha512', $infos->email.$password.$infos->name);

                if($password === $infos->password) {
                    $_SESSION['user_id'] = $infos->id;
                    $_SESSION['email'] = $infos->email;
                    $_SESSION['name'] = $infos->name;
                    $_SESSION['level'] = $infos->status;

                    if($remember) {
                        $params = session_get_cookie_params();
                        setcookie(session_name(), $_COOKIE[session_name()], time() + 60*60*24*30*3, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
                    }

                    $this->redirect('/');
                }
            }

            $this->addVar('message', true);
        }

        $this->design('signin');
    }

    public function signout()
    {
        $this->verification();

        $user = new UserModel($this->db);

        if($this->logged) {
            $_SESSION = [];

            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
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

        $forms = new Forms($this->view);

        $forms->email('email', 'mail', '', $settings->email);

        if (isset($_POST['submit_settings'])) {
            try {
                $params = $forms->verification($_POST);

                $this->addVar('message', 'Les configurations on bien été enregistrer.');
            }
            catch (\Exception $e) {
                $this->addVar('error', $e->getMessage());
            }

            array_push($params, $this->user_id);

            $user->saveUserSettings(...$params);
        }

        $this->addVar('forms', $forms);

        $this->design('settings');
    }

}