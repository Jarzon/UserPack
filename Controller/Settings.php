<?php
namespace UserPack\Controller;

use Jarzon\Forms;

class Settings extends User
{
    protected function getForms($settings) {
        $forms = new Forms($_POST);

        $forms
            ->email('mail')
            ->label('email')
            ->value($settings->email)
            ->required();

        return $forms;
    }

    public function index()
    {
        $this->user->verification();

        $user = $this->getUserModel();

        $settings = $user->getUserSettings($this->user->id);

        $forms = $this->getForms($settings);

        if (isset($_POST['submit_settings'])) {
            try {
                $values = $forms->verification();

                $this->addVar('message', ['ok', 'the settings have been saved']);
            }
            catch (\Exception $e) {
                $this->addVar('message', ['error', $e->getMessage()]);
            }

            if(!empty($values)) $this->submit($values, $user);
        }

        $this->design('settings', 'UserPack', ['forms' => $forms->getForms()]);
    }

    protected function submit(array $values, $user)
    {
        $user->saveUserSettings($values, $this->user->id);
    }
}