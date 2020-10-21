<?php
namespace UserPack\Controller;

use Jarzon\Form;
use Jarzon\ValidationException;
use Prim\AbstractController;
use Prim\View;
use UserPack\Model\UserModel;
use UserPack\Service\User;

class Reset extends AbstractController
{
    private User $user;
    private UserModel $userModel;

    public function __construct(View $view, array $options,
                                User $user, UserModel $userModel)
    {
        $options += [
            'userpack_pwmin' => 6,
            'userpack_pwmax' => 250
        ];

        parent::__construct($view, $options);

        $this->user = $user;
        $this->userModel = $userModel;
    }

    protected function getEmailForm()
    {
        $form = new Form($_POST);

        $form
            ->email('email')->required()

            ->submit();

        return $form;
    }

    protected function getPasswordForm()
    {
        $form = new Form($_POST);

        $form
            ->password('password1')->min($this->options['userpack_pwmin'])->max($this->options['userpack_pwmax'])->required()
            ->password('password2')->min($this->options['userpack_pwmin'])->max($this->options['userpack_pwmax'])->required()

            ->submit();

        return $form;
    }

    protected function sendEmail(string $email, string $name, string $subject, string $message)
    {
        $transport = (new \Swift_SmtpTransport($this->options['smtp_url'], $this->options['smtp_port'], $this->options['smtp_secure']))
            ->setUsername($this->options['email'])
            ->setPassword($this->options['smtp_password']);

        $mailer = new \Swift_Mailer($transport);

        $body = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom([$this->options['email'] => $this->options['email_name']])
            ->setTo([$email => $name])
            ->setBody($message);

        return $mailer->send($body);
    }

    public function index()
    {
        $form = $this->getEmailForm();

        if ($form->submitted()) {
            $values = [];

            try {
                $values = $form->validation();
            }
            catch (ValidationException $e) {
                $this->message('error', $e->getMessage());
            }

            if($user = $this->userModel->getUserByEmail($values['email'])) {
                $this->message('ok', 'We have sent an email to reset your password at your email address.');

                $reset = bin2hex(random_bytes(10)); // 20 chars

                $this->userModel->updateUser(['reset' => $reset], $user->id);

                try {
                    $message = $this->view->fetch('email/reset', 'UserPack', ['user' => $user, 'reset' => $reset]);

                    if($this->options['environment'] === 'prod') {
                        $this->sendEmail($user->email, $user->name, "{$this->options['project_name']} - Demande de réinitialisation du mot de passe", $message);
                    }
                } catch(\Exception $e) {
                    $this->message('alert', 'password reset email error');
                }
            } else {
                $this->message('error', 'email doesnt exist');
            }
        }

        $this->render('reset/index', 'UserPack', ['form' => $form]);
    }

    public function reset($email = false, $reset = false)
    {
        if(!$email || !$reset) {
            $this->redirect('/');
        }

        if(!$this->userModel->canResetPassword($email, $reset)) {
            throw new \Exception("Unmatching email/reset token");
        }

        $user = $this->userModel->getUserByEmail($email);

        $form = $this->getPasswordForm();

        if ($form->submitted()) {
            try {
                $values = $form->validation();

                if($values['password1'] !== $values['password2']) {
                    throw new ValidationException('password missmatch');
                }

                $this->userModel->updateUser([
                    'password' => $this->user->hashPassword($values['password1']),
                    'reset' => ''
                ], $user->id);

                $this->message('ok', 'password changed');
            }
            catch (ValidationException $e) {
                $this->message('error', $e->getMessage());
            }
        }

        $this->render('reset/setPassword', 'UserPack', ['form' => $form]);
    }
}
