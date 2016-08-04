<?php

ini_set('display_errors', 'off');
session_start();
session_set_cookie_params(2400);

date_default_timezone_set('Europe/Warsaw');

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Jenssegers\Blade\Blade;

require 'vendor/autoload.php';
require_once 'config/config.php';

spl_autoload_register(function ($classname) {
    if(file_exists(__DIR__ . "/app/classes/controllers/" . $classname . ".class.php")){
        require (__DIR__ . "/app/classes/controllers/" . $classname . ".class.php");
    } elseif(file_exists(__DIR__ . "/app/classes/models/" . $classname . ".class.php")) {
        require(__DIR__ . "/app/classes/models/" . $classname . ".class.php");
    } elseif(file_exists(__DIR__ . "/app/classes/" . $classname . ".class.php")) {
        require(__DIR__ . "/app/classes/" . $classname . ".class.php");
    }
});

//sciezka do glownego katalogu na serwerze
AppHelper::SetPublicPatch(__DIR__."/");

//tworzenie obiektu
$app = new \Slim\App(["settings" => $config]);

//proste sprawdzenie autoryzacji
$mw = function ($request, $response, $next) {
    $response = $next($request, $response);
    return $response;
};

// Get container
$container = $app->getContainer();

//szablony Blade dodane do skryptu
$container['view'] = function ($container) {
    $views = AppHelper::PublicPatch() . '/app/views';
    $cache = AppHelper::PublicPatch() . '/app/cache';
    $view = new Blade($views, $cache);
    AppHelper::setBladeIns($view);
    return $view;
};

//logs
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('admin');
    $file_handler = new \Monolog\Handler\StreamHandler(AppHelper::PublicPatch() ."/app/logs/".date("n_Y").".app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};
//zarzadzanie baza danych
$container['medoo'] = function ($container) use($config) {
    $database = new medoo([
        'database_type' => 'mysql',
        'database_name' => $config['database_name'],
        'server' => $config['server'],
        'username' => $config['username'],
        'password' => $config['password'],
        'charset' => 'utf8',
        'prefix' => $config['prefix'],
    ]);
    AppHelper::setMedooIns($database);
    return $database;
};
$container['Model'] = function ($container) {
    return new Model($container);
};

//errors
$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        $c['logger']->addError($exception->getMessage(), array(
            'ip' => $_SERVER['REMOTE_ADDR']
        ));
        return $response->withRedirect(\AppHelper::UrlTo('/home/error?code=500'), 500);
    };
};
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        $c['logger']->addError($exception->getMessage(), array(
            'ip' => $_SERVER['REMOTE_ADDR']
        ));
        return $response->withRedirect(\AppHelper::UrlTo('/home/error?code=404'), 404);
    };
};

//rutowanie
$app->get('/', function (Request $request, Response $response)  {
    $response->getBody()->write("Front - czekay na Twoją implementtacje :)");
    return $response;
});


$app->run();


?>