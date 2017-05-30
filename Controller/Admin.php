<?php
namespace UserPack\Controller;

use Prim\Controller;

use UserPack\Model\UserModel;

class Admin extends Controller
{
    public function list(int $page = 1)
    {
        $user = new UserModel($this->db);

        if (isset($_POST['submit_add_user'])) {

            $user->addUser($_POST['name']);
        }

        $this->addVar('users', $user->getAllUsers());

        $this->design('admin/list');
    }

    public function deleteUser(int $user_id)
    {
        $user = new UserModel($this->db);

        if (isset($user_id)) {
            $user->deleteUser($user_id);
        }

        $this->redirect('/users');
    }

    public function updateUser(int $user_id)
    {
        $user = new UserModel($this->db);

        if (isset($_POST['submit_update_user'])) {
            $user->updateUser($_POST['name'], $_POST['user_id']);
        }

        $this->redirect('/users');
    }

}