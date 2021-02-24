<?php
require '../vendor/autoload.php';

use controllers\PostController;
use controllers\UserController;

$router = new AltoRouter();
$router->setBasePath('/projet5');

// map homepage
$router->map('GET', '/', function() {
    $postController = new PostController();
    $postController->index();
});

// map Blog
$router->map('GET', '/blog', function() {
    $postController = new PostController();
    $postController->blog();
});

// map single Post
$router->map('GET', '/blog/[*:slug]-[i:id]', function() {
    $postController = new PostController();
    $postController->single();
});

// map login
$router->map('GET', '/login', function() {
    $userController = new UserController();
    $userController->login();
});

// map Home admin
$router->map('GET', '/admin', function() {
    $postController = new PostController();
    $postController->adminIndex();
});

// map create Post
$router->map('GET', '/admin/ajouter', function() {
    $postController = new PostController();
    $postController->create();
});

// map read Post
$router->map('GET', '/admin/article/[*:slug]-[i:id]', function() {
    $postController = new PostController();
    $postController->read();
});

// map update Post
$router->map('GET', '/admin/modifier/[*:slug]-[i:id]', function() {
    $postController = new PostController();
    $postController->update();
});

// match curent request url
$match = $router->match();

// call closure or throw 404 status
if(is_array($match) && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
}