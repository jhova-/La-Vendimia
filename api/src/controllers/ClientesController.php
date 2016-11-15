<?php

require "models/ClientesModel.php";

$app->group("/clientes", function(){
    $this->get("", function(\Slim\Http\Request $request, \Slim\Http\Response $response){
        return $response->withJson(Cliente::getAll($this->db));
    });

    $this->get("/{id:[1-9][0-9]*}", function(\Slim\Http\Request $request, \Slim\Http\Response $response, $args){
        return $response->withJson(Cliente::fromClave($this->db, (int) $args['id']));
    });

    $this->get("/new", function(\Slim\Http\Request $request, \Slim\Http\Response $response){
        return $response->withJson(Cliente::newInstance($this->db));
    });

    $this->post("/register", function(\Slim\Http\Request $request, \Slim\Http\Response $response){
        $cliente = Cliente::instanceFrom($this->db, $request->getParsedBody());
        
        return $response->withJson($cliente->Guardar());
    });
});

?>