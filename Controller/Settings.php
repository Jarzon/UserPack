<?php declare(strict_types=1);
namespace UserPack\Controller;

use Prim\AbstractController;
use Prim\View;
use UserPack\Form\UserForm;
use UserPack\Model\UserModel;
use UserPack\Service\User;

class Settings extends AbstractController
{
    private User $user;
    private UserForm $userForm;
    private UserModel $userModel;

    public function __construct(View $view, array $options,
                                User $user = null, UserForm $userForm = null, UserModel $userModel = null)
    {
        parent::__construct($view, $options);

        if($user) $this->user = $user;
        if($userForm) $this->userForm = $userForm;
        if($userModel) $this->userModel = $userModel;
    }

    public function index(): void
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

        $this->view(['form' => $this->userForm->getForm()]);
    }

    protected function view(array $vars): void
    {
        $this->render('settings', 'UserPack', $vars);
    }

    protected function submit(array $values): bool
    {
        $this->userModel->updateUser($values, null);

        return true;
    }
}
