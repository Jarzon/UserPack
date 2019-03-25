<?php
namespace UserPack\Controller;

use Prim\Controller;
use UserPack\Model\UserModel;

class User extends Controller
{
    protected $user;
    protected $userModel;

    public function __construct(\Prim\View $view, \Prim\Container $container, array $options, \UserPack\Service\User $user, UserModel $userModel)
    {
        parent::__construct($view, $container, $options);

        $this->user = $user;
        $this->userModel = $userModel;
    }

    protected function sendEmail(string $email, string $name, string $subject, string $message) {
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
}