<?php

class BaseModel
{
    function Initialize(PDO $con, string $tableName, $id = 0)
    {
        switch($tableName)
        {
            case "clientes":
                if($id == 0)
                {
                    $query = "SELECT MAX(clave) AS clave FROM clientes;";
                }
                else
                {
                    $query = "SELECT * FROM clientes WHERE clave = :id;";
                }
                break;
            case "ventas":
                if($id == 0)
                {
                    $query = "SELECT MAX(folio) AS folio FROM ventas;";
                }
                else
                {
                    $query = "SELECT * FROM ventas WHERE folio = :id;";
                }
                break;
            case "articulos":
                if($id == 0)
                {
                    $query = "SELECT MAX(clave) AS clave FROM articulos;";
                }
                else
                {
                    $query = "SELECT * FROM articulos WHERE clave = :id;";
                }
                break;
            case "config":
                $query = "SELECT * FROM config;";
                break;
        }
        
        $stmnt = $con->prepare($query);

        if($id == 0)
        {
            $stmnt->execute();
        }
        else
        {
            $stmnt->execute(array(":id" => $id));
        }

        if($stmnt->rowCount() > 0)
        {
            return $stmnt->fetch();
        }
        else
        {
            return array();
        }
    }
}

?>