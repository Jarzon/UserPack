<?php
namespace UserPack\Controller;

use Prim\Controller;

class User extends Controller
{
    protected $user;

    public function __construct(\Prim\View $view, \Prim\Container $container, array $options, \UserPack\Service\User $user)
    {
        parent::__construct($view, $container, $options);

        $this->user = $user;
    }

    /**
     * @return \UserPack\Model\UserModel
     */
    public function getUserModel() {
        return $this->getModel('UserModel');
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