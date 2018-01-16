<?php
namespace UserPack\Controller;

class Signout extends User
{
    public function index()
    {
        $this->user->verification();

        if($this->user->logged) {
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

        $this->redirection();
    }

    protected function redirection() {
        $this->redirect('/');
    }
}