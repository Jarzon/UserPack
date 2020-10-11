<?php declare(strict_types=1);
namespace UserPack\Service;

use Prim\View;

class User
{
    public bool $logged = false;
    public int $id = 0;
    protected View $view;
    protected array $options;

    public function __construct(View $view, array $options)
    {
        $this->view = $view;
        $this->options = $options += [
            'url_protocol' => 'http',
            'password' => [
                'algo' => PASSWORD_DEFAULT,
                'options' => [
                    'cost' => 10
                ]
            ]
        ];
    }

    public function init() {
        if(session_status() !== PHP_SESSION_ACTIVE && !headers_sent()) {
            session_start();
        }

        if(isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
            $this->populateLoggedInUser();
        }

        $this->populateView();
    }

    public function signin(array $values) {
        $_SESSION['user_id'] = $values['id'];
        $_SESSION['email'] = $values['email'];
        $_SESSION['name'] = $values['name'] ?? $values['email'];
        $_SESSION['isAdmin'] = $values['isAdmin'] ?? false;
        $_SESSION['status'] = $values['status'] ?? 0;

        if (isset($values['remember']) && $values['remember']) {
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

    public function signout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }

        session_destroy();
    }

    public function verification()
    {
        if(!$this->logged) {
            header('location: /');
            exit;
        }
    }

    public function hashPassword(string $password) : string
    {
        if(!$pw = password_hash($password, $this->options['password']['algo'], $this->options['password']['options'])) {
            throw new \Exception('Error while trying to hash password');
        }

        return $pw;
    }

    protected function populateLoggedInUser()
    {
        $this->logged = true;
        $this->id = $_SESSION['user_id'];
    }

    protected function populateView()
    {
        $this->view->addVar('logged', $this->logged);
    }
}
