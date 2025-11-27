<?php

namespace ModelsAdmin;

use Config\DB;
use PDO;
use Exception;
use PDOException;

require_once dirname(__DIR__, 2) . '/config/DB.php';

class Categoria
{
    private int $id_categoria;
    private string $nombre_categoria;
    private string $tipo_categoria;
    private string $modalidad_categoria;

    public array $categorias = [];

    public PDO $connection;

    //constructor sin parámetros
    public function __construct(array $categorias)
    {

        $this->id_categoria = $categorias['id_categoria'];
        $this->nombre_categoria = $categorias['nombre_categoria'];
        $this->tipo_categoria = $categorias['tipo_categoria'];
        $this->modalidad_categoria = $categorias['modalidad_producto'];

        $this->connection = DB::getInstance()->getConnection();

        $this->categorias = $categorias;
    }

    //Metodos getters

    public function getIdCategoria(): int
    {
        return $this->id_categoria;
    }

    public function getNombreCategoria(): string
    {
        return $this->nombre_categoria;
    }

    public function getTipoCategoria(): string
    {
        return $this->tipo_categoria;
    }

    public function getModalidadCategoria(): string
    {
        return $this->modalidad_categoria;
    }

    //Método para obtener las categorias de la base de datos
    public static function obtenerCategorias(): array
    {
        try {
            $connection = DB::getInstance()->getConnection();
            $sql = "SELECT * FROM categorias";
            $stmt = $connection->prepare($sql);
            $stmt->execute();

            $categoriasDatos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $categorias = [];

            foreach ($categoriasDatos as $categoriaDato) {
                $categorias[] = new Categoria($categoriaDato);
            }

            return $categorias;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener las categorías: " . $e->getMessage());
        }
    }
}
