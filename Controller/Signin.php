<?php
namespace UserPack\Controller;

use Jarzon\Form;

class Signin extends User
{
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
            catch (\Exception $e) {
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

        $user = $this->getUserModel();

        $infos = $user->signin([$values['name']]);

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
            setcookie(session_name(), $_COOKIE[session_name()], time() + 60 * 60 * 24 * 30 * 3, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        return true;
    }

    protected function redirection() {
        $this->redirect('/');
    }
}