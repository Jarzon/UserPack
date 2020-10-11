<?php
namespace UserPack\Controller;

use Prim\AbstractController;
use Prim\View;
use UserPack\Model\UserModel;
use UserPack\Service\User;

class Signout extends AbstractController
{
    private UserModel $userModel;
    private User $user;

    public function __construct(View $view, array $options, User $user, UserModel $userModel)
    {
        parent::__construct($view, $options);

        $this->user = $user;
        $this->userModel = $userModel;
    }

    public function index()
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }

        session_destroy();

        $this->redirection();
    }

    protected function redirection() {
        $this->redirect('/');
    }
}
