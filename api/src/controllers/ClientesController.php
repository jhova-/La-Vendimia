<?php

require "models/ClientesModel.php";

$app->group("/clientes", function(){
    $this->get("", function(\Slim\Http\Request $request, \Slim\Http\Response $response){
        return $response
		        ->withHeader('Access-Control-Allow-Origin', 'http://www.la-vendimia.tk')
		        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            	->withHeader('Access-Control-Allow-Methods', 'GET, POST')
                ->withJson(Cliente::getAll($this->db));
    });

    $this->get("/{id:[1-9][0-9]*}", function(\Slim\Http\Request $request, \Slim\Http\Response $response, $args){
        return $response
		        ->withHeader('Access-Control-Allow-Origin', 'http://www.la-vendimia.tk')
		        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            	->withHeader('Access-Control-Allow-Methods', 'GET, POST')
                ->withJson(Cliente::fromClave($this->db, (int) $args['id']));
    });

    $this->get("/new", function(\Slim\Http\Request $request, \Slim\Http\Response $response){
        return $response
		        ->withHeader('Access-Control-Allow-Origin', 'http://www.la-vendimia.tk')
		        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            	->withHeader('Access-Control-Allow-Methods', 'GET, POST')
                ->withJson(Cliente::newInstance($this->db));
    });

    $this->post("/find", function(\Slim\Http\Request $request, \Slim\Http\Response $response){
        return $response
		        ->withHeader('Access-Control-Allow-Origin', 'http://www.la-vendimia.tk')
		        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            	->withHeader('Access-Control-Allow-Methods', 'GET, POST')
                ->withJson(Cliente::find($this->db, ($request->getParsedBody())["cliente"]));
    });

    $this->post("/register", function(\Slim\Http\Request $request, \Slim\Http\Response $response){
        $cliente = Cliente::instanceFrom($this->db, $request->getParsedBody());
        
        return $response
		        ->withHeader('Access-Control-Allow-Origin', 'http://www.la-vendimia.tk')
		        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            	->withHeader('Access-Control-Allow-Methods', 'GET, POST')
                ->withJson($cliente->Guardar());
    });
});

?>