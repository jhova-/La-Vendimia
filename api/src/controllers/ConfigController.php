<?php

require "models/ConfigModel.php";

$app->group("/config", function(){
    $this->get("", function(\Slim\Http\Request $request, \Slim\Http\Response $response){
        return $response->withJson(Config::newInstance($this->db));
    });

    $this->post("/register", function(\Slim\Http\Request $request, \Slim\Http\Response $response){
        $articulo = Config::instanceFrom($this->db, $request->getParsedBody());
        
        return $response->withJson($articulo->Guardar());
    });
});

?>