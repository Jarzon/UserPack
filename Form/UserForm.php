<?php
namespace UserPack\Form;

use Jarzon\FormAbstract;

class UserForm extends FormAbstract
{
    public function build()
    {
        $this->form
            ->email('email')
            ->required()

            ->submit();
    }
}
