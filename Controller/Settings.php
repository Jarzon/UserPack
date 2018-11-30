<?php
namespace UserPack\Controller;

use Jarzon\Form;

class Settings extends User
{
    public function getForm($settings) {
        $form = new Form($_POST);

        $form
            ->email('mail')
            ->required()

            ->submit();

        $form->updateValues($settings);

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

                $this->message('ok', 'the settings have been saved');
            }
            catch (\Jarzon\ValidationException $e) {
                $this->message('error', $e->getMessage());
            }
        }

        $this->view(['form' => $form]);
    }

    protected function view(array $vars)
    {
        $this->render('settings', 'UserPack', $vars);
    }

    protected function submit(array $values, $user)
    {
        $user->saveUserSettings($values, $this->user->id);
    }
}