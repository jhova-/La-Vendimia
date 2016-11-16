<?php

require "models/ConfigModel.php";

$app->group("/config", function(){
    $this->get("", function(\Slim\Http\Request $request, \Slim\Http\Response $response){
        return $response
		        ->withHeader('Access-Control-Allow-Origin', 'http://www.la-vendimia.tk')
		        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            	->withHeader('Access-Control-Allow-Methods', 'GET, POST')
                ->withJson(Config::newInstance($this->db));
    });

    $this->post("/register", function(\Slim\Http\Request $request, \Slim\Http\Response $response){
        $articulo = Config::instanceFrom($this->db, $request->getParsedBody());
        
        return $response
		        ->withHeader('Access-Control-Allow-Origin', 'http://www.la-vendimia.tk')
		        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            	->withHeader('Access-Control-Allow-Methods', 'GET, POST')
                ->withJson($articulo->Guardar());
    });
});

?>