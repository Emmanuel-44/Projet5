<?php
namespace controllers;

use core\Controller;

class ErrorController extends Controller
{
    /**
     * Error page controller
     *
     */
    public function error404()
    {
        $this->render('error404View.twig');
    }
}