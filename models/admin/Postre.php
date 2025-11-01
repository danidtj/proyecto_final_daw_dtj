<?php

namespace ModelsAdmin;

use Config\DB;
use PDO;
use Exception;
use PDOException;
//use Interfaces\Insertar;

require_once dirname(__DIR__, 2) . '/config/DB.php';

class Postre extends Producto
{

    public array $postres = [];

    public function __construct(array $postres)
    {
        parent::__construct($postres);
        $this->postres = $postres;
    }

    public function insertarBaseDatos(array $postres): void
    {
        parent::insertarBaseDatos($postres);
    }

    public static function getPostres()
    {
        try {

            $postresDB = array();

            $sql = "SELECT productos.codigo_producto, nombre_producto, precio_producto, uds_producto, categoria_producto, tipo_producto, modalidad_producto 
            FROM productos
            JOIN categorias
            ON productos.codigo_producto = categorias.codigo_producto
            WHERE categoria_producto = 'postre'";
            $connection = DB::getInstance()->getConnection();
            $result = $connection->query($sql);

            if ($result) {
                $row = $result->fetch(PDO::FETCH_ASSOC);
                
                while ($row != null) {

                    $postresDB[] = new Postre($row);
                    $row = $result->fetch(PDO::FETCH_ASSOC);
                }
            }

            return $postresDB;
        } catch (Exception $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public static function getUnPostre($codigo)
    {
        return parent::getUnProducto($codigo);
    }

    
    public static function eliminarPostre($codigo){
        return parent::eliminarProducto($codigo);
    }
}
