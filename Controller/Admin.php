<?php
namespace UserPack\Controller;

class Admin extends User
{
    public function list(int $page = 1)
    {
        $user = $this->getUserModel();

        if (isset($_POST['submit_add_user'])) {

            $user->addUser($_POST['name']);
        }

        $this->design('admin/list', 'UserPack', ['users' => $user->getAllUsers()]);
    }

    public function deleteUser(int $user_id)
    {
        $user = $this->getUserModel();

        if (isset($user_id)) {
            $user->deleteUser($user_id);
        }

        $this->redirect('/users');
    }

    public function updateUser(int $user_id)
    {
        $user = $this->getUserModel();

        if (isset($_POST['submit_update_user'])) {
            $user->updateUser($_POST['name'], $_POST['user_id']);
        }

        $this->redirect('/users');
    }

}