<?php
namespace UserPack\Controller;

use Prim\View;
use UserPack\Model\UserModel;

class Admin extends User
{
    protected $admin;

    public function __construct(View $view, array $options, \UserPack\Service\User $user,
                                UserModel $userModel, \PrimPack\Service\Admin $admin)
    {
        parent::__construct($view, $options, $user, $userModel);


        $this->admin = $admin;

        if(!$user->logged || !$this->admin->isAdmin()) {
            header("HTTP/1.1 403 Forbidden");exit;
        }
    }

    public function list(int $page = 1)
    {
        if (isset($_POST['submit_add_user'])) {

            $this->userModel->addUser($_POST['name']);
        }

        $this->render('admin/list', 'UserPack', ['users' => $this->userModel->getAllUsers()]);
    }

    public function show(int $user_id)
    {
        if (isset($_POST['submit_update_user'])) {
            $post = $_POST;

            unset($post['id']);
            unset($post['submit_update_user']);

            $this->userModel->updateUser($post, $_POST['id']);
        }

        $this->render('admin/show', 'UserPack', ['user' => $this->userModel->getUser($user_id)]);
    }
}