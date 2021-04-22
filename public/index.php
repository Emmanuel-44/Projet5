<?php
session_start();
require '../vendor/autoload.php';

use core\Download;
use controllers\PostController;
use controllers\UserController;
use controllers\ErrorController;
use controllers\CommentController;
use controllers\ContactController;

$router = new AltoRouter();
$router->setBasePath('/projet5');

$postController = new PostController();
$contactController = new ContactController();
$userController = new UserController();
$commentController = new CommentController();
$errorController = new ErrorController();
$download = new Download();

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
$router->map('POST', '/admin/supprimer/[*:slug]-[i:id]', [$postController, 'delete']);
$router->map('POST', '/blog/[*:slug]-[i:id]', [$commentController, 'add']);
$router->map(
    'POST', '/admin/supprimer/[*:slug]-[i:postId]/[i:commentId]', 
    [$commentController, 'delete']
);
$router->map(
    'POST', '/admin/valider/[*:slug]-[i:postId]/[i:commentId]', 
    [$commentController, 'confirm']
);
$router->map('GET', '/creer-un-compte', [$userController, 'create']);
$router->map('POST', '/creer-un-compte', [$userController, 'create']);
$router->map('POST', '/admin/utilisateur/[i:userId]', [$userController, 'update']);
$router->map('POST', '/admin/utilisateur/remove/[i:userId]', [$userController, 'remove']);
$router->map(
    'GET|POST', '/admin/modifier/[*:slug]-[i:id]', function () {
        $postController = new PostController();
        $postController->update();
    }
);
$router->map('POST', '/contact', [$contactController, 'emailSend']);
$router->map('GET', '/admin/utilisateurs', [$userController, 'usersList']);
$router->map('GET', '/cv', [$download, 'downloadCv']);

// match curent request url
$match = $router->match();

// call closure or throw 404 status
if (is_array($match) && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    $errorController->error404();
}
