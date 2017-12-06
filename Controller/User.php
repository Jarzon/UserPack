<?php
namespace UserPack\Controller;

use Prim\Controller;

class User extends Controller
{
    use \UserPack\Service\Controller;

    public function getUserModel() : \UserPack\Model\UserModel {
        return $this->getModel('UserModel');
    }
}