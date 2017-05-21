<?php
$this->router->addRoute(['GET', 'POST'], '/signup', ['\Jarzon\UserPack\Controller\User', 'signup']);
$this->router->addRoute(['GET', 'POST'], '/signin', ['\Jarzon\UserPack\Controller\User', 'signin']);
$this->router->addRoute(['GET', 'POST'], '/signout', ['\Jarzon\UserPack\Controller\User', 'signout']);

// TODO:
$this->router->addGroup('/admin', function($r) {
    $r->addRoute(['GET', 'POST'], '[/{page:[\d+]?}]', ['\Jarzon\UserPack\Controller\User', 'list']);

    $r->addRoute(['GET', 'POST'], '/edit/{user:[\d+]?}', ['\Jarzon\UserPack\Controller\User', 'showUser']);

    $r->get('/delete/{user:[\d+]?}', ['\Jarzon\UserPack\Controller\User', 'deleteUser']);
});