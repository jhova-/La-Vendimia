<?php

//require "BaseModel.php";

class Venta extends BaseModel
{

    private $pdo;

    public $folio;
    public $cliente;
    public $articulos;
    public $fecha;
    public $total;
    public $plazo;

    private function __construct(PDO $con, array $venta, $notIncrement = false)
    {
        $this->pdo = $con;

        $this->folio = (!$notIncrement ? (is_null($venta["folio"]) ? sprintf("%04d", 1) : sprintf("%04d", $venta["folio"] + 1)) : sprintf("%04d", $venta["folio"]));
        $this->cliente = (is_null($venta["cliente"]) ? "" : $venta["cliente"]);
        $this->articulos = (is_null($venta["articulos"]) ? "" : $venta["articulos"]);
        $this->fecha = (is_null($venta["fecha"]) ? "" : $venta["fecha"]);
        $this->total = (is_null($venta["total"]) ? "" : $venta["total"]);
        $this->plazo = (is_null($venta["plazo"]) ? "" : $venta["plazo"]);
    }

    static function newInstance(PDO $con)
    {
        return new self($con, self::Initialize($con, "ventas"));
    }

    static function instanceFrom(PDO $con, array $venta)
    {
        return new self($con, $venta, true);
    }

    static function fromFolio(PDO $con, int $folio)
    {
        return new self($con, self::Initialize($con, "ventas", $folio), true);
    }

    static function getAll(PDO $con)
    {
        $query = "SELECT * FROM ventas";

        $stmnt = $con->prepare($query);

        $stmnt->execute();

        return $stmnt->fetchAll();
    }

    function Guardar()
    {
        $query = "INSERT INTO ventas (folio, cliente, articulos, fecha, total, plazo) VALUES (:folio, :cliente, :articulos, :fecha, :total, :plazo)
                    ON DUPLICATE KEY UPDATE cliente = :cliente, articulos = :articulos, fecha = :fecha, total = :total, plazo = :plazo;";
        
        $stmnt = $this->pdo->prepare($query);

        return $stmnt->execute(array(":folio" => (int) $this->folio, ":cliente" => $this->cliente, ":articulos" => json_encode($this->articulos), ":fecha" => $this->fecha, ":total" => $this->total, ":plazo" => $this->plazo));
    }
}

?>