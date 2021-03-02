<?php
require '../vendor/autoload.php';

use controllers\PostController;
use controllers\UserController;

$router = new AltoRouter();
$router->setBasePath('/projet5');

$postController = new PostController();
$userController = new UserController();

// routes
$router->map('GET', '/', [$postController, 'index']);
$router->map('GET', '/blog', [$postController, 'blog']);
$router->map('GET', '/blog/[*:slug]-[i:id]', [$postController, 'single']);
$router->map('GET', '/login', [$userController, 'login']);
$router->map('GET', '/admin', [$postController, 'adminIndex']);
$router->map('GET|POST', '/admin/ajouter', [$postController, 'create']);
$router->map('GET', '/admin/article/[*:slug]-[i:id]', [$postController, 'read']);
$router->map('GET|POST', '/admin/modifier/[*:slug]-[i:id]', [$postController, 'update']);
$router->map('GET', '/admin/supprimer/[*:slug]-[i:id]', [$postController, 'delete']);

// map update Post
$router->map('GET|POST', '/admin/modifier/[*:slug]-[i:id]', function() {
    $postController = new PostController();
    $postController->update();
});

// match curent request url
$match = $router->match();

// call closure or throw 404 status
if (is_array($match) && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
}
else {
    echo '404 Not Found';
}