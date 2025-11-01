<?php
namespace ModelsFrontend;

//session_start();

use Config\DB;
use ModelsAdmin\Producto;
use PDO;
use PDOException;
use Exception;

require_once dirname(__DIR__, 2) . '/config/DB.php';
require_once dirname(__DIR__) . '/admin/Producto.php';

class Carrito
{
    private PDO $connection;
    private $productos = array();
    
    /*public function __construct()
    {
        $this->connection = DB::getInstance()->getConnection();
        
    }*/
    
    
    // Introduce un nuevo artículo en la cesta de la compra
    public function newArticulo($codigo) {
        $nuevoProducto = Producto::getUnProducto($codigo);
        $this->productos[] = $nuevoProducto;
    }
    
    // Obtiene los artículos en la cesta
    public function getProductos() { 
        return $this->productos; 
    }
    
    // Obtiene el coste total de los artículos en la cesta
    public function getCoste() {
        $coste = 0;
        foreach ($this->productos as $p) {
            $coste += $p->getPrecioProducto();
        }
        return $coste;
    }
    
    // Devuelve true si la cesta está vacía
    public function isVacia() {

        return (count($this->productos) == 0);
    }
    
    // Guarda la cesta de la compra en la sesión del usuario
    public function saveCarrito() { 
        $_SESSION['carrito'] = $this; 
    }
    
    // Recupera la cesta de la compra almacenada en la sesión del usuario
    public static function loadCarrito() {
        if (!isset($_SESSION['carrito'])) {
            return new Carrito();
        } else {
            return $_SESSION['carrito'];
        }
    }
}
