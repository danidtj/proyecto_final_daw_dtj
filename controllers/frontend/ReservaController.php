<?php

namespace ControllerFrontend;

session_start();

use ModelsFrontend\Reserva;
use ModelsFrontend\Orden;

require_once dirname(__DIR__, 2) . '/models/frontend/Reserva.php';
require_once dirname(__DIR__, 2) . '/models/frontend/Orden.php';

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
    $codigo_reserva = $reserva->realizarReserva(
        $_SESSION['fecha'],
        $_SESSION['hora_inicio'],
        $_SESSION['numero_comensales'],
        $_SESSION['comanda_previa'],
        $_POST['mesa_id'],
        $_SESSION['id_usuario']
    );

    //Almacenamos el id de la nueva reserva en session
    $_SESSION['id_reserva_nueva'] = $codigo_reserva;

    if ($_SESSION['comanda_previa'] === "1") {
        //Si el usuario ha hecho una comanda previa, redirige a la carta
        header("Location: /views/frontend/carta.php");
        exit();
    } else {
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
    $_SESSION['id_mesa'] = $_POST['id_mesa'];
}




if (isset($_POST['confirmarModificacionReserva'])) {

    $_SESSION['confirmarModificacionReserva'] = true;

    $reserva->modificarReserva(
        $_SESSION['id_reserva'],
        $_SESSION['id_mesa'],
        $_SESSION['fecha'],
        $_SESSION['hora_inicio'],
        $_SESSION['numero_comensales'],
        $_SESSION['comanda_previa']
    );

    if ($_SESSION['comanda_previa'] === "1") {

        //Si el usuario ha hecho una comanda previa, redirige a la carta
        header("Location: /views/frontend/carta.php");
        exit();
    } else {

        //Si no ha hecho comanda previa, redirige a la página principal
        header("Location: /home");
        exit();
    }
}

$reservaController = new ReservaController();
//La vista de Reserva siempre tiene que ejecutarse independientemente de si se envía o no el formulario.
$reservaController->mostrarVistaReserva();
