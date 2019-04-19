<?php
use UserPack\Controller\{Admin, Reset, Settings, Signin, Signout, Signup, User};

$injection = function($dic) {
    return [
        $dic->get('userService'),
        $dic->model('UserPack\UserModel')
    ];
};

return [
    Reset::class => $injection,
    Settings::class => $injection,
    Signin::class => $injection,
    Signout::class => $injection,
    Signup::class => $injection,
    User::class => $injection,
    
    Admin::class => function($dic) {
        return [
            $dic->get('userService'),
            $dic->model('UserPack\UserModel'),
            $dic->get('adminService'),
        ];
    },
];