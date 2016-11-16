<?php

require "models/ArticulosModel.php";

$app->group("/articulos", function(){
    $this->get("", function(\Slim\Http\Request $request, \Slim\Http\Response $response){
        return $response
		        ->withHeader('Access-Control-Allow-Origin', 'http://www.la-vendimia.tk')
		        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            	->withHeader('Access-Control-Allow-Methods', 'GET, POST')
                ->withJson(Articulo::getAll($this->db));
    });

    $this->get("/{clave:[1-9][0-9]*}", function(\Slim\Http\Request $request, \Slim\Http\Response $response, $args){
        return $response
		        ->withHeader('Access-Control-Allow-Origin', 'http://www.la-vendimia.tk')
		        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            	->withHeader('Access-Control-Allow-Methods', 'GET, POST')
                ->withJson(Articulo::fromClave($this->db, (int) $args['clave']));
    });

    $this->get("/new", function(\Slim\Http\Request $request, \Slim\Http\Response $response){
        return $response
		        ->withHeader('Access-Control-Allow-Origin', 'http://www.la-vendimia.tk')
		        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            	->withHeader('Access-Control-Allow-Methods', 'GET, POST')
                ->withJson(Articulo::newInstance($this->db));
    });

    $this->post("/find", function(\Slim\Http\Request $request, \Slim\Http\Response $response){
        return $response
		        ->withHeader('Access-Control-Allow-Origin', 'http://www.la-vendimia.tk')
		        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            	->withHeader('Access-Control-Allow-Methods', 'GET, POST')
                ->withJson(Articulo::find($this->db, ($request->getParsedBody())['articulo']));
    });

    $this->post("/register", function(\Slim\Http\Request $request, \Slim\Http\Response $response){
        $articulo = Articulo::instanceFrom($this->db, $request->getParsedBody());
        
        return $response
		        ->withHeader('Access-Control-Allow-Origin', 'http://www.la-vendimia.tk')
		        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            	->withHeader('Access-Control-Allow-Methods', 'GET, POST')
                ->withJson($articulo->Guardar());
    });
});

?>