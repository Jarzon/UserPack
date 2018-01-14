<?php
namespace UserPack\Controller;

use Prim\Controller;

class User extends Controller
{
    /**
     * @return \UserPack\Model\UserModel
     */
    public function getUserModel() {
        return $this->getModel('UserModel');
    }
}