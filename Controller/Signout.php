<?php declare(strict_types=1);
namespace UserPack\Controller;

use Prim\AbstractController;
use Prim\View;
use UserPack\Service\User;

class Signout extends AbstractController
{
    private User $user;

    public function __construct(
        View $view,
        array $options,
        User $user
    ) {
        parent::__construct($view, $options);

        $this->user = $user;
    }

    public function index(): void
    {
        $this->user->signout();

        $this->redirection();
    }

    protected function redirection(): void
    {
        $this->redirect('/');
    }
}
