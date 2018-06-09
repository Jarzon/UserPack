<?php
namespace UserPack\Controller;

use Jarzon\Form;

class Reset extends User
{
    public function index()
    {
        if($this->user->logged) {
            header("location: /");
            exit;
        }

        $form = new Form($_POST);

        $form
            ->text('name')->required()

            ->submit();

        if ($form->submitted()) {
            try {
                $values = $form->validation();

                if(!$this->exists($values['name'])) {
                    $this->addVar('message', ['ok', 'We have sent an email to reset your password at your email address.']);

                    // Send email
                } else {
                    $this->addVar('message', ['error', 'We don\'t have that email/username']);
                }
            }
            catch (\Exception $e) {
                $this->addVar('message', ['error', $e->getMessage()]);
            }
        }

        $this->design('reset', 'UserPack', ['form' => $form]);
    }
}