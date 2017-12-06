<?php
namespace UserPack\Controller;

use PrimUtilities\Forms;

class Settings extends User
{
    public function getForms($settings) {
        $forms = new Forms($_POST);

        $forms
            ->email('mail')
            ->label('email')
            ->value($settings->email)
            ->required();

        return $forms;
    }

    public function settings()
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

            if(!empty($values)) {
                $user->saveUserSettings($values, $this->user_id);
            }
        }

        $this->design('settings', 'UserPack', ['forms' => $forms->getForms()]);
    }

}