<?php
namespace UserPack\Controller;

use Jarzon\Form;
use Jarzon\ValidationException;

class Reset extends User
{
    public function index()
    {
        if($this->user->logged) {
            $this->redirect('/');
        }

        $userModel = $this->getUserModel();

        $form = new Form($_POST);

        $form
            ->email('email')->required()

            ->submit();


        if ($form->submitted()) {
            try {
                $values = $form->validation();
            }
            catch (ValidationException $e) {
                $this->message('error', $e->getMessage());
            }

            if($user = $userModel->getUserByEmail($values['email'])) {
                $this->message('ok', 'We have sent an email to reset your password at your email address.');

                $reset = bin2hex(random_bytes(10)); // 20 chars

                $userModel->saveUserSettings(['reset' => $reset], $user->id);

                try {
                    $message = $this->view->fetch('email/reset', 'UserPack', ['user' => $user]);

                    $this->sendEmail($user->email, $user->name, "{$this->options['project_name']} - Password reset", $message);
                } catch(\Exception $e) {
                    $this->message('alert', 'Something went wrong, we couldn\'t send the email.');
                }
            } else {
                $this->message('error', 'We don\'t have that email/username');
            }
        }

        $this->render('reset/index', 'UserPack', ['form' => $form]);
    }

    public function reset($email = false, $reset = false)
    {
        if($this->user->logged || !$email || !$reset) {
            $this->redirect('/');
        }

        $userModel = $this->getUserModel();

        if(!$userModel->canResetPassword($email, $reset)) {
            throw new \Exception("Unmatching email/reset token");
        }

        $user = $userModel->getUserByEmail($email);

        $form = new Form($_POST);

        $form
            ->password('password1')->required()
            ->password('password2')->required()

            ->submit();


        if ($form->submitted()) {
            try {
                $values = $form->validation();

                if($values['password1'] !== $values['password2']) {
                    throw new ValidationException('The two passwords doesn\'t match.');
                }

                $userModel->saveUserSettings([
                    'password' => $this->user->hashPassword($values['password1']),
                    'reset' => ''
                ], $user->id);


                $this->message('ok', 'Your password have been changed.');
            }
            catch (ValidationException $e) {
                $this->message('error', $e->getMessage());
            }
        }

        $this->render('reset/setPassword', 'UserPack', ['form' => $form]);
    }
}