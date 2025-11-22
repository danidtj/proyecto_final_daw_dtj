<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {

    header("Location: /proyecto_final_daw_dtj/views/frontend/index.php");
    exit;
}

use ModelsFrontend\Reserva;
use ModelsFrontend\Orden;
use ModelsAdmin\Producto;

require_once dirname(__DIR__, 2) . '/models/frontend/Reserva.php';
require_once dirname(__DIR__, 2) . '/models/frontend/Orden.php';
require_once dirname(__DIR__, 2) . '/models/admin/Producto.php';
require_once dirname(__DIR__, 2) . '/controllers/utilidades/enviarEmail.php';

$orden = new Orden();
$nuevaReserva = new Reserva();

//Almacenamos las reservas realizadas por el usuario que ha iniciado sesión
$reservasUsuario = [];
if (isset($_SESSION['id_usuario'])) {
    $reservasUsuario = $nuevaReserva->obtenerReservasPorUsuario($_SESSION['id_usuario']);
}

//Comprobamos si la reserva recién creada tiene una orden asociada para enviar el correo de confirmación
if (isset($_SESSION['idOrdenCreada']) && isset($_SESSION['codigo_reserva'])) {

    // Envío de correo de confirmación
    $emailDestinatario = $_SESSION['email_usuario'] ?? '';
    $nombreDestinatario = $_SESSION['nombre_usuario'] ?? '';

    $ordenEmail = $orden->obtenerOrdenPorCodigoReserva($_SESSION['codigo_reserva']);
    $reservaEmail = $nuevaReserva->obtenerReservaPorCodigo($_SESSION['codigo_reserva']);
    $productosOrdenEmail = Producto::obtenerProductosReservaOrden($_SESSION['id_usuario'], $_SESSION['codigo_reserva'], $_SESSION['idOrdenCreada']);

    if (!empty($ordenEmail) && !empty($reservaEmail) && !empty($productosOrdenEmail)) {

        $contenidoCorreo = "<h2>Confirmación de su reserva en Restaurante XITO</h2>";
        $contenidoCorreo .= "<p>Estimado/a " . htmlspecialchars($nombreDestinatario) . ",</p>";
        $contenidoCorreo .= "<p>Le informamos de que el ID de su reserva es <strong>" . htmlspecialchars($reservaEmail['id_reserva']) .
            "</strong>. A continuación, encontrará los detalles:</p>";

        //Mostrarle al cliente los detalles de su reserva
        $contenidoCorreo .= "<ul>";
        $contenidoCorreo .= "<li>Fecha y hora de la reserva: " . htmlspecialchars($reservaEmail['hora_inicio']) . ".</li>";
        $contenidoCorreo .= "<li>Número de mesa: " . htmlspecialchars($reservaEmail['id_mesa']) . ".</li>";
        $contenidoCorreo .= "<li>Número de comensales: " . htmlspecialchars($reservaEmail['numero_comensales']) . ".</li>";
        $contenidoCorreo .= "</ul>";
        $contenidoCorreo .= "<ul>";

        //Muestra al cliente los productos asociados a su orden
        foreach ($productosOrdenEmail as $producto) {
            $contenidoCorreo .= "<li>" . htmlspecialchars($producto['nombre_corto']) . " - Cantidad: " . htmlspecialchars($producto['cantidad_pedido']) .
                " - Precio Unitario: " . htmlspecialchars(number_format($producto['precio_unitario'], 2, ',', '.')) . " €.</li>";
        }

        $contenidoCorreo .= "</ul>";
        $contenidoCorreo .= "<p>Total de la orden: " . htmlspecialchars(number_format($ordenEmail['precio_total'], 2, ',', '.')) . " €.</p>";
        $contenidoCorreo .= "<p>Pago adelantado (10%): " . htmlspecialchars(number_format($ordenEmail['precio_total'] * 0.1, 2, ',', '.')) . " €.</p>";
        $contenidoCorreo .= "<p>Gracias por confiar en Restaurante XITO. Esperamos verle pronto.</p>";
        //$asuntoCorreo = "Confirmación de su reserva en Restaurante XITO";
        $asuntoCorreo = "Dentro de miPerfil el primer asunto.";

        //Enviar el correo
        enviarEmail($emailDestinatario, $nombreDestinatario, $asuntoCorreo, $contenidoCorreo);
    }

    unset($_SESSION['idOrdenCreada']);
    unset($_SESSION['codigo_reserva']);
    unset($_SESSION['carrito']);
    unset($_SESSION['stripe_payment_id']);
}

