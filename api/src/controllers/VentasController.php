<?php

require "models/VentasModel.php";

$app->group("/ventas", function(){
    $this->get("", function(\Slim\Http\Request $request, \Slim\Http\Response $response){
        return $response
		        ->withHeader('Access-Control-Allow-Origin', 'http://www.la-vendimia.tk')
		        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            	->withHeader('Access-Control-Allow-Methods', 'GET, POST')
                ->withJson(Venta::getAll($this->db));
    });

    $this->get("/{folio:[1-9][0-9]*}", function(\Slim\Http\Request $request, \Slim\Http\Response $response, $args){
        return $response
		        ->withHeader('Access-Control-Allow-Origin', 'http://www.la-vendimia.tk')
		        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            	->withHeader('Access-Control-Allow-Methods', 'GET, POST')
                ->withJson(Venta::fromFolio($this->db, (int) $args['folio']));
    });

    $this->get("/new", function(\Slim\Http\Request $request, \Slim\Http\Response $response){
        return $response
		        ->withHeader('Access-Control-Allow-Origin', 'http://www.la-vendimia.tk')
		        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            	->withHeader('Access-Control-Allow-Methods', 'GET, POST')
                ->withJson(Venta::newInstance($this->db));
    });

    $this->post("/register", function(\Slim\Http\Request $request, \Slim\Http\Response $response){
        $venta = Venta::instanceFrom($this->db, $request->getParsedBody());
        
        return $response
		        ->withHeader('Access-Control-Allow-Origin', 'http://www.la-vendimia.tk')
		        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            	->withHeader('Access-Control-Allow-Methods', 'GET, POST')
                ->withJson($venta->Guardar());
    });
});

?>