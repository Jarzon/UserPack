<?php
$this->both('/signup', 'UserPack\Signup', 'index');
$this->both('/signin', 'UserPack\Signin', 'index');
$this->both('/signout', 'UserPack\Signout', 'index');
$this->both('/settings', 'UserPack\Settings', 'index');

// TODO: implement admin
$this->addGroup('/admin', function($r) {
    $r->both('[/{page:[\d+]?}]', 'UserPack\Admin', 'list');

    $r->both('/edit/{user:[\d+]?}', 'UserPack\Admin', 'showUser');

    $r->get('/delete/{user:[\d+]?}', 'UserPack\Admin', 'deleteUser');
});