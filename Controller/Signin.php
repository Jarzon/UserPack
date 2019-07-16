<?php
namespace UserPack\Controller;

use Jarzon\Form;
use Prim\AbstractController;
use Prim\View;
use UserPack\Model\UserModel;

class Signin extends AbstractController
{
    private $userModel;

    public function __construct(View $view, array $options, UserModel $userModel)
    {
        parent::__construct($view, $options);

        $this->userModel = $userModel;
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

        $this->render('signin', 'UserPack', ['form' => $form]);
    }

    protected function getForm()
    {
        $form = new Form($_POST);

        $form
            ->text('name')->required()
            ->password('password')->required()
            ->checkbox('remember')->value(true)

            ->submit();

        return $form;
    }

    protected function submit(array $values)
    {
        if (empty($values)) return false;

        $infos = $this->userModel->signin([$values['name']]);

        if (!$infos || !password_verify($values['password'], $infos->password)) {
            $this->message('error', 'wrong password or username');
            return false;
        }

        return $this->signin($values, $infos);
    }

    protected function signin($values, $infos) {
        $_SESSION['user_id'] = $infos->id;
        $_SESSION['email'] = $infos->email;
        $_SESSION['name'] = $infos->name;
        $_SESSION['status'] = $infos->status;

        if ($values['remember']) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                $_COOKIE[session_name()],
                time() + 60 * 60 * 24 * 30 * 3,
                $params['path'],
                $params['domain'],
                ($this->options['url_protocol'] === 'https://'? true: false),
                $params['httponly']
            );
        }

        return true;
    }

    protected function redirection() {
        $this->redirect('/');
    }
}