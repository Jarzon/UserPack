<?php
$this->addGroup('/admin/users/', function($r) {
    $r->both('{user:\d*}', 'UserPack\Admin', 'list');

    $r->both('edit/{user:\d+}', 'UserPack\Admin', 'show');
});
