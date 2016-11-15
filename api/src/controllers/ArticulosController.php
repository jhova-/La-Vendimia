<?php

require "models/ArticulosModel.php";

$app->group("/articulos", function(){
    $this->get("", function(\Slim\Http\Request $request, \Slim\Http\Response $response){
        return $response->withJson(Articulo::getAll($this->db));
    });

    $this->get("/{clave:[1-9][0-9]*}", function(\Slim\Http\Request $request, \Slim\Http\Response $response, $args){
        return $response->withJson(Articulo::fromClave($this->db, (int) $args['clave']));
    });

    $this->get("/new", function(\Slim\Http\Request $request, \Slim\Http\Response $response){
        return $response->withJson(Articulo::newInstance($this->db));
    });

    $this->post("/register", function(\Slim\Http\Request $request, \Slim\Http\Response $response){
        $articulo = Articulo::instanceFrom($this->db, $request->getParsedBody());
        
        return $response->withJson($articulo->Guardar());
    });
});

?>