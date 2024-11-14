<?php

use Backend\Router;
use Backend\HomeController;
use Backend\UserController;
use Backend\TweetController;
use Backend\Middleware;

$router = new Router();

// Orientando as rotas
$router->add('/', HomeController::class, 'showHome');
$router->add('/profile', UserController::class, 'showProfile', 'GET', Middleware::class);
$router->add('/feed', UserController::class, 'showFeed', 'GET', Middleware::class);
$router->add('/registrar', UserController::class, 'showRegister');
$router->add('/login', UserController::class, 'showLogin');
$router->add('/user-tweets/{usuarioApelido}', TweetController::class, 'getTweetsByApelido', 'GET', Middleware::class);
$router->add('/tweets', TweetController::class, 'getAllTweets', 'GET', Middleware::class);
$router->add('/tweets/{tweetId}/{usuarioId}', TweetController::class, 'deleteTweet', 'DELETE');
$router->add('/logout', UserController::class, 'makeLogout', 'GET');

// Orientando as rotas POST
$router->post('/login', [UserController::class, 'makeLogin']);
$router->post('/logout', [UserController::class, 'makeLogout']);
$router->post('/registrar', [UserController::class, 'createUser']);
$router->post('/verificar-apelido', [UserController::class, 'verifyApelido']);
$router->post('/new-tweet', [TweetController::class, 'createTweet']);

return $router;
