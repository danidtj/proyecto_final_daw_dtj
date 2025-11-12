<?php

namespace ControllerFrontend;

session_start();

use ModelsFrontend\Reserva;
use ModelsFrontend\Orden;
use ModelsAdmin\Producto;

require_once dirname(__DIR__, 2) . '/models/frontend/Reserva.php';
require_once dirname(__DIR__, 2) . '/models/frontend/Orden.php';
require_once dirname(__DIR__, 2) . '/models/admin/Producto.php';
require_once dirname(__DIR__) . '/utilidades/enviarEmail.php';

$reserva = new Reserva();
$orden = new Orden();
class ReservaController
{
    //Método para mostar la vista de Reserva
    public function mostrarVistaReserva()
    {
        require_once dirname(__DIR__, 2) . '/views/frontend/reserva.php';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmarReserva'])) {

    $_SESSION['confirmarReserva'] = true;

    //Creamos la nueva reserva y almacenados su id
    /*$codigo_reserva = $reserva->realizarReserva(
        $_SESSION['fecha'],
        $_SESSION['hora_inicio'],
        $_SESSION['numero_comensales'],
        $_SESSION['comanda_previa'],
        $_POST['mesa_id'],
        $_SESSION['id_usuario']
    );*/

    //Almacenamos el id de la nueva reserva en session
    //$_SESSION['id_reserva_nueva'] = $codigo_reserva;

    if ($_SESSION['comanda_previa'] === "1") {
        $_SESSION['mesa_id'] = $_POST['mesa_id'];
        //Si el usuario ha hecho una comanda previa, redirige a la carta
        header("Location: /views/frontend/carta.php");
        exit();
    } else {
        //Almacenamos el ID de la reserva realizada
        $idReservaEmail = $reserva->realizarReserva(
            $_SESSION['fecha'],
            $_SESSION['hora_inicio'],
            $_SESSION['numero_comensales'],
            $_SESSION['comanda_previa'],
            $_POST['mesa_id'],
            $_SESSION['id_usuario']
        );


        $emailDestinatario = "retorjr@gmail.com";
        $nombreDestinatario = $_SESSION['nombre_usuario'];

        $reservaEmail = $reserva->obtenerReservaPorCodigo($idReservaEmail);

        $contenidoCorreo = "";

        echo $reservaEmail;
        if (!empty($reservaEmail)) {

            $contenidoCorreo = "<h2>Confirmación de su reserva en Restaurante XITO</h2>";
            $contenidoCorreo .= "<p>Estimado/a " . htmlspecialchars($nombreDestinatario) . ",</p>";
            $contenidoCorreo .= "<p>Su reserva con ID <strong>" . htmlspecialchars($reservaEmail['id_reserva']) .
                "</strong> ha sido confirmada con éxito. A continuación, encontrará los detalles de su reserva:</p>";

            //Mostrarle al cliente desde $reservaEmail la fecha de la reserva, la hora, el número de mesa y el de comensales
            $contenidoCorreo .= "<ul>";
            $contenidoCorreo .= "<li>Fecha de la reserva: " . htmlspecialchars($reservaEmail['fecha']) . ".</li>";
            $contenidoCorreo .= "<li>Hora de la reserva: " . htmlspecialchars($reservaEmail['hora_inicio']) . ".</li>";
            $contenidoCorreo .= "<li>Número de mesa: " . htmlspecialchars($reservaEmail['id_mesa']) . ".</li>";
            $contenidoCorreo .= "<li>Número de comensales: " . htmlspecialchars($reservaEmail['numero_comensales']) . ".</li>";
            $contenidoCorreo .= "</ul>";

            $contenidoCorreo .= "<p>Le recordamos que la duración de la reserva es de 1 hora y 30 minutos.</p>";
            $contenidoCorreo .= "<p>Gracias por confiar en Restaurante XITO.</p>";

            $asuntoCorreo = "Confirmación de su reserva en Restaurante XITO";

            $resultadoEmail = enviarEmail($emailDestinatario, $nombreDestinatario, $asuntoCorreo, $contenidoCorreo);
        }



        //Almacenamos el id de la nueva reserva en session
        //$_SESSION['id_reserva_nueva'] = $codigo_reserva;
        //Si no ha hecho comanda previa, redirige a la página principal
        header("Location: /home");
        exit();
    }
}


if (isset($_POST['modificarReserva'])) {

    $_SESSION['id_reserva'] = $_POST['id_reserva'];
    $_SESSION['fecha'] = $_POST['fecha'];
    $_SESSION['hora_inicio'] = $_POST['hora_inicio'];
    $_SESSION['numero_comensales'] = $_POST['numero_comensales'];
    $_SESSION['comanda_previa'] = $_POST['comanda_previa'];
    //$_SESSION['id_mesa'] = $_POST['id_mesa'];
}




if (isset($_POST['confirmarModificacionReserva'])) {

    $_SESSION['confirmarModificacionReserva'] = true;

    $reserva->modificarReserva(
        $_SESSION['id_reserva'],
        $_POST['mesa_id'],
        $_SESSION['fecha'],
        $_SESSION['hora_inicio'],
        $_SESSION['numero_comensales'],
        $_SESSION['comanda_previa']
    );

    $emailDestinatario = $_SESSION['email_usuario'];
    $nombreDestinatario = $_SESSION['nombre_usuario'];

    $reservaEmail = $reserva->obtenerReservaPorCodigo($idReservaEmail);

    $contenidoCorreo = "";

    if (!empty($reservaEmail)) {

        $contenidoCorreo = "<h2>Modificación de su reserva en Restaurante XITO</h2>";
        $contenidoCorreo .= "<p>Estimado/a " . htmlspecialchars($nombreDestinatario) . ",</p>";
        $contenidoCorreo .= "<p>Su reserva con ID <strong>" . htmlspecialchars($reservaEmail['id_reserva']) .
            "</strong> ha sido modificada con éxito. A continuación, encontrará los detalles de su reserva:</p>";

        //Mostrarle al cliente desde $reservaEmail la fecha de la reserva, la hora, el número de mesa y el de comensales
        $contenidoCorreo .= "<ul>";
        $contenidoCorreo .= "<li>Fecha de la reserva: " . htmlspecialchars($reservaEmail['fecha']) . ".</li>";
        $contenidoCorreo .= "<li>Hora de la reserva: " . htmlspecialchars($reservaEmail['hora_inicio']) . ".</li>";
        $contenidoCorreo .= "<li>Número de mesa: " . htmlspecialchars($reservaEmail['id_mesa']) . ".</li>";
        $contenidoCorreo .= "<li>Número de comensales: " . htmlspecialchars($reservaEmail['numero_comensales']) . ".</li>";
        $contenidoCorreo .= "</ul>";

        $contenidoCorreo .= "<p>Le recordamos que la duración de la reserva es de 1 hora y 30 minutos.</p>";
        $contenidoCorreo .= "<p>Gracias por confiar en Restaurante XITO. Esperamos verle pronto.</p>";

        $asuntoCorreo = "Confirmación de su reserva en Restaurante XITO";
    }


    //Redirige a la página del perfil del ususario
    header("Location: /views/frontend/miPerfil.php");
    exit();
}


$reservaController = new ReservaController();
//La vista de Reserva siempre tiene que ejecutarse independientemente de si se envía o no el formulario.
$reservaController->mostrarVistaReserva();
