<?php declare(strict_types=1);
namespace UserPack\Controller;

use Prim\AbstractController;
use Prim\View;
use UserPack\Form\SignInForm;
use UserPack\Model\UserModel;
use UserPack\Service\User;

class Signin extends AbstractController
{
    private User $user;
    private SignInForm $signInForm;
    private UserModel $userModel;

    public function __construct(View $view, array $options,
                                User $user, SignInForm $signInForm, UserModel $userModel)
    {
        parent::__construct($view, $options);

        $this->user = $user;
        $this->signInForm = $signInForm;
        $this->userModel = $userModel;
    }

    public function index(): void
    {
        if ($this->signInForm->submitted()) {
            try {
                $values = $this->signInForm->validation();

                if($this->submit($values)) {
                    $this->redirection();
                }
            }
            catch (\Jarzon\ValidationException $e) {
                $this->message('error', $e->getMessage());
            }
        }

        $this->render('signin', 'UserPack', [
            'form' => $this->signInForm->getForm()
        ]);
    }

    protected function submit(array $values): bool
    {
        if (empty($values)) return false;

        $infos = $this->userModel->getUserByEmail($values['email']);

        if (isset($infos) || !password_verify($values['password'], $infos->password)) {
            $this->message('error', 'wrong password or username');
            return false;
        }

        if (password_needs_rehash($infos->password, $this->options['password']['algo'], $this->options['password']['options'])) {
            $this->userModel->updateUser(['password' => $this->user->hashPassword($values['password'])], $infos->id);
        }

        $values['id'] = $infos->id;
        $values['email'] = $infos->email;
        $values['name'] = $infos->name;
        $values['status'] = $infos->status;
        $values['isAdmin'] = $infos->status >= 99;

        return $this->user->signin($values);
    }

    protected function redirection(): void
    {
        $this->redirect('/');
    }
}
