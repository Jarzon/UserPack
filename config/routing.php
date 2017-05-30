<?php
$this->router->addRoute(['GET', 'POST'], '/signup', ['\UserPack\Controller\User', 'signup']);
$this->router->addRoute(['GET', 'POST'], '/signin', ['\UserPack\Controller\User', 'signin']);
$this->router->addRoute(['GET', 'POST'], '/signout', ['\UserPack\Controller\User', 'signout']);

// TODO:
$this->router->addGroup('/admin', function($r) {
    $r->addRoute(['GET', 'POST'], '[/{page:[\d+]?}]', ['\UserPack\Controller\User', 'list']);

    $r->addRoute(['GET', 'POST'], '/edit/{user:[\d+]?}', ['\UserPack\Controller\User', 'showUser']);

    $r->get('/delete/{user:[\d+]?}', ['\UserPack\Controller\User', 'deleteUser']);
});