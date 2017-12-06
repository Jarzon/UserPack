<?php
$this->both('/signup', 'UserPack\Signup', 'signup');
$this->both('/signin', 'UserPack\Signin', 'signin');
$this->both('/signout', 'UserPack\Signout', 'signout');

// TODO: implement admin
$this->addGroup('/admin', function($r) {
    $r->both('[/{page:[\d+]?}]', 'UserPack\Admin', 'list');

    $r->both('/edit/{user:[\d+]?}', 'UserPack\Admin', 'showUser');

    $r->get('/delete/{user:[\d+]?}', 'UserPack\Admin', 'deleteUser');
});