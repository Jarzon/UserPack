<?php
namespace UserPack\Controller;

use Jarzon\Form;

class Settings extends User
{
    protected function getForm() {
        $form = new Form($_POST);

        $form
            ->email('mail')
            ->required()

            ->submit();

        return $form;
    }

    public function index()
    {
        $this->user->verification();

        $user = $this->getUserModel();

        $settings = $user->getUserSettings($this->user->id);

        $form = $this->getForm();

        $form->updateValues($settings);

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

        $this->design('settings', 'UserPack', ['form' => $form]);
    }

    protected function submit(array $values, $user)
    {
        $user->saveUserSettings($values, $this->user->id);
    }
}