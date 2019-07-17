<?php
namespace UserPack\Controller;

use Jarzon\Form;
use Prim\AbstractController;
use Prim\View;
use UserPack\Model\UserModel;

class Settings extends AbstractController
{
    protected $userModel;

    public function __construct(View $view, array $options, UserModel $userModel)
    {
        parent::__construct($view, $options);

        $this->userModel = $userModel;
    }

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
        $settings = $this->userModel->getUserSettings();

        $form = $this->getForm($settings);

        if ($form->submitted()) {
            try {
                $values = $form->validation();

                $this->submit($values);

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

    protected function submit(array $values)
    {
        $this->userModel->saveUserSettings($values);
    }
}