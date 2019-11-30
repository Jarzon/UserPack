<?php
namespace UserPack\Form;

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
