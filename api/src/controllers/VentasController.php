<?php

require "models/VentasModel.php";

$app->group("/ventas", function(){
    $this->get("", function(\Slim\Http\Request $request, \Slim\Http\Response $response){
        return $response->withJson(Venta::getAll($this->db));
    });

    $this->get("/{folio:[1-9][0-9]*}", function(\Slim\Http\Request $request, \Slim\Http\Response $response, $args){
        return $response->withJson(Venta::fromFolio($this->db, (int) $args['folio']));
    });

    $this->get("/new", function(\Slim\Http\Request $request, \Slim\Http\Response $response){
        return $response->withJson(Venta::newInstance($this->db));
    });

    $this->post("/register", function(\Slim\Http\Request $request, \Slim\Http\Response $response){
        $venta = Venta::instanceFrom($this->db, $request->getParsedBody());
        
        return $response->withJson($venta->Guardar());
    });
});

?>