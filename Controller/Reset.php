<?php
namespace UserPack\Controller;

use Jarzon\Form;

class Reset extends User
{
    public function index()
    {
        if($this->user->logged) {
            header("location: /");
            exit;
        }

        $userModel = $this->getUserModel();

        $form = new Form($_POST);

        $form
            ->email('email')->required()

            ->submit();


        if ($form->submitted()) {
            try {
                $values = $form->validation();

                if($user = $userModel->getUserByEmail($values['email'])) {
                    $this->addVar('message', ['ok', 'We have sent an email to reset your password at your email address.']);

                    $reset = bin2hex(random_bytes(10)); // 20 chars

                    $userModel->saveUserSettings(['reset' => $reset], $user->id);

                    try {
                        $this->view->design('email/reset', 'UserPack', ['user' => $values]);

                        $this->sendEmail($user->email, $user->name, 'Libellum - Password reset', $this->view->section('default'));
                    } catch(\Exception $e) {
                        $this->addVar('message', ['alert', 'Something went wrong, we couldn\'t send the email.']);
                    }
                } else {
                    $this->addVar('message', ['error', 'We don\'t have that email/username']);
                }
            }
            catch (\Exception $e) {
                $this->addVar('message', ['error', $e->getMessage()]);
            }
        }

        $this->design('reset/index', 'UserPack', ['form' => $form]);
    }

    public function reset($email = false, $reset = false)
    {
        if($this->user->logged || !$email || !$reset) {
            header("location: /");
            exit;
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
                    throw new \Exception('The two passwords doesn\'t match.');
                }

                $userModel->saveUserSettings([
                    'password' => $this->user->hashPassword($values['password1']),
                    'reset' => ''
                ], $user->id);

                $this->addVar('message', ['ok', 'Your password have been changed.']);
            }
            catch (\Exception $e) {
                $this->addVar('message', ['error', $e->getMessage()]);
            }
        }

        $this->design('reset/setPassword', 'UserPack', ['form' => $form]);
    }
}