<?php
use \Prim\Container;
use UserPack\Controller\{Admin, Reset, Settings, Signin, Signout, Signup};

return [
    \UserPack\Model\UserModel::class => function(Container $dic) {
        return [
            $dic->model('UserPack\UserEntity')
        ];
    },
    Signup::class => function(Container $dic) {
        return [
            $dic->get('userService'),
            $dic->form('UserPack\SignUpForm'),
            $dic->model('UserPack\UserModel')
        ];
    },
    Signin::class => function(Container $dic) {
        return [
            $dic->get('userService'),
            $dic->form('UserPack\SignInForm'),
            $dic->model('UserPack\UserModel')
        ];
    },
    Reset::class => function(Container $dic) {
        $user = $dic->get('userService');

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
        $dic->get('userService')->verification();

        return [
            $dic->get('userService'),
            $dic->model('UserPack\UserModel')
        ];
    },
    Settings::class => function(Container $dic) {
        $user = $dic->get('userService');
        $user->verification();

        return [
            $user,
            $dic->model('UserPack\UserModel')
        ];
    },
    Admin::class => function(Container $dic) {
        $dic->get('userService')->verification();

        if(!$dic->get('adminService')->isAdmin()) {
            header("HTTP/1.1 403 Forbidden");
            exit;
        }

        return [
            $dic->model('UserPack\UserModel'),
        ];
    },
];
