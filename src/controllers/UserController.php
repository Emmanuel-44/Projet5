<?php
namespace controllers;

class UserController
{
    public function login()
    {
        $twig = TwigFactory::twig();
        echo $twig->render('frontend/loginView.twig');
    }
}