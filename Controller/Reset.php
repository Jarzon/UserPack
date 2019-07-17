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
    protected $user;
    protected $userModel;

    public function __construct(View $view, array $options,
                                User $user, UserModel $userModel)
    {
        parent::__construct($view, $options);

        $this->user = $user;
        $this->userModel = $userModel;
    }

    protected function sendEmail(string $email, string $name, string $subject, string $message)
    {
        $transport = \Swift_SmtpTransport::newInstance($this->options['smtp_url'], $this->options['smtp_port'], $this->options['smtp_secure'])
            ->setUsername($this->options['email'])
            ->setPassword($this->options['smtp_password']);

        $mailer = \Swift_Mailer::newInstance($transport);

        $body = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom([$this->options['email'] => $this->options['email_name']])
            ->setTo([$email => $name])
            ->setBody($message);

        return $mailer->send($body);
    }

    public function index()
    {
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

            if($user = $this->userModel->getUserByEmail($values['email'])) {
                $this->message('ok', 'We have sent an email to reset your password at your email address.');

                $reset = bin2hex(random_bytes(10)); // 20 chars

                $this->userModel->saveUserSettings(['reset' => $reset], $user->id);

                try {
                    $message = $this->view->fetch('email/reset', 'UserPack', ['user' => $user, 'reset' => $reset]);

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
        if(!$email || !$reset) {
            $this->redirect('/');
        }

        if(!$this->userModel->canResetPassword($email, $reset)) {
            throw new \Exception("Unmatching email/reset token");
        }

        $user = $this->userModel->getUserByEmail($email);

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

                $this->userModel->saveUserSettings([
                    'password' => $this->user->hashPassword($values['password1']),
                    'reset' => ''
                ]);


                $this->message('ok', 'Your password have been changed.');
            }
            catch (ValidationException $e) {
                $this->message('error', $e->getMessage());
            }
        }

        $this->render('reset/setPassword', 'UserPack', ['form' => $form]);
    }
}