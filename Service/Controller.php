<?php
namespace UserPack\Service;

trait Controller
{
    public $logged = false;
    public $user_id = 0;

    function buildSession() {
        if(session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $this->populateView();
    }

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