<?php
$this->both('/signup', 'UserPack\Signup', 'index');
$this->both('/signin', 'UserPack\Signin', 'index');
$this->both('/signout', 'UserPack\Signout', 'index');
$this->both('/settings', 'UserPack\Settings', 'index');
$this->both('/reset', 'UserPack\Reset', 'index');
$this->both('/reset/{email:.*}/{reset:.*}', 'UserPack\Reset', 'reset');