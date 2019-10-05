<?php
namespace UserPack\Controller;

use Jarzon\FormAbstract;

class UserForm extends FormAbstract
{
    public function __construct()
    {
        parent::__construct();

        $this->form
            ->email('email')->required()
            ->password('password')->required()
            ->checkbox('remember')->value(true)->selected()

            ->submit();
    }
}
