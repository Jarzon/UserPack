<?php
namespace UserPack\Controller;

use Jarzon\Form;
use Prim\AbstractController;
use Prim\View;
use UserPack\Model\UserModel;
use UserPack\Service\User;

class Settings extends AbstractController
{
    protected User $user;
    protected UserForm $userForm;
    protected UserModel $userModel;

    public function __construct(View $view, array $options,
                                User $user, UserForm $userForm, UserModel $userModel)
    {
        parent::__construct($view, $options);

        $this->user = $user;
        $this->userForm = $userForm;
        $this->userModel = $userModel;
    }

    public function index()
    {
        $settings = $this->userModel->getUserSettings();

        $this->userForm->updateValues($settings);

        if ($this->userForm->submitted()) {
            try {
                $values = $this->userForm->validation();

                if($this->submit($values)) {
                    $this->message('ok', 'the settings have been saved');
                }
            }
            catch (\Jarzon\ValidationException $e) {
                $this->message('error', $e->getMessage());
            }
        }

        $this->view(['form' => $this->userForm->getForms()]);
    }

    protected function view(array $vars)
    {
        $this->render('settings', 'UserPack', $vars);
    }

    protected function submit(array $values): bool
    {
        $this->userModel->updateUser($values, null);

        return true;
    }
}
