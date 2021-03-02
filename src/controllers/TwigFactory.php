<?php
namespace controllers;

class TwigFactory
{
    public static function twig()
    {
        $loader = new \Twig\Loader\FilesystemLoader('../src/views');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
            'debug' => true
        ]);
        $twig->addExtension(new \Twig\Extension\DebugExtension());

        return $twig;
    }
}