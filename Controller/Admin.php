<?php
namespace Jarzon\UserPack\Controller;

use Prim\Controller;

use Jarzon\UserPack\Model\UserModel;

/**
 * Class User
 *
 */
class Admin extends Controller
{
    /**
     * PAGE: index
     */
    public function list(int $page = 1)
    {
        $user = new UserModel($this->db);

        if (isset($_POST['submit_add_user'])) {

            $user->addUser($_POST['name']);
        }

        $this->addVar('users', $user->getAllUsers());

        $this->design('admin/list');
    }

    /**
     * ACTION: deleteUser
     */
    public function deleteUser(int $user_id)
    {
        $user = new UserModel($this->db);

        if (isset($user_id)) {
            $user->deleteUser($user_id);
        }

        $this->redirect('/users');
    }

    /**
     * ACTION: updateUser
     */
    public function updateUser(int $user_id)
    {
        $user = new UserModel($this->db);

        if (isset($_POST['submit_update_user'])) {
            $user->updateUser($_POST['name'], $_POST['user_id']);
        }

        $this->redirect('/users');
    }

}