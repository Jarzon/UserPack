<?php
namespace UserPack\Service;

class User
{
    public $logged = false;
    public $id = 0;
    protected $view;

    function __construct($view)
    {
        $this->view = $view;

        if(session_status() !== PHP_SESSION_ACTIVE && !headers_sent()) {
            session_start();
        }

        if(isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
            $this->logged = true;
            $this->id = $_SESSION['user_id'];
        }

        $this->populateView();
    }

    function populateView()
    {
        $this->view->addVar('logged', $this->logged);
    }

    function verification()
    {
        if(!$this->logged) {
            header("location: /");
            exit;
        }
    }

    function hashPassword(string $password) : string
    {
        return password_hash($password, PASSWORD_ARGON2I);
    }
}