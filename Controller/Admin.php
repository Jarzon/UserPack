<?php
namespace UserPack\Controller;

use Prim\AbstractController;
use Prim\View;
use UserPack\Model\UserModel;

class Admin extends AbstractController
{
    private object $admin;
    private UserModel $userModel;

    public function __construct(View $view, array $options,
                                UserModel $userModel, object $admin)
    {
        parent::__construct($view, $options);

        $this->userModel = $userModel;
        $this->admin = $admin;
    }

    public function list(int $page = 1)
    {
        if (isset($_POST['submit_add_user'])) {

            $this->userModel->signUp([$_POST['name']]);
        }

        $this->render('admin/list', 'UserPack', [
            'users' => $this->userModel->getAllUsers()
        ]);
    }

    public function show(int $user_id)
    {
        if (isset($_POST['submit_update_user'])) {
            $post = $_POST;

            unset($post['id']);
            unset($post['submit_update_user']);

            $this->userModel->updateUser($post, $_POST['id']);
        }

        $this->render('admin/show', 'UserPack', [
            'user' => $this->userModel->getUser($user_id)]
        );
    }
}
