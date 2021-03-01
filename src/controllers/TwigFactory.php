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

        $function = new \Twig\TwigFunction('htmlInsert', function($slug, $id, $class, $value) 
        {
            return "<a href='admin/article/$slug-$id' class='$class'>$value</a>";
        }, ['is_safe' => ['html']]);

        $twig->addFunction($function);

        $function2 = new \Twig\TwigFunction('getPathPost', function($path, $slug, $id) 
        {
            return "'$path/$slug-$id'";
        }, ['is_safe' => ['html']]);

        $twig->addFunction($function2);

        $function3 = new \Twig\TwigFunction('getPath', function($slash, $path) 
        {
            return "'$slash/$path'";
        }, ['is_safe' => ['html']]);

        $twig->addFunction($function3);

        return $twig;
    }
}