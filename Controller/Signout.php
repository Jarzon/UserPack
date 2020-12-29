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
        $this->user->signout();

        $this->redirection();
    }

    protected function redirection() {
        $this->redirect('/');
    }
}
