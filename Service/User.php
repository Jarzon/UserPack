<?php declare(strict_types=1);
namespace UserPack\Service;

use Prim\View;

class User
{
    public $logged = false;
    public $id = 0;
    protected $view;
    protected $options;

    public function __construct(View $view, array $options)
    {
        $this->view = $view;
        $this->options = $options;

        if(session_status() !== PHP_SESSION_ACTIVE && !headers_sent()) {
            session_start();
        }

        if(isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
            $this->logged = true;
            $this->id = $_SESSION['user_id'];
        }

        $this->populateView();
    }

    function signin(int $id, string $email, string $name, int $status, bool $isAdmin, bool $remember) {
        $_SESSION['user_id'] = $id;
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $name;
        $_SESSION['isAdmin'] = $isAdmin;
        $_SESSION['status'] = $status;

        if ($remember) {
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

    public function populateView()
    {
        $this->view->addVar('logged', $this->logged);
    }

    public function verification()
    {
        if(!$this->logged) {
            header("location: /");
            exit;
        }
    }

    public function hashPassword(string $password) : string
    {
        return password_hash($password, PASSWORD_ARGON2I);
    }
}