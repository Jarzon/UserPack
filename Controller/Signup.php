<?php
namespace UserPack\Controller;

use Jarzon\Form;
use Prim\AbstractController;
use Prim\View;
use UserPack\Model\UserModel;
use UserPack\Service\User;

class Signup extends AbstractController
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

        try {
            $mailer->send($body);
        }
        catch (\Swift_TransportException $e) {
            // huh
        }
    }

    public function index()
    {
        $form = $this->getForm();

        if ($form->submitted()) {
            try {
                $values = $form->validation();

                if($this->submit($values)) {
                    $this->redirection();
                }
            }
            catch (\Jarzon\ValidationException $e) {
                $this->message('error', $e->getMessage());
            }
        }

        $this->render('signup', 'UserPack', ['form' => $form]);
    }

    protected function getForm()
    {
        $form = new Form($_POST);

        $form
            ->email('email')->required()
            ->text('name')->required()
            ->password('password')->required()

            ->submit();

        return $form;
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
