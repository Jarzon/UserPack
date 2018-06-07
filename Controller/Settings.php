<?php
namespace UserPack\Controller;

use Jarzon\Form;

class Settings extends User
{
    protected function getForm($settings) {
        $form = new Form($_POST);

        $form
            ->email('mail')
            ->value($settings->email)
            ->required()

            ->submit();

        return $form;
    }

    public function index()
    {
        $this->user->verification();

        $user = $this->getUserModel();

        $settings = $user->getUserSettings($this->user->id);

        $form = $this->getForm($settings);

        if ($form->submitted()) {
            try {
                $values = $form->validation();

                $this->submit($values, $user);

                $this->addVar('message', ['ok', 'the settings have been saved']);
            }
            catch (\Exception $e) {
                $this->addVar('message', ['error', $e->getMessage()]);
            }
        }

        $this->design('settings', 'UserPack', ['forms' => $form]);
    }

    protected function submit(array $values, $user)
    {
        $user->saveUserSettings($values, $this->user->id);
    }
}