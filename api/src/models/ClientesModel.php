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

        $this->clave = (!$notIncrement ? (is_null($cliente["clave"]) ? sprintf("%04d", 1) : sprintf("%04d", $cliente["clave"] + 1)) : sprintf("%04d", $cliente["clave"]));
        $this->nombre = (is_null($cliente["nombre"]) ? "" : $cliente["nombre"]);
        $this->paterno = (is_null($cliente["paterno"]) ? "" : $cliente["paterno"]);
        $this->materno = (is_null($cliente["materno"]) ? "" : $cliente["materno"]);
        $this->rfc = (is_null($cliente["rfc"]) ? "" : $cliente["rfc"]);
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