//Comprobamos si la reserva modificada tiene una orden asociada para enviar el correo de modificación
if (isset($_SESSION['idReservaModificar']) && isset($_SESSION['idOrdenModificar'])) {

    // Envío de correo de confirmación
    $emailDestinatario = $_SESSION['email_usuario'] ?? '';
    $nombreDestinatario = $_SESSION['nombre_usuario'] ?? '';

    $ordenEmail = $orden->obtenerOrdenPorCodigoReserva($_SESSION['idReservaModificar']);
    $reservaEmail = $nuevaReserva->obtenerReservaPorCodigo($_SESSION['idReservaModificar']);
    $productosOrdenEmail = Producto::obtenerProductosReservaOrden($_SESSION['id_usuario'], $_SESSION['idReservaModificar'], $_SESSION['idOrdenModificar']);

    if (!empty($ordenEmail) && !empty($reservaEmail) && !empty($productosOrdenEmail)) {

        $contenidoCorreo = "<h2>Modificación de su orden en Restaurante XITO</h2>";
        $contenidoCorreo .= "<p>Estimado/a " . htmlspecialchars($nombreDestinatario) . ",</p>";
        $contenidoCorreo .= "<p>Su orden asociada a la reserva con ID <strong>" . htmlspecialchars($reservaEmail['id_reserva']) .
            "</strong> ha sido modificada con éxito. A continuación, encontrará los detalles actualizados de su orden:</p>";
        $contenidoCorreo .= "<ul>";
        foreach ($productosOrdenEmail as $producto) {
            $contenidoCorreo .= "<li>" . htmlspecialchars($producto['nombre_corto']) . " - Cantidad: " . htmlspecialchars($producto['cantidad_pedido']) .
                " - Precio Unitario: " . htmlspecialchars(number_format($producto['precio_unitario'], 2, ',', '.')) . " €.</li>";
        }
        $contenidoCorreo .= "</ul>";
        $contenidoCorreo .= "<p>Total de la orden: " . htmlspecialchars(number_format($ordenEmail['precio_total'], 2, ',', '.')) . " €.</p>";
        $contenidoCorreo .= "<p>Pago adelantado (10%): " . htmlspecialchars(number_format($ordenEmail['precio_total'] * 0.1, 2, ',', '.')) . " €.</p>";
        $contenidoCorreo .= "<p>Le recordamos que la devolución de su anterior pago se realizará en un plazo de 5-7 días hábiles.</p>";
        $contenidoCorreo .= "<p>Gracias por confiar en Restaurante XITO. Esperamos verle pronto.</p>";
        //$asuntoCorreo = "Modificación de su orden en Restaurante XITO";
        $asuntoCorreo = "Dentro de miPerfil el segundo asunto.";

        enviarEmail($emailDestinatario, $nombreDestinatario, $asuntoCorreo, $contenidoCorreo);
    }

    unset($_SESSION['idReservaModificar']);
    unset($_SESSION['idOrdenModificar']);
    unset($_SESSION['carrito']);
    unset($_SESSION['stripe_payment_id']);
}

