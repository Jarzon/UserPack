<?php
namespace UserPack\Form;

use Jarzon\FormAbstract;

class SignUpForm extends FormAbstract
{
    public function __construct()
    {
        parent::__construct();

        $this->form
            ->email('email')->required()
            ->text('name')->required()
            ->password('password')->required()

            ->submit();
    }
}
