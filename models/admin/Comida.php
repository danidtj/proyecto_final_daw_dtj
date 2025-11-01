<?php

namespace ModelsAdmin;

use Config\DB;
use PDO;
use Exception;
use PDOException;
//use Interfaces\Insertar;

require_once dirname(__DIR__, 2) . '/config/DB.php';

class Comida extends Producto
{

    public array $comidas = [];

    public function __construct(array $comidas)
    {
        parent::__construct($comidas);
        $this->comidas = $comidas;
    }

    public function insertarBaseDatos(array $comidas): void
    {
        parent::insertarBaseDatos($comidas);
    }

    public static function getComidas()
    {
        try {

            $comidasDB = array();

            $sql = "SELECT productos.codigo_producto, nombre_producto, precio_producto, uds_producto, categoria_producto, tipo_producto, modalidad_producto 
            FROM productos
            JOIN categorias
            ON productos.codigo_producto = categorias.codigo_producto
            WHERE categoria_producto = 'comida'";
            $connection = DB::getInstance()->getConnection();
            $result = $connection->query($sql);

            if ($result) {
                $row = $result->fetch(PDO::FETCH_ASSOC);

                while ($row != null) {

                    $comidasDB[] = new Comida($row);
                    $row = $result->fetch(PDO::FETCH_ASSOC);
                }
            }

            return $comidasDB;
        } catch (Exception $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function getUnaComida($codigo)
    {
        return parent::getUnProducto($codigo);
    }


    public static function eliminarComida($codigo)
    {
        return parent::eliminarProducto($codigo);
    }
}
