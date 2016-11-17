<?php

//require "BaseModel.php";

class Articulo extends BaseModel
{

    private $pdo;

    public $clave;
    public $descripcion;
    public $modelo;
    public $precio;
    public $existencia;

    private function __construct(PDO $con, array $articulo, $notIncrement = false)
    {
        $this->pdo = $con;

        $this->clave = (!$notIncrement ? (isset($articulo["clave"]) && !is_null($articulo["clave"]) ? sprintf("%04d", $articulo["clave"] + 1) : sprintf("%04d", 1)) : (isset($articulo["clave"]) && !is_null($articulo["clave"]) ? sprintf("%04d", $articulo["clave"]) : "0000"));
        $this->descripcion = (isset($articulo["descripcion"]) && !is_null($articulo["descripcion"]) ? $articulo["descripcion"] : "");
        $this->modelo = (isset($articulo["modelo"]) && !is_null($articulo["modelo"]) ? $articulo["modelo"] : "");
        $this->precio = (isset($articulo["precio"]) && !is_null($articulo["precio"]) ? $articulo["precio"] : "");
        $this->existencia = (isset($articulo["existencia"]) && !is_null($articulo["existencia"]) ? $articulo["existencia"] : "");
    }

    static function newInstance(PDO $con)
    {
        return new self($con, self::Initialize($con, "articulos"));
    }

    static function instanceFrom(PDO $con, array $articulo)
    {
        return new self($con, $articulo, true);
    }

    static function fromClave(PDO $con, int $clave)
    {
        return new self($con, self::Initialize($con, "articulos", $clave), true);
    }

    static function find(PDO $con, string $articulo){
        $query = "SELECT clave, descripcion, modelo FROM articulos 
                    WHERE 
                        `clave` LIKE :articulo OR
                        `descripcion` LIKE :articulo OR
                        `modelo` LIKE :articulo";

        $stmnt = $con->prepare($query);

        $stmnt->execute(array(":articulo" => "%".$articulo."%"));

        return $stmnt->fetchAll();
    }

    static function getAll(PDO $con)
    {
        $query = "SELECT * FROM articulos";

        $stmnt = $con->prepare($query);

        $stmnt->execute();

        return $stmnt->fetchAll();
    }

    function Guardar()
    {
        $query = "INSERT INTO articulos (clave, descripcion, modelo, precio, existencia) VALUES (:clave, :descripcion, :modelo, :precio, :existencia)
                    ON DUPLICATE KEY UPDATE descripcion = :descripcion, modelo = :modelo, precio = :precio, existencia = :existencia;";
        
        $stmnt = $this->pdo->prepare($query);

        return $stmnt->execute(array(":clave" => (int) $this->clave, ":descripcion" => $this->descripcion, ":modelo" => $this->modelo, ":precio" => $this->precio, ":existencia" => $this->existencia));
    }
}

?>