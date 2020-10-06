<?php
namespace UserPack\Form;

use Jarzon\FormAbstract;

class UserForm extends FormAbstract
{
    function __construct()
    {
        parent::__construct();

        $this->build();
    }

    public function build()
    {
        $this->form
            ->email('email')
            ->required()

            ->submit();
    }

    public function buildAdmin()
    {
        $this->form
            ->number('status')
            ->required();
    }
}
