<?php
namespace ModelsAdmin;

use Config\DB;
use PDO;
use Exception;
use PDOException;
//use Interfaces\Insertar;

require_once dirname(__DIR__, 2) . '/config/DB.php';

class Producto
{

    private string $codigo_producto;
    private string $nombre_producto;
    private float $precio_producto;
    private int $uds_producto;

    public array $productos = [];

    public PDO $connection;

    public function __construct(array $productos)
    {
        $this->codigo_producto = $productos['codigo_producto'];
        $this->nombre_producto = $productos['nombre_producto'];
        $this->precio_producto = $productos['precio_producto'];
        $this->uds_producto = $productos['uds_producto'];

        $this->productos = $productos;

        $this->connection = DB::getInstance()->getConnection();
    }

    //Metodos getters

    public function getCodigoProducto(): int
    {
        return $this->codigo_producto;
    }

    public function getNombreProducto(): string
    {
        return $this->nombre_producto;
    }

    public function getPrecioProducto(): float
    {
        return $this->precio_producto;
    }

    public function getUdsProducto(): int
    {
        return $this->uds_producto;
    }


    //Método para insertar productos en la tabla PRODUCTOS de la base de datos
    public function insertarBaseDatos(array $productos): void
    {
        try {
            foreach ($productos as $producto) {

                if (!empty($producto['uds_producto']) || !empty($producto['precio_producto'])) {
                    $existe = self::getUnProducto($producto['codigo_producto']);

                    if ($existe === null) {
                        $sqlProductos = "INSERT INTO productos (codigo_producto, nombre_producto, precio_producto, uds_producto) VALUES 
                        (:codigo_producto, :nombre_producto, :precio_producto, :uds_producto)";
                        $result = $this->connection->prepare($sqlProductos);
                        $result->execute([
                            ":codigo_producto" => $producto['codigo_producto'],
                            ":nombre_producto" => $producto['nombre_producto'],
                            ":precio_producto" => $producto['precio_producto'],
                            ":uds_producto" => $producto['uds_producto']
                        ]);

                        $sqlCategorias = "INSERT INTO categorias (codigo_producto, categoria_producto, tipo_producto, modalidad_producto) VALUES 
                        (:codigo_producto, :categoria_producto, :tipo_producto, :modalidad_producto)";
                        $result = $this->connection->prepare($sqlCategorias);
                        $result->execute([
                            ":codigo_producto" => $producto['codigo_producto'],
                            ":categoria_producto" => $producto['categoria_producto'],
                            ":tipo_producto" => $producto['tipo_producto'],
                            ":modalidad_producto" => $producto['modalidad_producto']
                        ]);

                    } else {
                        $totalUnidades = (int)$existe['uds_producto'] + (int)$producto['uds_producto'];
                        //$totalPrecio = (float)$existe['precio'] + (float)$producto['precio'];

                        $sql = "UPDATE productos SET uds_producto = :uds_producto, precio_producto = :precio_producto WHERE codigo_producto = :codigo_producto";
                        $result = $this->connection->prepare($sql);
                        $result->execute([
                            ":uds_producto" => $totalUnidades,
                            ":precio_producto" => $producto['precio_producto'],
                            ":codigo_producto" => $producto['codigo_producto']
                        ]);
                    }
                }
            }
        } catch (Exception $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public static function getProductos(string $categoria_producto)
    {
        try {

            $productosDB = array();

            $sql = "SELECT productos.codigo_producto, nombre_producto, precio_producto, uds_producto, categoria_producto, tipo_producto, modalidad_producto 
            FROM productos
            JOIN categorias
            ON productos.codigo_producto = categorias.codigo_producto
            WHERE categoria_producto = :categoria_producto";
            $connection = DB::getInstance()->getConnection();
            $result = $connection->prepare($sql);
            $result->execute([':categoria_producto' => $categoria_producto]);

            if ($result) {
                $row = $result->fetch(PDO::FETCH_ASSOC);

                while ($row != null) {

                    $productosDB[] = new Producto($row);
                    $row = $result->fetch(PDO::FETCH_ASSOC);
                }
            }

            return $productosDB;
        } catch (Exception $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public static function getUnProducto($codigo)
    {
         try {

            $producto = array();

            $sql = "SELECT codigo_producto, nombre_producto, precio_producto, uds_producto FROM productos WHERE codigo_producto = ?";
            $connection = DB::getInstance()->getConnection();
            $result = $connection->prepare($sql);

            $bool = $result->execute(array($codigo));
            $row = $result->fetch(PDO::FETCH_ASSOC);

            if ($bool) {
                if ($row === false) {
                    return null;
                } else {

                    $producto['codigo_producto'] = $row['codigo_producto'];
                    $producto['nombre_producto'] = $row['nombre_producto'];
                    $producto['precio_producto'] = $row['precio_producto'];
                    $producto['uds_producto'] = $row['uds_producto'];

                    return $producto;
                }
            }
        } catch (PDOException $e) {
            throw new Exception("ERROR - No se pudo obtener el producto con código {$codigo}: " . $e->getMessage());
        }
    }

    public static function eliminarProducto($codigo){
        try {

            $sql = "DELETE FROM productos WHERE codigo_producto = ?";
            $connection = DB::getInstance()->getConnection();
            $result = $connection->prepare($sql);

            $bool = $result->execute(array($codigo));     

            if ($bool) {
                $filasEliminadas = $result->rowCount();

                return $filasEliminadas;

            }else{
                return false;
            }
        } catch (PDOException $e) {
            throw new Exception("Error al eliminar producto con código {$codigo}: " . $e->getMessage());
        }
    }

}