if (isset($_SESSION['email_nueva_orden']) && $_SESSION['email_nueva_orden'] === true) {
    // Envío de correo de confirmación
    $emailDestinatario = $_SESSION['email_usuario'] ?? '';
    $nombreDestinatario = $_SESSION['nombre_usuario'] ?? '';

    $ordenEmail = $orden->obtenerOrdenPorCodigoReserva($_SESSION['id_reserva']);
    $reservaEmail = $nuevaReserva->obtenerReservaPorCodigo($_SESSION['id_reserva']);
    $productosOrdenEmail = Producto::obtenerProductosReservaOrden($_SESSION['id_usuario'], $_SESSION['id_reserva'], $ordenEmail['id_orden']);

    if (!empty($ordenEmail) && !empty($reservaEmail) && !empty($productosOrdenEmail)) {

        $contenidoCorreo = "<h2>Modificación de su orden en Restaurante XITO</h2>";
        $contenidoCorreo .= "<p>Estimado/a " . htmlspecialchars($nombreDestinatario) . ",</p>";
        $contenidoCorreo .= "<p>Su orden asociada a la reserva con ID <strong>" . htmlspecialchars($reservaEmail['id_reserva']) .
            "</strong> ha sido modificada con éxito. A continuación, encontrará los detalles actualizados de su orden:</p>";
        $contenidoCorreo .= "<ul>";
        foreach ($productosOrdenEmail as $producto) {
            $contenidoCorreo .= "<li>" . htmlspecialchars($producto['nombre_corto']) . " - Cantidad: " . htmlspecialchars($producto['cantidad_pedido']) .
                " - Precio Unitario: " . htmlspecialchars(number_format($producto['precio_unitario'], 2, ',', '.')) . " €.</li>";
        }
        $contenidoCorreo .= "</ul>";
        $contenidoCorreo .= "<p>Total de la orden: " . htmlspecialchars(number_format($ordenEmail['precio_total'], 2, ',', '.')) . " €.</p>";
        $contenidoCorreo .= "<p>Pago adelantado (10%): " . htmlspecialchars(number_format($ordenEmail['precio_total'] * 0.1, 2, ',', '.')) . " €.</p>";
        $contenidoCorreo .= "<p>Le recordamos que la devolución de su anterior pago se realizará en un plazo de 5-7 días hábiles.</p>";
        $contenidoCorreo .= "<p>Gracias por confiar en Restaurante XITO. Esperamos verle pronto.</p>";
        //$asuntoCorreo = "Modificación de su orden en Restaurante XITO";
        $asuntoCorreo = "Dentro de miPerfil el XXXXXX asunto.";

        enviarEmail($emailDestinatario, $nombreDestinatario, $asuntoCorreo, $contenidoCorreo);
    }

    unset($_SESSION['id_reserva']);
    unset($_SESSION['carrito']);
    unset($_SESSION['stripe_payment_id']);
    unset($_SESSION['email_nueva_orden']);
}

