<?php
/** @var $this \Prim\Container */

$this->register('userService', \UserPack\Service\User::class, [$this->get('view'), $this->options]);