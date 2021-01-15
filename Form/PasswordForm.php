<?php declare(strict_types=1);

namespace UserPack\Form;

use Jarzon\FormAbstract;

class PasswordForm extends FormAbstract
{
    public function __construct()
    {
        parent::__construct();

        $this->build();
    }

    public function build(): void
    {
        $this->form
            ->password('old_password')->required()->min(8)->max(300)->autocomplete('current-password')
            ->password('new_password')->required()->min(8)->max(300)->autocomplete('new-password')
            ->password('new_password_verification')->required()->min(8)->max(300)->autocomplete('new-password')

            ->submit();
    }
}
