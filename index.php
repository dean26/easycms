<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Jenssegers\Blade\Blade;

require_once 'config/config.php';
require 'vendor/autoload.php';

$db = new PDO("sqlite:".__DIR__."/db/file.sqlite");

$app = new \Slim\App(["settings" => $config]);

// Get container
$container = $app->getContainer();

$container['view'] = function ($container) {

    $views = __DIR__ . '/views';
    $cache = __DIR__ . '/cache';

    $view = new Blade($views, $cache);

    return $view;
};


//rutowanie
$app->get('/', function () use($db) {
    return $this->view->make('admin.index', array('test' => 123))->render();
});

$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});



$app->run();


?>