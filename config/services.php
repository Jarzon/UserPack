<?php
use UserPack\Controller\{Admin, Reset, Settings, Signin, Signout, Signup};

$basicInjection = function($dic) {
    return [
        $dic->get('userService'),
        $dic->model('UserPack\UserModel')
    ];
};

$injectionPlusVerification = function($dic) {
    $dic->get('userService')->verification();

    return [
        $dic->model('UserPack\UserModel')
    ];
};

return [
    Signup::class => $basicInjection,
    Signin::class => $basicInjection,
    Reset::class => function($dic) {
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
    Signout::class => function($dic) {
        $dic->get('userService')->verification();

        return [
            $dic->model('UserPack\UserModel')
        ];
    },
    Settings::class => function($dic) {
        $user = $dic->get('userService');
        $user->verification();

        return [
            $user,
            $dic->model('UserPack\UserModel')
        ];
    },
    Admin::class => function($dic) {
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