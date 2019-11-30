<?php
namespace UserPack\Controller;

use Jarzon\FormAbstract;

class UserForm extends FormAbstract
{
    public function __construct()
    {
        parent::__construct();

        $this->form
            ->email('mail')
            ->required()

            ->submit();
    }
}
