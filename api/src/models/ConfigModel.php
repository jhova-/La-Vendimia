<?php

//require "BaseModel.php";

class Config extends BaseModel
{

    private $pdo;

    public $id;
    public $tasa;
    public $porcentaje_engancho;
    public $plazo_max;

    private function __construct(PDO $con, array $config, $notIncrement = false)
    {
        $this->pdo = $con;

        $this->id = (!$notIncrement ? (is_null($config["id"]) ? 1 : $config["id"] + 1) : $config["id"]);
        $this->tasa = (is_null($config["tasa"]) ? "" : $config["tasa"]);
        $this->porcentaje_engancho = (is_null($config["porcentaje_engancho"]) ? "" : $config["porcentaje_engancho"]);
        $this->plazo_max = (is_null($config["plazo_max"]) ? "" : $config["plazo_max"]);
    }

    static function newInstance(PDO $con)
    {
        return new self($con, self::Initialize($con, "config"));
    }

    static function instanceFrom(PDO $con, array $config)
    {
        return new self($con, $config, true);
    }

    function Guardar()
    {
        $query = "INSERT INTO config (id, tasa, porcentaje_engancho, plazo_max) VALUES (:id, :tasa, :porcentaje_engancho, :plazo_max)
                    ON DUPLICATE KEY UPDATE tasa = :tasa, porcentaje_engancho = :porcentaje_engancho, plazo_max = :plazo_max;";
        
        $stmnt = $this->pdo->prepare($query);

        return $stmnt->execute(array(":id" => $this->id, ":tasa" => $this->tasa, ":porcentaje_engancho" => $this->porcentaje_engancho, ":plazo_max" => $this->plazo_max));
    }
}

?>