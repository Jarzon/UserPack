<?php
$this->both('/signup', 'UserPack\User', 'signup');
$this->both('/signin', 'UserPack\User', 'signin');
$this->both('/signout', 'UserPack\User', 'signout');

// TODO: implement admin
$this->addGroup('/admin', function($r) {
    $r->both('[/{page:[\d+]?}]', 'UserPack\User', 'list');

    $r->both('/edit/{user:[\d+]?}', 'UserPack\User', 'showUser');

    $r->get('/delete/{user:[\d+]?}', 'UserPack\User', 'deleteUser');
});