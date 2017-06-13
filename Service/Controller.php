<?php
namespace UserPack\Service;

// TODO: Make it a User Class Service and add it using a container Trait

trait Controller
{
    public $logged = false;
    public $user_id = 0;

    function __construct($view, $container)
    {
        parent::__construct($view, $container);

        if(session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $this->populateView();
        $this->init();
    }

    function init() {}

    function populateView()
    {
        if(isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
            $this->logged = true;
            $this->user_id = $_SESSION['user_id'];
        }

        $this->addVar('logged', $this->logged);
    }

    function verification()
    {
        if(!$this->logged) $this->redirect('/');
    }
}