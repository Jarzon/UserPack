<?php
namespace UserPack\Container;

trait User {
    /**
     * @return \UserPack\Service\User
     */
    public function getUserService()
    {
        $obj = 'userService';

        $this->setDefaultParameter($obj, '\UserPack\Service\User');

        return $this->init($obj, $this->getView());
    }
}