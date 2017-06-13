<?php
$this->addRoute(['GET', 'POST'], '/signup', 'UserPack\User', 'signup');
$this->addRoute(['GET', 'POST'], '/signin', 'UserPack\User', 'signin');
$this->addRoute(['GET', 'POST'], '/signout', 'UserPack\User', 'signout');

// TODO: implement admin
$this->addGroup('/admin', function($r) {
    $r->addRoute(['GET', 'POST'], '[/{page:[\d+]?}]', 'UserPack\User', 'list');

    $r->addRoute(['GET', 'POST'], '/edit/{user:[\d+]?}', 'UserPack\User', 'showUser');

    $r->get('/delete/{user:[\d+]?}', 'UserPack\User', 'deleteUser');
});