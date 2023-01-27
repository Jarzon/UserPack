<?php
use \Prim\Container;
use UserPack\Controller\{Admin, Reset, Settings, Signin, Signout, Signup};

return [
    \UserPack\Service\User::class => function(Container $dic) {
        return [
            $dic->get('view'),
            $dic->options
        ];
    },
    \UserPack\Model\UserModel::class => function(Container $dic) {
        return [
            $dic->model('UserPack\UserEntity')
        ];
    },
    Signup::class => function(Container $dic) {
        return [
            $dic->service('UserPack\User'),
            $dic->form('UserPack\SignUpForm'),
            $dic->model('UserPack\UserModel')
        ];
    },
    Signin::class => function(Container $dic) {
        return [
            $dic->service('UserPack\User'),
            $dic->form('UserPack\SignInForm'),
            $dic->model('UserPack\UserModel')
        ];
    },
    Reset::class => function(Container $dic) {
        $user = $dic->service('UserPack\User');

        if(!$user->logged) {
            header("Location: /");
            exit;
        }

        return [
            $user,
            $dic->model('UserPack\UserModel')
        ];
    },
    Signout::class => function(Container $dic) {
        return [
            $dic->service('UserPack\User'),
            $dic->model('UserPack\UserModel')
        ];
    },
    Settings::class => function(Container $dic) {
        $user = $dic->service('UserPack\User');
        $user->verification();

        return [
            $user,
            $dic->model('UserPack\UserModel')
        ];
    },
    Admin::class => function(Container $dic) {
        $dic->service('UserPack\User')->verification();

        if(!$dic->get('adminService')->isAdmin()) {
            header("HTTP/1.1 403 Forbidden");
            exit;
        }

        return [
            $dic->model('UserPack\UserModel'),
        ];
    },
];
