<?php
namespace UserPack\Controller;

use Jarzon\ValidationException;
use Prim\AbstractController;
use Prim\View;

class Signup extends AbstractController
{
    protected object $user;
    protected object $form;
    protected object $userModel;

    public function __construct(View $view, array $options,
                                object $user, object $signUpForm, object $userModel)
    {
        parent::__construct($view, $options);

        $this->user = $user;
        $this->form = $signUpForm;
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

        try {
            $mailer->send($body);
        }
        catch (\Swift_TransportException $e) {
            // huh
        }
    }

    public function index()
    {
        if ($this->form->submitted()) {
            try {
                $values = $this->form->validation();

                if($this->submit($values)) {
                    $this->redirection();
                }
            }
            catch (ValidationException $e) {
                $this->message('error', $e->getMessage());
            }
        }

        $this->render('signup', 'UserPack', [
            'form' => $this->form
        ]);
    }

    protected function submit(array $values = [])
    {
        if(empty($values)) return false;

        if($this->userModel->exists($values['email'], $values['name'])) {
            $this->message('error', 'that email/name is already used by another account');
            return false;
        }

        $values['password'] = $this->user->hashPassword($values['password']);

        $id = $this->userModel->signup($values);

        $this->welcomeEmail($values);

        return $this->user->signin((int)$id, $values['email'], $values['name'], 0, false, false);
    }

    protected function welcomeEmail(array $user)
    {
        $message = $this->view->fetch('email/signup', 'UserPack', ['user' => $user]);

        $this->sendEmail($user['email'], $user['name'], "{$this->options['project_name']} - Signup", $message);
    }

    protected function redirection()
    {
        $this->redirect('/');
    }
}
