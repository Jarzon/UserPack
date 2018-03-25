<?php
// TODO: implement admin
$this->addGroup('/admin', function($r) {
    $r->both('[/{page:[\d+]?}]', 'UserPack\Admin', 'list');

    $r->both('/edit/{user:[\d+]?}', 'UserPack\Admin', 'showUser');

    $r->get('/delete/{user:[\d+]?}', 'UserPack\Admin', 'deleteUser');
});