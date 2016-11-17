<?php

//require "BaseModel.php";

class Cliente extends BaseModel
{

    private $pdo;

    public $clave;
    public $nombre;
    public $paterno;
    public $materno;
    public $rfc;

    private function __construct(PDO $con, array $cliente, $notIncrement = false)
    {
        $this->pdo = $con;

        $this->clave = (!$notIncrement ? (isset($cliente["clave"]) && !is_null($cliente["clave"]) ? sprintf("%04d", $cliente["clave"] + 1) : sprintf("%04d", 1)) : (isset($cliente["clave"]) && !is_null($cliente["clave"]) ? sprintf("%04d", $cliente["clave"]) : "0000"));
        $this->nombre = (isset($cliente["nombre"]) && !is_null($cliente["nombre"]) ? $cliente["nombre"] : "");
        $this->paterno = (isset($cliente["paterno"]) && !is_null($cliente["paterno"]) ? $cliente["paterno"] : "");
        $this->materno = (isset($cliente["materno"]) && !is_null($cliente["materno"]) ? $cliente["materno"] : "");
        $this->tfc = (isset($cliente["tfc"]) && !is_null($cliente["tfc"]) ? $cliente["tfc"] : "");
    }

    static function newInstance(PDO $con)
    {
        return new self($con, self::Initialize($con, "clientes"));
    }

    static function instanceFrom(PDO $con, array $cliente)
    {
        return new self($con, $cliente, true);
    }

    static function fromClave(PDO $con, int $clave)
    {
        return new self($con, self::Initialize($con, "clientes", $clave), true);
    }
    
    static function find(PDO $con, string $cliente){
        $query = "SELECT * FROM clientes 
                    WHERE 
                        `clave` LIKE :cliente OR
                        `nombre` LIKE :cliente OR
                        `paterno` LIKE :cliente OR
                        `materno` LIKE :cliente";

        $stmnt = $con->prepare($query);

        $stmnt->execute(array(":cliente" => "%".$cliente."%"));

        return $stmnt->fetchAll();
    }

    static function getAll(PDO $con)
    {
        $query = "SELECT * FROM clientes";

        $stmnt = $con->prepare($query);

        $stmnt->execute();

        return $stmnt->fetchAll();
    }

    function Guardar()
    {
        $query = "INSERT INTO clientes (clave, nombre, paterno, materno, rfc) VALUES (:clave, :nombre, :paterno, :materno, :rfc)
                    ON DUPLICATE KEY UPDATE nombre = :nombre, paterno = :paterno, materno = :materno, rfc = :rfc;";
        
        $stmnt = $this->pdo->prepare($query);

        return $stmnt->execute(array(":clave" => (int) $this->clave, ":nombre" => $this->nombre, ":paterno" => $this->paterno, ":materno" => $this->materno, ":rfc" => $this->rfc));
    }
}

?>