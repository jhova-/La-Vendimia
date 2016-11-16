<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$config['displayErrorDetails'] = true;

$config['db']['host']   = "www.la-vendimia.tk:6033";
$config['db']['user']   = "vendimia_user";
$config['db']['pass']   = "490fb89fb01fcdcfc729d28ca7a29f6457977c1b";
$config['db']['dbname'] = "la_vendimia";

$app = new \Slim\App(["settings" => $config]);

$container = $app->getContainer();

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'] . ";charset=utf8",
            $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    return $pdo;
};

$app->get("/", function(Request $request, Response $response){
    $res["success"] = true;

    return $response
		        ->withHeader('Access-Control-Allow-Origin', 'http://www.la-vendimia.tk')
		        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            	->withHeader('Access-Control-Allow-Methods', 'GET, POST')
                ->withJson($res);
});

require "models/BaseModel.php";
require "controllers/ClientesController.php";
require "controllers/ArticulosController.php";
require "controllers/VentasController.php";
require "controllers/ConfigController.php";

$app->run();