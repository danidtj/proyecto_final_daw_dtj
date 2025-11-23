<?php
namespace ControllerFrontend;

use ModelsFrontend\Login;

require_once dirname(__DIR__, 2) . '/models/frontend/Login.php';

$loginController = new LoginController();
//La vista de Login siempre tiene que ejecutarse independientemente de si se envía o no el formulario.
$loginController->mostrarVistaLogin();
//Una vez se verifica que el formulario ha sido enviado, llamamos al método para comprobar que las credenciales son correctas.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginController->comprobarLogin();
}

class LoginController
{
    /*Método para comprobar que se ha enviado el formulario, almacenar la información del usuario y contraseña 
    en variables y llamar al método de credenciales*/
    public function comprobarLogin()
    {
        if (isset($_POST['user'], $_POST['password'])) {
            //Con htmlspecialchars se evita inyección de código malicioso y se convierte en entidades de HTML.
            $email_usuario = htmlspecialchars($_POST['user'], ENT_QUOTES, 'UTF-8');
            $pswd_usuario = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
        }

        $login = new Login();
        $login->comprobarCredencialesAcceso(trim($email_usuario), trim($pswd_usuario));
    }

    //Método para mostar la vista de Login
    public function mostrarVistaLogin()
    {
        require_once dirname(__DIR__, 2) . '/views/frontend/login.php';
    }
}


