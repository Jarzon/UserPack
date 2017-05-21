<?php
namespace Jarzon\UserPack\Service;

trait Controller
{
    public $logged = false;
    public $user_id = 0;

    function __construct($view)
    {
        parent::__construct($view);

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