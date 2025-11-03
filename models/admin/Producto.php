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
    private int $id_producto;
    private int $uds_stock;
    private string $nombre_corto;
    private string $nombre_largo;
    private string $descripcion;
    private float $precio_unitario;

    public array $productos = [];

    public PDO $connection;

    public function __construct(array $productos)
    {
        $this->id_producto      = $productos['id_producto'];
        $this->nombre_corto   = $productos['nombre_corto'] ?? '';
        $this->nombre_largo   = $productos['nombre_largo'] ?? '';
        $this->descripcion    = $productos['descripcion'] ?? '';
        $this->precio_unitario = isset($productos['precio_unitario']) ? (float)$productos['precio_unitario'] : 0.0;
        $this->uds_stock       = isset($productos['uds_stock']) ? (int)$productos['uds_stock'] : 0;

        $this->productos = $productos;

        $this->connection = DB::getInstance()->getConnection();
    }


    //Metodos getters


    public function getNombreCorto(): string
    {
        return $this->nombre_corto;
    }

    public function getPrecioUnitario(): float
    {
        return $this->precio_unitario;
    }

    public function getUdsStock(): int
    {
        return $this->uds_stock;
    }

    public function getNombreLargo(): string
    {
        return $this->nombre_largo;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }
    public function getIdProducto(): int
    {
        return $this->id_producto;
    }



    //Método para crear un producto nuevo en la base de datos e introducir su categoría
    public function crearProductoNuevo(array $producto, array $categoria)
    {
        try {
            //Llama a la base de datos para obtener el id_categoria en base a nombre_categoria de la tabla categorias
            $sqlCategoria = "SELECT id_categoria FROM categorias WHERE nombre_categoria = :nombre_categoria AND
            tipo_categoria = :tipo_categoria AND modalidad_producto = :modalidad_producto";
            $result = $this->connection->prepare($sqlCategoria);
            $result->execute([
                ":nombre_categoria" => $categoria['nombre_categoria'],
                ":tipo_categoria" => $categoria['tipo_categoria'],
                ":modalidad_producto" => $categoria['modalidad_producto']
            ]);
            $id_categoria = $result->fetchColumn();

            if ($id_categoria === false) {
                //crear la categoría si no existe
                $sqlNuevaCategoria = "INSERT INTO categorias (nombre_categoria, tipo_producto, modalidad_producto) VALUES 
                (:nombre_categoria, :tipo_producto, :modalidad_producto)";
                $result = $this->connection->prepare($sqlNuevaCategoria);
                $result->execute([
                    ":nombre_categoria" => $categoria['nombre_categoria'],
                    ":tipo_producto" => $categoria['tipo_producto'],
                    ":modalidad_producto" => $categoria['modalidad_producto']
                ]);
                $id_categoria = $this->connection->lastInsertId();
            }

            $sqlProductos = "INSERT INTO productos (id_categoria, uds_stock, nombre_corto, nombre_largo, descripcion, precio_unitario) VALUES 
            (:id_categoria, :uds_stock, :nombre_corto, :nombre_largo, :descripcion, :precio_unitario)";
            $result = $this->connection->prepare($sqlProductos);
            $result->execute([
                ":id_categoria" => $id_categoria,
                ":uds_stock" => $producto['uds_stock'],
                ":nombre_corto" => $producto['nombre_corto'],
                ":nombre_largo" => $producto['nombre_largo'],
                ":descripcion" => $producto['descripcion'],
                ":precio_unitario" => $producto['precio_unitario']
            ]);

            return true;



            /*$sqlCategorias = "INSERT INTO categorias (codigo_producto, categoria_producto, tipo_producto, modalidad_producto) VALUES 
            (:codigo_producto, :categoria_producto, :tipo_producto, :modalidad_producto)";
            $result = $this->connection->prepare($sqlCategorias);
            $result->execute([
                ":codigo_producto" => $producto['codigo_producto'],
                ":categoria_producto" => $producto['categoria_producto'],
                ":tipo_producto" => $producto['tipo_producto'],
                ":modalidad_producto" => $producto['modalidad_producto']
            ]);*/
        } catch (Exception $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    //Método para insertar productos en la tabla PRODUCTOS de la base de datos
    public function insertarBaseDatos(array $productos): void
    {
        try {
            foreach ($productos as $producto) {
                //Comprobamos si alguno de los campos no está vacío
                if (!empty($producto['uds_stock']) || !empty($producto['precio_unitario'])) {
                    //Comprobamos si el producto ya existe en la base de datos
                    $existe = self::getUnProducto($producto['id_producto']);

                    if ($existe === null) {
                        //Si no existe, lo insertamos
                        $sqlProductos = "INSERT INTO productos (id_producto, id_categoria, nombre_producto, precio_unitario, uds_stock) VALUES 
                        (:id_producto, :id_categoria, :nombre_producto, :precio_unitario, :uds_stock)";
                        $result = $this->connection->prepare($sqlProductos);
                        $result->execute([
                            ":id_producto" => $producto['id_producto'],
                            ":id_categoria" => $producto['id_categoria'],
                            ":nombre_producto" => $producto['nombre_producto'],
                            ":precio_unitario" => $producto['precio_unitario'],
                            ":uds_stock" => $producto['uds_stock']
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
                        //$totalUnidades = (int)$existe['uds_stock'] + (int)$producto['uds_stock'];
                        //$totalPrecio = (float)$existe['precio'] + (float)$producto['precio'];
                        if (!empty($producto['precio_unitario'])) {
                            $sql = "UPDATE productos SET precio_unitario = :precio_unitario WHERE id_producto = :id_producto";
                            $result = $this->connection->prepare($sql);
                            $result->execute([
                                ":precio_unitario" => $producto['precio_unitario'],
                                ":id_producto" => $producto['id_producto']
                            ]);
                        } 
                        if (!empty($producto['uds_stock'])) {
                            $sql = "UPDATE productos SET uds_stock = :uds_stock WHERE id_producto = :id_producto";
                            $result = $this->connection->prepare($sql);
                            $result->execute([
                                ":uds_stock" => $producto['uds_stock'],
                                ":id_producto" => $producto['id_producto']
                            ]);
                        } elseif (empty($producto['uds_stock']) && empty($producto['precio_unitario'])) {

                            $sql = "UPDATE productos SET uds_stock = :uds_stock, precio_unitario = :precio_unitario WHERE id_producto = :id_producto";
                            $result = $this->connection->prepare($sql);
                            $result->execute([
                                ":uds_stock" => $producto['uds_stock'],
                                ":precio_unitario" => $producto['precio_unitario'],
                                ":id_producto" => $producto['id_producto']
                            ]);
                        }
                    }
                }
            }
        } catch (Exception $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public static function getProductos(string $nombre_categoria)
    {
        try {

            $productosDB = array();

            $sql = "SELECT productos.id_producto, productos.id_categoria, productos.uds_stock, productos.nombre_corto, productos.nombre_largo, 
            productos.descripcion, productos.precio_unitario, 
            categorias.nombre_categoria, categorias.tipo_categoria, categorias.modalidad_producto
                FROM productos
                JOIN categorias
                ON productos.id_categoria = categorias.id_categoria
                WHERE categorias.nombre_categoria = :nombre_categoria
                ";
            $connection = DB::getInstance()->getConnection();
            $result = $connection->prepare($sql);
            $result->execute([':nombre_categoria' => $nombre_categoria]);

            if ($result) {
                $row = $result->fetch(PDO::FETCH_ASSOC);

                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $productosDB[] = new Producto($row);
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

            $sql = "SELECT id_producto, id_categoria, uds_stock , nombre_corto, nombre_largo, descripcion, precio_unitario
            FROM productos WHERE id_producto = ?";
            $connection = DB::getInstance()->getConnection();
            $result = $connection->prepare($sql);

            $bool = $result->execute(array($codigo));
            $row = $result->fetch(PDO::FETCH_ASSOC);

            if ($bool) {
                if ($row === false) {
                    return null;
                } else {

                    $producto['id_producto'] = $row['id_producto'];
                    $producto['id_categoria'] = $row['id_categoria'];
                    $producto['uds_stock'] = $row['uds_stock'];
                    $producto['nombre_corto'] = $row['nombre_corto'];
                    $producto['nombre_largo'] = $row['nombre_largo'];
                    $producto['descripcion'] = $row['descripcion'];
                    $producto['precio_unitario'] = $row['precio_unitario'];

                    return $producto;
                }
            }
        } catch (PDOException $e) {
            throw new Exception("ERROR - No se pudo obtener el producto con código {$codigo}: " . $e->getMessage());
        }
    }

    public static function eliminarProducto($codigo)
    {
        try {

            $sql = "DELETE FROM productos WHERE id_producto = ?";
            $connection = DB::getInstance()->getConnection();
            $result = $connection->prepare($sql);

            $bool = $result->execute(array($codigo));

            if ($bool) {
                $filasEliminadas = $result->rowCount();

                return $filasEliminadas;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            throw new Exception("Error al eliminar producto con código {$codigo}: " . $e->getMessage());
        }
    }
}
