<?php
namespace UserPack\Controller;

use PrimUtilities\Forms;

class Settings extends User
{
    public function index()
    {
        $this->verification();

        $user = $this->getUserModel();

        $settings = $user->getUserSettings($this->user_id);

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

    protected function getForms($settings) {
        $forms = new Forms($_POST);

        $forms
            ->email('mail')
            ->label('email')
            ->value($settings->email)
            ->required();

        return $forms;
    }

    protected function submit(array $values, $user)
    {
        $user->saveUserSettings($values, $this->user_id);
    }
}