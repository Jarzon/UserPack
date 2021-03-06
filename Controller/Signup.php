<?php declare(strict_types=1);
namespace UserPack\Controller;

use Prim\AbstractController;
use Prim\View;
use UserPack\Form\SignUpForm;
use UserPack\Model\UserModel;
use UserPack\Service\User;

class Signup extends AbstractController
{
    private User $user;
    private SignUpForm $signUpForm;
    private UserModel $userModel;

    public function __construct(View $view, array $options,
                                User $user, SignUpForm $signUpForm, UserModel $userModel)
    {
        parent::__construct($view, $options);

        $this->user = $user;
        $this->signUpForm = $signUpForm;
        $this->userModel = $userModel;
    }

    protected function sendEmail(string $email, string $name, string $subject, string $message): void
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

        try {
            $mailer->send($body);
        }
        catch (\Swift_TransportException $e) {
            // huh
        }
    }

    public function index(): void
    {
        if ($this->signUpForm->submitted()) {
            try {
                $values = $this->signUpForm->validation();

                if($this->submit($values)) {
                    $this->redirection();
                }
            }
            catch (\Jarzon\ValidationException $e) {
                $this->message('error', $e->getMessage());
            }
        }

        $this->render('signup', 'UserPack', [
            'form' => $this->signUpForm->getForm()
        ]);
    }

    protected function submit(array $values = []): bool
    {
        if(empty($values)) return false;

        if($this->userModel->exists($values['email'])) {
            $this->message('error', 'that email/name is already used by another account');
            return false;
        }

        $values['password'] = $this->user->hashPassword($values['password']);

        $id = $this->userModel->signup($values);

        $values['id'] = $id;

        if($this->options['environment'] === 'prod') {
            $this->welcomeEmail($values);
        }

        return $this->user->signin($values);
    }

    protected function welcomeEmail(array $user): void
    {
        $message = $this->view->fetch('email/signup', 'UserPack', ['user' => $user]);

        $this->sendEmail($user['email'], $values['name'] ?? $user['email'], "{$this->options['project_name']} - Signup", $message);
    }

    protected function redirection(): void
    {
        $this->redirect('/');
    }
}
