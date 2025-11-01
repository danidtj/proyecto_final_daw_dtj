<?php

namespace ModelsAdmin;

use Config\DB;
use PDO;
use Exception;
use PDOException;
//use Interfaces\Insertar;

require_once dirname(__DIR__, 2) . '/config/DB.php';

class Bebida extends Producto
{

    public array $bebidas = [];

    public function __construct(array $bebidas)
    {
        parent::__construct($bebidas);
        $this->bebidas = $bebidas;
    }

    public function insertarBaseDatos(array $bebidas): void
    {
        parent::insertarBaseDatos($bebidas);
    }

    public static function getBebidas()
    {
        try {

            $bebidasDB = array();

            $sql = "SELECT productos.codigo_producto, nombre_producto, precio_producto, uds_producto, categoria_producto, tipo_producto, modalidad_producto 
            FROM productos
            JOIN categorias
            ON productos.codigo_producto = categorias.codigo_producto
            WHERE categoria_producto = 'bebida'";
            $connection = DB::getInstance()->getConnection();
            $result = $connection->query($sql);

            if ($result) {
                $row = $result->fetch(PDO::FETCH_ASSOC);

                while ($row != null) {

                    $bebidasDB[] = new Bebida($row);
                    $row = $result->fetch(PDO::FETCH_ASSOC);
                }
            }

            return $bebidasDB;
        } catch (Exception $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function getUnaBebida($codigo)
    {
        return parent::getUnProducto($codigo);
    }


    public static function eliminarBebida($codigo)
    {
        return parent::eliminarProducto($codigo);
    }
}
