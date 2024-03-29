<?php
namespace core;

/**
 * Twig
 */
class Twig
{
    /**
     * Twig loader
     *
     * @return object
     */
    public static function twig(): object
    {
        $loader = new \Twig\Loader\FilesystemLoader('../src/views');
        $twig = new \Twig\Environment(
            $loader, [
            'cache' => false,
            'debug' => true
            ]
        );
        $twig->addExtension(new \Twig\Extension\DebugExtension());
        $twig->addExtension(new Globals);

        return $twig;
    }
}
