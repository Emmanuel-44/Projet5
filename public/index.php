<?php
session_start();
require '../vendor/autoload.php';

use controllers\PostController;
use controllers\UserController;
use controllers\CommentController;

$router = new AltoRouter();
$router->setBasePath('/projet5');

$postController = new PostController();
$userController = new UserController();
$commentController = new CommentController();

// routes
$router->map('GET', '/', [$postController, 'index']);
$router->map('GET', '/blog', [$postController, 'blog']);
$router->map('GET', '/blog/[*:slug]-[i:id]', [$postController, 'single']);
$router->map('GET', '/login', [$userController, 'login']);
$router->map('POST', '/login', [$userController, 'login']);
$router->map('GET', '/logout', [$userController, 'logout']);
$router->map('GET', '/admin', [$postController, 'adminIndex']);
$router->map('GET|POST', '/admin/ajouter', [$postController, 'create']);
$router->map('GET', '/admin/article/[*:slug]-[i:id]', [$postController, 'read']);
$router->map(
    'GET|POST', '/admin/modifier/[*:slug]-[i:id]', [$postController, 'update']
);
$router->map('GET', '/admin/supprimer/[*:slug]-[i:id]', [$postController, 'delete']);
$router->map('POST', '/blog/[*:slug]-[i:id]', [$commentController, 'add']);
$router->map(
    'GET', '/admin/supprimer/[*:slug]-[i:postId]/[i:commentId]', 
    [$commentController, 'delete']
);
$router->map(
    'GET', '/admin/valider/[*:slug]-[i:postId]/[i:commentId]', 
    [$commentController, 'confirm']
);
$router->map('GET', '/creer-un-compte', [$userController, 'create']);
$router->map('POST', '/creer-un-compte', [$userController, 'create']);

// map update Post
$router->map(
    'GET|POST', '/admin/modifier/[*:slug]-[i:id]', function () {
        $postController = new PostController();
        $postController->update();
    }
);

// match curent request url
$match = $router->match();

// call closure or throw 404 status
if (is_array($match) && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    echo '404 Not Found';
}
