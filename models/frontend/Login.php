<?php

namespace ModelsFrontend;

//session_start();

use Config\DB;
use PDO;
use PDOException;
use Exception;

require_once dirname(__DIR__, 2) . '/config/DB.php';

//require_once dirname(__DIR__) . '/views/frontend/login.php';


class Login
{
    private PDO $connection;
    private $bool;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->connection = DB::getInstance()->getConnection();
        $this->bool = false;
    }

    public function comprobarCredencialesAcceso($email_usuario, $pswd_usuario): void
    {
        try {
            //PARA EL REGISTRO DE NUEVOS USUARIOS: usar la función password_hash() para hashear la contraseña y después almacenar el resultado en la DB.
            //PARA LA COMPROBACIÓN DE LA CONTRASEÑA EN EL INICIO DE SESIÓN: como en la DB está almacenado el hash, utilizar password_verify().
            // Verificar que los campos no estén vacíos
            if (empty($email_usuario) || empty($pswd_usuario)) {
                echo "<p style='color:white'>Debe introducir tanto un email como su contraseña.</p>";
            } else {
                // Consulta preparada con parámetros
                $sql = "SELECT id_usuario, email_usuario, password_usuario FROM usuarios WHERE email_usuario = :email_usuario";
                $result = $this->connection->prepare($sql);
                $result->execute([":email_usuario" => $email_usuario]);

                // Recuperar el resultado
                $row = $result->fetch(PDO::FETCH_ASSOC);
                //Como al introducir un usuario o contraseña que no están en la DB el resultado devuelto por $row es false, lo controlamos con el condicional
                /*if ($row != false) {
                    $psswdHash = $row["password_usuario"];
                    $this->bool = true;
                }*/

                // Validación de contraseña
                if (empty($row)) {
                    echo "<p style='color:white'>El nombre de usuario o la contraseña son incorrectos.</p>";
                } else {
                    $psswdHash = $row["password_usuario"];
                    //Este método comprueba si la contraseña introducida por el usuario coincide con el hash almacenado en la DB
                    // Devuelve true si coinciden, false en caso contrario
                    if (password_verify($pswd_usuario, $psswdHash)) {

                        $_SESSION['id_usuario'] = $row['id_usuario'];
                        if ($email_usuario === "admin@admin.com") {
                            header("Location: /views/admin/admin.php");
                        } else {
                            header("Location: ../../home");
                        }

                        exit;
                    } else {
                        echo "<p style='color:white'>La contraseña no es correcta.</p>";
                    }
                }
            }
        } catch (PDOException $e) {
            // Captura cualquier error relacionado con la base de datos
            echo "Error de conexión o consulta: " . $e->getMessage();
        } catch (Exception $e) {
            // Captura otros errores que puedan surgir
            echo "Ocurrió un error inesperado: " . $e->getMessage();
        }
    }
}
