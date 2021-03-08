<?php
namespace controllers;

/**
 * User controller
 */
class UserController
{
    /**
     * Login user controller
     *
     * @return void
     */
    public function login()
    {
        $twig = TwigFactory::twig();
        echo $twig->render('frontend/loginView.twig');
    }
}
