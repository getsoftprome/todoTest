<?php

namespace Core;

use Model\User\User;

abstract class Controller
{
    protected $view;
    protected $user;
    protected $configPage;

    public function __construct()
    {
        $userModel = new User();
        $this->view = new View();
        $this->configPage = [];
        $this->user = $userModel->getUser($_COOKIE['hash']);
    }
}