<?php

namespace Config;
// Introducimos el gestor de dependencias a través del archivo autoload.php para cargar cualquier clase sin require
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Importa la clase Dotenv del namespace Dotenv
use Dotenv\Dotenv;
// Importar las clases nativas de PHP
use PDO;
use PDOException;
use Exception;

// Cargar variables de entorno
$envPath = dirname(__DIR__);
if (file_exists($envPath . '/secrets.env')) {
    $dotenv = Dotenv::createImmutable($envPath, 'secrets.env');
    $dotenv->load(); // ← Ahora $_ENV ya está disponible
} else {
    die('.env file not found');
}

//Definición de constantes para las claves de Stripe
define('STRIPE_SECRET_KEY', $_ENV['STRIPE_SECRET_KEY']);
define('STRIPE_PUBLIC_KEY', $_ENV['STRIPE_PUBLIC_KEY']);

// Carga del archivo .env
//$dotenv = Dotenv::createImmutable(dirname(__DIR__));
//$dotenv->load();



class DB
{
    private static ?DB $instance = null; //almacena la instancia única de la clase
    private PDO $connection; //almacena el objeto PDO representante de la conexión a la DB

    private $host;
    private $db;
    private $user;
    private $pass;

    private function __construct() //constructor privado para que no sea accesible desde otro punto del código
    {
        //Con la variable superglobal $_ENV accedemos a las definidas en el archivo .env
        $this->host = $_ENV['DB_HOST'];
        $this->db = $_ENV['DB_DATABASE'];
        $this->user = $_ENV['DB_USERNAME'];
        $this->pass = $_ENV['DB_PASSWORD'];

        try {
            //Preparación de la conexión a la DB
            $this->connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db . ";charset=utf8", $this->user, $this->pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->exec("SET CHARACTER SET utf8");
        } catch (PDOException $e) {
            throw new Exception("Error" . $e->getMessage());
        }
    }


    //Devuelve la instancia si ya está creada o la crea en caso contrario
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    //Devuelve la conexión a la DB
    public function getConnection()
    {
        return $this->connection;
    }
}
