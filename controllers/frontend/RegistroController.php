<?php

namespace ControllerFrontend;

use ModelsFrontend\Registro;
use ModelsFrontend\Usuario;

require_once dirname(__DIR__, 2) . '/models/frontend/Registro.php';

$registroController = new RegistroController();
//La vista de Login siempre tiene que ejecutarse independientemente de si se envía o no el formulario.
$registroController->mostrarVistaRegistro();
//Una vez se verifica que el formulario ha sido enviado, llamamos al método para comprobar que las credenciales son correctas.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registroController->comprobarRegistro();
}

class RegistroController
{

    public function comprobarRegistro()
    {
        if (isset($_POST['nombre_usuario'], $_POST['password_usuario'])) {
            //Con htmlspecialchars se evita inyección de código malicioso y se convierte en entidades de HTML.
            $nom_usuario = htmlspecialchars($_POST['nombre_usuario'], ENT_QUOTES, 'UTF-8');
            $apellidos_usuario = htmlspecialchars($_POST['apellidos_usuario'], ENT_QUOTES, 'UTF-8');
            $email_usuario = htmlspecialchars($_POST['email_usuario'], ENT_QUOTES, 'UTF-8');
            $pswd_usuario = htmlspecialchars($_POST['password_usuario'], ENT_QUOTES, 'UTF-8');

            $nuevoUsuario = new Usuario(trim($nom_usuario), trim($apellidos_usuario), trim($pswd_usuario), trim($email_usuario));

            $registro = new Registro();
            $registro->realizarRegistroUsuarioNuevo($nuevoUsuario->getNombreUsuario(), $nuevoUsuario->getApellidosUsuario(), $nuevoUsuario->getPasswordUsuario(), $nuevoUsuario->getEmailUsuario());
        }
    }

    //Método para mostar la vista de Registro
    public function mostrarVistaRegistro()
    {
        require_once dirname(__DIR__, 2) . '/views/frontend/registro.php';
    }
}
