<?php
namespace UserPack\Controller;

class Admin extends User
{
    /** @var $admin \PrimPack\Service\Admin */
    protected $admin;

    public function build() {
        if(!$this->admin->isAdmin()) {
            header("HTTP/1.1 403 Forbidden");exit;
        }
    }

    public function list(int $page = 1)
    {
        $user = $this->getUserModel();

        if (isset($_POST['submit_add_user'])) {

            $user->addUser($_POST['name']);
        }

        $this->render('admin/list', 'UserPack', ['users' => $user->getAllUsers()]);
    }

    public function show(int $user_id)
    {
        $user = $this->getUserModel();

        if (isset($_POST['submit_update_user'])) {
            $user->updateUser($_POST['name'], $_POST['user_id']);
        }

        $this->render('admin/show', 'UserPack', ['user' => $user->getUser($user_id)]);
    }
}