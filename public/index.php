<?php
require '../vendor/autoload.php';

$router = new AltoRouter();
$router->setBasePath('/projet5');

$router->map('GET', '/', function() {
    echo "accueil";
});

// match curent request url
$match = $router->match();

// call closure or throw 404 status
if(is_array($match) && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
}