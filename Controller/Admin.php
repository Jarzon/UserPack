<?php
namespace UserPack\Controller;

use Jarzon\Localization;
use UserPack\Model\UserModel;

class Admin extends User
{
    protected $admin;

    public function __construct(\Prim\View $view, array $options, \UserPack\Service\User $user, Localization $local, UserModel $userModel, \PrimPack\Service\Admin $admin)
    {
        parent::__construct($view, $options, $user, $local, $userModel);


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