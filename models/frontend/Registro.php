<?php
namespace ModelsFrontend;

@session_start();

use Config\DB;
use PDO;
use PDOException;
use Exception;

require_once dirname(__DIR__, 2) . '/config/DB.php';

class Registro
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = DB::getInstance()->getConnection();
    }
    

    public function realizarRegistroUsuarioNuevo($nom_usuario, $apellidos_usuario, $pswd_usuario, $email_usuario, $telefono_usuario): void
    {
        try {

            if (isset($_POST['registro'])) {

                //PARA EL REGISTRO DE NUEVOS USUARIOS: usar la función password_hash() para hashear la contraseña y después almacenar el resultado en la DB.
                //PARA LA COMPROBACIÓN DE LA CONTRASEÑA EN EL INICIO DE SESIÓN: como en la DB está almacenado el hash, utilizar password_verify().
                // Verificar que los campos no estén vacíos
                if (empty($nom_usuario) || empty($pswd_usuario)) {
                    echo "<p style='color:white'>Debe introducir tanto un nombre de usuario como su contraseña.</p>";
                } else {
                    // Conocer el número de registros afectados por la coincidencia del email de usuario que se quiere registrar
                    $sql = "SELECT COUNT(*) FROM usuarios WHERE email_usuario = :email_usuario";
                    $result = $this->connection->prepare($sql);
                    $result->execute([":email_usuario" => $email_usuario]);

                    // Recuperar el resultado único que devuelve la consulta
                    $count = $result->fetchColumn();

                    if ($count > 0) {
                        // Redirigir al home si es exitoso
                        echo "<p style='color:white'>Este email ya está registrado.</p>";
                    } else {

                        $sql = "INSERT INTO usuarios (id_rol, nombre_usuario, apellidos_usuario, password_usuario, email_usuario, telefono_usuario) VALUES ('1', :nom_usuario, :apellidos_usuario, :psswd, :email_usuario, :telefono_usuario)";
                        $result = $this->connection->prepare($sql);
                        $insert = $result->execute([":nom_usuario" => $nom_usuario, ":apellidos_usuario" => $apellidos_usuario, ":psswd" => password_hash($pswd_usuario, PASSWORD_DEFAULT), ":email_usuario" => $email_usuario, ":telefono_usuario" => $telefono_usuario]);
                        if (!$insert) {
                            echo "<p style='color:white'>El registro no se ha podido completar. Inténtalo de nuevo.</p>";
                        } else {
                            $_SESSION['id_usuario'] = $this->connection->lastInsertId();
                            //Comprobamos que no se hayan enviado previamente los encabezados por HTTP. En caso afirmativo, 
                            //redirecciona mediante código javascript
                            //if (headers_sent()) {
                                //echo "<script> window.location.href = '../../home'; </script>";
                            //} else {
                                header("Location: ". dirname(__DIR__, 2). "/views/frontend/home");
                                exit;
                            //}
                        }
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
