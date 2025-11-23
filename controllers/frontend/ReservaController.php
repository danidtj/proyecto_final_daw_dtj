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

    public function crearOrdenReservaExistente()
    {
        $reserva = new Reserva();
        $orden = new Orden();

        $stripePaymentId = $_SESSION['stripe_payment_id'] ?? null;

        $reserva->modificarReserva(
            $_SESSION['id_reserva'],
            $_SESSION['mesa_id'],
            $_SESSION['fecha'],
            $_SESSION['hora_inicio'],
            $_SESSION['numero_comensales'],
            $_SESSION['mod_reserva_con_comanda']
        );

        $orden->crearOrden(
            $_SESSION['id_reserva'],
            'Tarjeta de crédito',
            $_SESSION['precioTotalCarrito'],
            $_SESSION['nuevoPagoAdelantado'],
            $stripePaymentId
        );

        

        unset($_SESSION['stripe_payment_id']);
        unset($_SESSION['confirmarModificacionReserva']);
        unset($_SESSION['mesa_id']);
        unset($_SESSION['fecha']);
        unset($_SESSION['hora_inicio']);
        unset($_SESSION['numero_comensales']);
        unset($_SESSION['mod_reserva_con_comanda']);

        $_SESSION['carrito'] = [];
    }

    public function crearOrdenYReserva()
    {
        $reserva = new Reserva();
        $orden = new Orden();
        $codigo_reserva = $reserva->realizarReserva(
            $_SESSION['fecha'],
            $_SESSION['hora_inicio'],
            $_SESSION['numero_comensales'],
            $_SESSION['comanda_previa'],
            $_SESSION['mesa_id'],
            $_SESSION['id_usuario']
        );

        // Almacenamos el id de la nueva reserva en session
        $_SESSION['id_reserva_nueva'] = $codigo_reserva;
        $stripePaymentId = $_SESSION['stripe_payment_id'] ?? null;

        $idOrdenCreada = $orden->crearOrden(
            $_SESSION['id_reserva_nueva'],
            'Tarjeta de crédito',
            $_SESSION['precioTotalCarrito'],
            $_SESSION['nuevoPagoAdelantado'],
            $stripePaymentId
        );

        if (isset($codigo_reserva) && isset($idOrdenCreada)) {
            $_SESSION['codigo_reserva'] = $codigo_reserva;
            $_SESSION['idOrdenCreada'] = $idOrdenCreada;
        }

        unset($_SESSION['fecha']);
        unset($_SESSION['hora_inicio']);
        unset($_SESSION['numero_comensales']);
        unset($_SESSION['comanda_previa']);
        unset($_SESSION['mesa_id']);
        unset($_SESSION['id_reserva_nueva']);
        unset($_SESSION['stripe_payment_id']);
        unset($_SESSION['confirmarReserva']);

        $_SESSION['carrito'] = [];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmarReserva'])) {

    //Almacenamos el número de reservas que el usuario tiene en la fecha seleccionada
    $siUsuarioTieneYaReservaEnFecha = $reserva->usuarioTieneReservaEnFecha($_SESSION['id_usuario'], $_SESSION['fecha']);
    //En caso de tener ya una reserva en esa fecha, mostramos un pop-up indicándoselo
    if ($siUsuarioTieneYaReservaEnFecha) {
?>
        <!-- Pop-up -->
        <div id="popup" class="popup">
            <div class="popup-contenido">
                <span id="cerrar">&times;</span>
                <h2>Reserva no permitida</h2>
                <p>No puedes realizar más de una reserva en la misma fecha.1</p>
                <button id="aceptar">Aceptar</button>
            </div>
        </div>
        <script src="/proyecto_final_daw_dtj/assets/js/popupReserva.js"></script>
        
    <?php
    
    } else {

        $_SESSION['confirmarReserva'] = true;

        if ($_SESSION['comanda_previa'] === "1") {
            $_SESSION['mesa_id'] = $_POST['mesa_id'];
            //Si el usuario ha hecho una comanda previa, redirige a la carta
            header("Location: /proyecto_final_daw_dtj/views/frontend/carta.php");
            exit();
        } else {
            $idReservaEmail = $reserva->realizarReserva(
                $_SESSION['fecha'],
                $_SESSION['hora_inicio'],
                $_SESSION['numero_comensales'],
                $_SESSION['comanda_previa'],
                $_POST['mesa_id'],
                $_SESSION['id_usuario']
            );


            $emailDestinatario = $_SESSION['email_usuario'];
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
                $contenidoCorreo .= "<li>Fecha y hora de la reserva: " . htmlspecialchars($reservaEmail['hora_inicio']) . ".</li>";
                $contenidoCorreo .= "<li>Número de mesa: " . htmlspecialchars($reservaEmail['id_mesa']) . ".</li>";
                $contenidoCorreo .= "<li>Número de comensales: " . htmlspecialchars($reservaEmail['numero_comensales']) . ".</li>";
                $contenidoCorreo .= "</ul>";

                $contenidoCorreo .= "<p>Le recordamos que la duración de la reserva es de 1 hora y 30 minutos.</p>";
                $contenidoCorreo .= "<p>Gracias por confiar en Restaurante XITO.</p>";

                //$asuntoCorreo = "Confirmación de su reserva en Restaurante XITO";
                $asuntoCorreo = "Dentro de RC en el primer asunto.";

                $resultadoEmail = enviarEmail($emailDestinatario, $nombreDestinatario, $asuntoCorreo, $contenidoCorreo);
            }

            unset(
                $_SESSION['fecha'],
                $_SESSION['hora_inicio'],
                $_SESSION['numero_comensales'],
                $_SESSION['comanda_previa']
            );

            //Almacenamos el id de la nueva reserva en session
            //$_SESSION['id_reserva_nueva'] = $codigo_reserva;
            //Si no ha hecho comanda previa, redirige a la página principal
            header("Location: /proyecto_final_daw_dtj/views/frontend/miPerfil.php");
            exit();
        }
    }
}