//Comprobamos si se quiere modificar una reserva con orden asociada
if (isset($_POST['modificarOrden']) && !empty($_POST['id_orden']) && !empty($_POST['id_reserva'])) {

    $_SESSION['carrito'] = [];
    $_SESSION['modificar_orden'] = true;

    //Almacenamos los productos de la orden
    $recuperarOrden = $orden->obtenerProductosPorOrden($_POST['id_orden']);

    foreach ($recuperarOrden as $productoOrden) {
        //Datos del producto
        $datosProducto = Producto::getUnProducto($productoOrden['id_producto']);
        if (!empty($datosProducto)) {
            foreach (range(1, $productoOrden['cantidad_pedido']) as $i) {

                $_SESSION['carrito'][] = [
                    'id_producto' => $productoOrden['id_producto'],
                    //'cantidad_pedido' => $productoOrden['cantidad_pedido'],
                    //'id_orden' => $productoOrden['id_orden'],
                    'nombre_corto' => $datosProducto['nombre_corto'],
                    'precio_unitario' => $datosProducto['precio_unitario'],
                    //'id_reserva' => $_POST['id_reserva']
                ];

                $_SESSION['orden_original'][] = [
                    'cantidad_pedido' => $productoOrden['cantidad_pedido'],
                    'id_orden' => $productoOrden['id_orden'],
                    'id_reserva' => $_POST['id_reserva']
                ];
            }
        }
    }

    header("Location: /proyecto_final_daw_dtj/views/frontend/carrito.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Restaurante XITO</title>
</head>


<body>
    <?php include_once __DIR__ . '/../partials/header.php'; ?>
    <main>
        <section class="container_form perfil_container_reservas">
            <h2 class="titulo_form perfil_titulo">Resumen de mis reservas</h2>
            <?php
            if (!empty($reservasUsuario)) {

                foreach ($reservasUsuario as $reserva) {
                    echo "
                    <div class='reserva_detalles perfil_reserva_detalles'>
                    <div class='cabecera_reserva perfil_cabecera_reserva'>
                            <span><strong>Código:</strong> " . htmlspecialchars($reserva['id_reserva']) . "</span>                            
                            <span><strong>Fecha:</strong> " . htmlspecialchars(date('d/m/Y', strtotime($reserva['fecha']))) . "</span>
                            <span><strong>Hora:</strong> " . htmlspecialchars($reserva['hora_inicio']) . "</span>
                            <span><strong>Mesa:</strong> " . htmlspecialchars($reserva['id_mesa']) . "</span>";
                    if ($reserva['comanda_previa'] == 1) {
                        echo " <span><strong>Orden: </strong>Sí</span> ";
                    } else {
                        echo " <span><strong>Orden: </strong>No</span> ";
                    }
                    echo "
                            <span><strong>Comensales:</strong> " . htmlspecialchars($reserva['numero_comensales']) . "</span></div>
                          ";

                    $ordenes = $orden->obtenerOrdenPorCodigoReserva($reserva['id_reserva']);
                    if (!empty($ordenes)) {
                        echo "<div class='productos_reserva perfil_productos_reserva'>";
                        $productosOrden = Producto::obtenerProductosReservaOrden($_SESSION['id_usuario'], $reserva['id_reserva'], $ordenes['id_orden']);

                        foreach ($productosOrden as $producto) {
                            echo "<p>" . htmlspecialchars($producto['nombre_corto']) . " ..... " . number_format(htmlspecialchars($producto['precio_unitario']), 2, ',', '.') . " € ..... " .
                                htmlspecialchars($producto['cantidad_pedido']) . " u ..... " . number_format(htmlspecialchars($producto['precio_unitario']) * htmlspecialchars($producto['cantidad_pedido']), 2, ',', '.') . " €</p>";
                        }
                        echo "<br><p><strong>Precio total: " . number_format($ordenes['precio_total'], 2, ',', '.') . " €</strong></p>";
                        echo "<p><strong>Montante adelantado (10%): " . number_format($ordenes['montante_adelantado'], 2, ',', '.') . " €</strong></p><br>";
                        //echo "<p>Número de mesa: " . htmlspecialchars($ordenes['id_mesa']) . "</p>";

                        echo "</div>";
                    }
                    $idReservasHoraInicioUsuario = $nuevaReserva->obtenerReservasAnterioresHoraInicioPorUsuario($_SESSION['id_usuario']);
                    $idReservasActivasUsuario = $nuevaReserva->obtenerReservasActivasPorUsuario($_SESSION['id_usuario']);
                    $idReservasActivasPorFecha = $nuevaReserva->obtenerReservasActivasFechaActualPorUsuario($_SESSION['id_usuario']);

                    if (!(in_array($reserva['id_reserva'], array_column($idReservasHoraInicioUsuario, 'id_reserva')))) {



                        echo "<div class=\"botones perfil_botones_reserva\">";
                        // Formulario para cancelar la reserva 
                        echo "<form action=\"" . htmlspecialchars($_SERVER['PHP_SELF']) . "\" method=\"post\">";
                        echo "<input type=\"hidden\" name=\"id_reserva\" value=\"" . htmlspecialchars($reserva['id_reserva']) . "\">";
                        echo "<input type=\"hidden\" name=\"comanda_previa\" value=\"" . htmlspecialchars($reserva['comanda_previa']) . "\">";
                        if (!empty($ordenes)) {
                            echo "<input type=\"hidden\" name=\"id_orden\" value=\"" . htmlspecialchars($ordenes['id_orden']) . "\">";
                        }
                        echo "<input type=\"submit\" class=\"botones btn_cancelar\" value=\"Cancelar reserva\" name=\"cancelarReserva\">";
                        echo "</form>";


                        // Formulario para modificar la reserva 
                        echo "<form action=\"/proyecto_final_daw_dtj/controllers/frontend/ReservaController.php\" method=\"post\">";
                        echo "<input type=\"hidden\" name=\"id_reserva\" value=\"" . htmlspecialchars($reserva['id_reserva']) . "\">";
                        echo "<input type=\"hidden\" name=\"fecha\" value=\"" . htmlspecialchars($reserva['fecha']) . "\">";
                        echo "<input type=\"hidden\" name=\"hora_inicio\" value=\"" . htmlspecialchars($reserva['hora_inicio']) . "\">";
                        echo "<input type=\"hidden\" name=\"numero_comensales\" value=\"" . htmlspecialchars($reserva['numero_comensales']) . "\">";
                        echo "<input type=\"hidden\" name=\"comanda_previa\" value=\"" . htmlspecialchars($reserva['comanda_previa']) . "\">";
                        // echo "<input type=\"hidden\" name=\"id_mesa\" value=\"" . htmlspecialchars($reserva['id_mesa']) . "\">";
                        echo "<input type=\"submit\" class=\"botones btn_modificar\" value=\"Modificar reserva\" name=\"modificarReserva\">";
                        echo "</form>";
                    }

                    if (!empty($ordenes)) {

                        $mostrarBoton = false;
                        $botonModificacionMostrado = false; // <- evita duplicados

                        // --- Reservas activas por fecha ---
                        if (in_array($reserva['id_reserva'], array_column($idReservasActivasPorFecha, 'id_reserva'))) {
                            $mostrarBoton = true;

                            if (
                                ($mostrarBoton && $reserva['hora_inicio'] > date('H:i:s') && $reserva['fecha'] >= date('Y-m-d'))
                                || $reserva['fecha'] > date('Y-m-d')
                            ) {

                                // Botón para cancelar la orden
                                echo "<form action=\"" . htmlspecialchars($_SERVER['PHP_SELF']) . "\" method=\"post\">";
                                echo "<input type=\"hidden\" name=\"id_reserva\" value=\"" . htmlspecialchars($ordenes['id_reserva']) . "\">";
                                echo "<input type=\"hidden\" name=\"id_orden\" value=\"" . htmlspecialchars($ordenes['id_orden']) . "\">";
                                echo "<input type=\"submit\" class=\"botones btn_cancelar\" value=\"Cancelar orden\" name=\"cancelarOrden\">";
                                echo "</form>";

                                // Tiene en cuenta la fecha y hora de la reserva para mostrar el botón de modificar orden
                                $hoy = date('Y-m-d');
                                $ahora = date('H:i:s');

                                $mostrarAntesDeInicio =
                                    ($reserva['fecha'] > $hoy) ||
                                    ($reserva['fecha'] === $hoy && $reserva['hora_inicio'] > $ahora);

                                // Formulario para modificar la orden
                                if ($mostrarBoton && $mostrarAntesDeInicio && !$botonModificacionMostrado) {

                                    echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="post">';
                                    echo "<input type=\"hidden\" name=\"id_reserva\" value=\"" . htmlspecialchars($reserva['id_reserva']) . "\">";
                                    echo "<input type=\"hidden\" name=\"id_orden\" value=\"" . htmlspecialchars($ordenes['id_orden']) . "\">";
                                    echo "<input type=\"submit\" class=\"botones btn_modificar\" value=\"Modificar orden\" name=\"modificarOrden\">";
                                    echo "</form>";

                                    $botonModificacionMostrado = true;
                                }
                            }
                        }

                        // Reservas activas por usuario (entre hora inicio y fin)
                        if (in_array($reserva['id_reserva'], array_column($idReservasActivasUsuario, 'id_reserva'))) {
                            $mostrarBoton = true;
                        }

                        //Mostramos el botón una sola vez
                        if (
                            $mostrarBoton && !$botonModificacionMostrado && $reserva['hora_fin'] > date('H:i:s') &&
                            $reserva['fecha'] >= date('Y-m-d')
                        ) {

                            // Formulario para modificar la orden
                            echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="post">';
                            echo "<input type=\"hidden\" name=\"id_reserva\" value=\"" . htmlspecialchars($reserva['id_reserva']) . "\">";
                            echo "<input type=\"hidden\" name=\"id_orden\" value=\"" . htmlspecialchars($ordenes['id_orden']) . "\">";
                            echo "<input type=\"submit\" class=\"botones btn_modificar\" value=\"Modificar orden\" name=\"modificarOrden\">";
                            echo "</form>";

                            $botonModificacionMostrado = true;
                        }
                    }


                    echo "</div>
                    </div>
                    <br>";
                }
            } else {
                echo "<p>No tienes reservas realizadas.</p>";
            }
            //Cancelar una reserva
            if (isset($_POST['cancelarReserva'])) {

                $emailDestinatario = $_SESSION['email_usuario'];
                $nombreDestinatario = $_SESSION['nombre_usuario'];


                $ordenEmail = $orden->obtenerOrdenPorCodigoReserva($_POST['id_reserva']);
                $reservaEmail = $nuevaReserva->obtenerReservaPorCodigo($_POST['id_reserva']);

                if (!empty($ordenEmail)) {
                    $orden->reembolsarOrden($_POST['id_reserva']);
                }

                $contenidoCorreo = "";

                if (!empty($reservaEmail)) {

                    $productosAddStock = Producto::obtenerProductosReservaOrden($_SESSION['id_usuario'], $_POST['id_reserva'], $_POST['id_orden']);
                    if (!empty($productosAddStock) && isset($_POST['comanda_previa']) && $_POST['comanda_previa'] == 1) {
                        Producto::incrementarStockPorCancelacion($productosAddStock);
                    }

                    $nuevaReserva->cancelarReserva($_POST['id_reserva']);

                    $contenidoCorreo = "<h2>Cancelación de su reserva en Restaurante XITO</h2>";
                    $contenidoCorreo .= "<p>Estimado/a " . htmlspecialchars($nombreDestinatario) . ",</p>";
                    $contenidoCorreo .= "<p>Su reserva con ID <strong>" . htmlspecialchars($reservaEmail['id_reserva']) .
                        "</strong> ha sido cancelada.</p>";

                    if (!empty($ordenEmail)) {
                        $contenidoCorreo .= "<p>Le recordamos que la devolución de su pago se realizará en un plazo de 5-7 días hábiles.</p>";
                    }

                    $contenidoCorreo .= "<p>Gracias por confiar en Restaurante XITO. Esperamos verle pronto.</p>";

                    //$asuntoCorreo = "Cancelación de su reserva en Restaurante XITO";
                    $asuntoCorreo = "Dentro de miPerfil el tercer asunto.";

                    $resultadoEmail = enviarEmail($emailDestinatario, $nombreDestinatario, $asuntoCorreo, $contenidoCorreo);
                }
            }

            if (isset($_POST['cancelarOrden'])) {

                $productosAddStock = Producto::obtenerProductosReservaOrden($_SESSION['id_usuario'], $_POST['id_reserva'], $_POST['id_orden']);
                if (!empty($productosAddStock)) {
                    Producto::incrementarStockPorCancelacion($productosAddStock);
                }

                $orden->reembolsarOrden($_POST['id_reserva']);

                $orden->eliminarOrdenPorCodigoReserva($_POST['id_reserva']);

                $nuevaReserva->modificarComandaPreviaReservaPorOrdenCancelada($_POST['id_reserva']);

                $emailDestinatario = $_SESSION['email_usuario'];
                $nombreDestinatario = $_SESSION['nombre_usuario'];

                $contenidoCorreo = "";

                $contenidoCorreo = "<h2>Cancelación de su orden en Restaurante XITO</h2>";
                $contenidoCorreo .= "<p>Estimado/a " . htmlspecialchars($nombreDestinatario) . ",</p>";
                $contenidoCorreo .= "<p>Su orden ha sido cancelada.</p>";

                $contenidoCorreo .= "<p>Le recordamos que la devolución de su pago se realizará en un plazo de 5-7 días hábiles.</p>";

                $contenidoCorreo .= "<p>Gracias por confiar en Restaurante XITO. Esperamos verle pronto.</p>";

                //$asuntoCorreo = "Cancelación de su orden en Restaurante XITO";
                $asuntoCorreo = "Dentro de miPerfil el cuarto asunto.";

                $resultadoEmail = enviarEmail($emailDestinatario, $nombreDestinatario, $asuntoCorreo, $contenidoCorreo);
            }


            ?>

        </section>


    </main>

    <?php include_once __DIR__ . '/../partials/footer.php'; ?>

</body>

</html>