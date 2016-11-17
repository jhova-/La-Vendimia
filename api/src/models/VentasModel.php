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

        $this->folio = (!$notIncrement ? (isset($venta["folio"]) && !is_null($venta["folio"]) ? sprintf("%04d", $venta["folio"] + 1) : sprintf("%04d", 1)) : (isset($venta["folio"]) && !is_null($venta["folio"]) ? sprintf("%04d", $venta["folio"]) : "0000"));
        $this->cliente = (isset($venta["cliente"]) && !is_null($venta["cliente"]) ? $venta["cliente"] : "");
        $this->articulos = (isset($venta["articulos"]) && !is_null($venta["articulos"]) ? $venta["articulos"] : "");
        $this->fecha = (isset($venta["fecha"]) && !is_null($venta["fecha"]) ? $venta["fecha"] : "");
        $this->total = (isset($venta["total"]) && !is_null($venta["total"]) ? $venta["total"] : "");
        $this->plazo = (isset($venta["plazo"]) && !is_null($venta["plazo"]) ? $venta["plazo"] : "");
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
        $query = "SELECT folio, cliente, nombre, paterno, materno, articulos, total, plazo, DATE_FORMAT(fecha, '%d/%m/%Y') AS fecha 
                    FROM ventas JOIN clientes ON cliente = clave";

        $stmnt = $con->prepare($query);

        $stmnt->execute();

        return $stmnt->fetchAll();
    }

    function Guardar()
    {
        $query = "INSERT INTO ventas (folio, cliente, articulos, total, plazo) VALUES (:folio, :cliente, :articulos, :total, :plazo)
                    ON DUPLICATE KEY UPDATE cliente = :cliente, articulos = :articulos, total = :total, plazo = :plazo;";
        
        $stmnt = $this->pdo->prepare($query);
        
        if($stmnt->execute(array(":folio" => (int) $this->folio, ":cliente" => $this->cliente, ":articulos" => json_encode($this->articulos), ":total" => $this->total, ":plazo" => $this->plazo)))
        {
            return $this->DeducirArticulos($this->pdo, $this->articulos);
        }
    }

    private function DeducirArticulos(PDO $con, $articulos){
        foreach($articulos as $articulo){
            $query = "UPDATE articulos SET existencia = existencia - :cantidad WHERE clave = :clave";

            $stmnt = $con->prepare($query);

            if(!$stmnt->execute($articulo))
            {
                return false;
            }
        }

        return true;
    }
}

?>