if (isset($_POST['modificarReserva'])) {

    $_SESSION['id_reserva'] = $_POST['id_reserva'];
    $_SESSION['fecha'] = $_POST['fecha'];
    $_SESSION['hora_inicio'] = $_POST['hora_inicio'];
    $_SESSION['numero_comensales'] = $_POST['numero_comensales'];

    if ($_POST['comanda_previa'] == "1") {
        $_SESSION['mod_reserva_con_comanda'] = "1";
        $_SESSION['mod_reserva_con_comanda_original'] = "1";
    } else {
        $_SESSION['mod_reserva_sin_comanda'] = "0";
    }
    //$_SESSION['id_mesa'] = $_POST['id_mesa'];
}


if (isset($_POST['confirmarModificacionReserva'])) {

    $_SESSION['confirmarModificacionReserva'] = true;

    $siUsuarioTieneYaReserva = $reserva->obtenerReservasUsuarioEnFechaConcreta($_SESSION['id_usuario'], $_SESSION['fecha']);

    // Convertimos a lista única de IDs
    $idsReserva = array_unique(array_column($siUsuarioTieneYaReserva, 'id_reserva'));

    //Tiene otra reserva distinta a la que quiere modificar en esa fecha
    if (count($idsReserva) > 1 || (count($idsReserva) == 1 && $idsReserva[0] != $_SESSION['id_reserva'])) {
    ?>
        <div id="popup-modificar-reserva" class="popup">
            <div class="popup-contenido">
                <span id="cerrar-modificar-reserva">&times;</span>
                <h2>Reserva no permitida</h2>
                <p>No puedes realizar más de una reserva en la misma fecha.2</p>
                <button id="aceptar-modificar-reserva">Aceptar</button>
            </div>
        </div>
        <script src="/proyecto_final_daw_dtj/assets/js/popupModificarReserva.js"></script>
<?php
        exit();
    }

    //La reserva a modificar es la misma que la que ya tiene en esa fecha
    if (count($idsReserva) == 1 && $idsReserva[0] == $_SESSION['id_reserva']) {

        if (isset($_SESSION['mod_reserva_con_comanda']) && $_SESSION['mod_reserva_con_comanda'] == "1") {
            $_SESSION['mesa_id'] = $_POST['mesa_id'];
            header("Location: /proyecto_final_daw_dtj/views/frontend/carta.php");
            exit();
        } elseif (isset($_SESSION['mod_reserva_sin_comanda']) && $_SESSION['mod_reserva_sin_comanda'] == "0") {

            $reserva->modificarReserva(
                $_SESSION['id_reserva'],
                $_POST['mesa_id'],
                $_SESSION['fecha'],
                $_SESSION['hora_inicio'],
                $_SESSION['numero_comensales'],
                $_SESSION['mod_reserva_sin_comanda']
            );

            unset($_SESSION['fecha'], $_SESSION['hora_inicio'], $_SESSION['numero_comensales'], $_SESSION['mod_reserva_sin_comanda']);
        } elseif (isset($_SESSION['mod_reserva_con_comanda_original']) && $_SESSION['mod_reserva_con_comanda_original'] == "1") {

            $reserva->modificarReserva(
                $_SESSION['id_reserva'],
                $_POST['mesa_id'],
                $_SESSION['fecha'],
                $_SESSION['hora_inicio'],
                $_SESSION['numero_comensales'],
                $_SESSION['mod_reserva_con_comanda_original']
            );

            unset(
                $_SESSION['fecha'],
                $_SESSION['hora_inicio'],
                $_SESSION['numero_comensales'],
                $_SESSION['mod_reserva_con_comanda'],
                $_SESSION['mod_reserva_con_comanda_original']
            );
        }
    }

    //Si no tiene ninguna reserva en esa fecha
    if (count($idsReserva) == 0) {

        if (isset($_SESSION['mod_reserva_con_comanda']) && $_SESSION['mod_reserva_con_comanda'] == "1") {
            $_SESSION['mesa_id'] = $_POST['mesa_id'];
            header("Location: /proyecto_final_daw_dtj/views/frontend/carta.php");
            exit();
        } elseif (isset($_SESSION['mod_reserva_sin_comanda']) && $_SESSION['mod_reserva_sin_comanda'] == "0") {

            $reserva->modificarReserva(
                $_SESSION['id_reserva'],
                $_POST['mesa_id'],
                $_SESSION['fecha'],
                $_SESSION['hora_inicio'],
                $_SESSION['numero_comensales'],
                $_SESSION['mod_reserva_sin_comanda']
            );

            unset($_SESSION['fecha'], $_SESSION['hora_inicio'], $_SESSION['numero_comensales'], $_SESSION['mod_reserva_sin_comanda']);
        } elseif (isset($_SESSION['mod_reserva_con_comanda_original']) && $_SESSION['mod_reserva_con_comanda_original'] == "1") {

            $reserva->modificarReserva(
                $_SESSION['id_reserva'],
                $_POST['mesa_id'],
                $_SESSION['fecha'],
                $_SESSION['hora_inicio'],
                $_SESSION['numero_comensales'],
                $_SESSION['mod_reserva_con_comanda_original']
            );

            unset(
                $_SESSION['fecha'],
                $_SESSION['hora_inicio'],
                $_SESSION['numero_comensales'],
                $_SESSION['mod_reserva_con_comanda'],
                $_SESSION['mod_reserva_con_comanda_original']
            );
        }
    }

    $emailDestinatario = $_SESSION['email_usuario'];
    $nombreDestinatario = $_SESSION['nombre_usuario'];

    $datosReserva = $reserva->obtenerReservaPorCodigo($_SESSION['id_reserva']);

    $contenidoCorreo = "";

    if (!empty($datosReserva)) {
        $contenidoCorreo = "<h2>Modificación de su reserva en Restaurante XITO</h2>";
        $contenidoCorreo .= "<p>Estimado/a " . htmlspecialchars($nombreDestinatario) . ",</p>";
        $contenidoCorreo .= "<p>Su reserva con ID <strong>" . htmlspecialchars($datosReserva['id_reserva']) .
            "</strong> ha sido modificada con éxito. A continuación, encontrará los detalles de su reserva:</p>";

        //Mostrarle al cliente desde $reservaEmail la fecha de la reserva, la hora, el número de mesa y el de comensales
        $contenidoCorreo .= "<ul>";
        //$contenidoCorreo .= "<li>Fecha de la reserva: " . htmlspecialchars($reservaEmail['fecha']) . ".</li>";
        $contenidoCorreo .= "<li>Fecha y hora de la reserva: " . htmlspecialchars($datosReserva['hora_inicio']) . ".</li>";
        $contenidoCorreo .= "<li>Número de mesa: " . htmlspecialchars($datosReserva['id_mesa']) . ".</li>";
        $contenidoCorreo .= "<li>Número de comensales: " . htmlspecialchars($datosReserva['numero_comensales']) . ".</li>";
        $contenidoCorreo .= "</ul>";

        $contenidoCorreo .= "<p>Le recordamos que la duración de la reserva es de 1 hora y 30 minutos.</p>";
        $contenidoCorreo .= "<p>Gracias por confiar en Restaurante XITO. Esperamos verle pronto.</p>";

        //$asuntoCorreo = "Confirmación de su reserva en Restaurante XITO";
        $asuntoCorreo = "Dentro de RC en el segundo asunto.";

        enviarEmail($emailDestinatario, $nombreDestinatario, $asuntoCorreo, $contenidoCorreo);
    }
    unset($_SESSION['fecha']);
    unset($_SESSION['hora_inicio']);
    unset($_SESSION['numero_comensales']);
    unset($_SESSION['mod_reserva_con_comanda']);

    //Redirige a la página del perfil del ususario
    header("Location: /proyecto_final_daw_dtj/views/frontend/miPerfil.php");
    exit();
    //}
    //}
}


$reservaController = new ReservaController();
//La vista de Reserva siempre tiene que ejecutarse independientemente de si se envía o no el formulario.
$reservaController->mostrarVistaReserva();

