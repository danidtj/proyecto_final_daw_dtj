<?php
session_start();

use ModelsFrontend\Reserva;
use ModelsFrontend\Orden;
use ModelsAdmin\Producto;

require_once dirname(__DIR__, 2) . '/models/frontend/Reserva.php';
require_once dirname(__DIR__, 2) . '/models/frontend/Orden.php';
require_once dirname(__DIR__, 2) . '/models/admin/Producto.php';
require_once dirname(__DIR__, 2) . '/controllers/utilidades/enviarEmail.php';
$nuevaReserva = new Reserva();
if (isset($_SESSION['id_usuario'])) {
    $reservasUsuario = $nuevaReserva->obtenerReservasPorUsuario($_SESSION['id_usuario']);
}
$orden = new Orden();

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

        //Mostrarle al cliente desde $reservaEmail la fecha de la reserva, la hora, el número de mesa y el de comensales
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
        $asuntoCorreo = "Confirmación de su reserva en Restaurante XITO";

        enviarEmail($emailDestinatario, $nombreDestinatario, $asuntoCorreo, $contenidoCorreo);
    }

    unset($_SESSION['idOrdenCreada']);
    unset($_SESSION['codigo_reserva']);
    unset($_SESSION['carrito']);
    unset($_SESSION['stripe_payment_id']);
}

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
        $asuntoCorreo = "Modificación de su orden en Restaurante XITO";

        enviarEmail($emailDestinatario, $nombreDestinatario, $asuntoCorreo, $contenidoCorreo);
    }

    unset($_SESSION['idReservaModificar']);
    unset($_SESSION['idOrdenModificar']);
    unset($_SESSION['carrito']);
    unset($_SESSION['stripe_payment_id']);
}


if (isset($_POST['modificarOrden']) && !empty($_POST['id_orden']) && !empty($_POST['id_reserva'])) {

    $_SESSION['carrito'] = [];
    $_SESSION['modificar_orden'] = true;

    $recuperarOrden = $orden->obtenerProductosPorOrden($_POST['id_orden']);
    foreach ($recuperarOrden as $productoOrden) {
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

    header("Location: /views/frontend/carrito.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Restaurante XITO</title>
</head>


<body>
    <hr id="hr1">
    <!--<hr id="hr2">
        <hr id="hr3">-->
    <hr id="hr4">
    <?php include_once __DIR__ . '/../partials/header.php'; ?>
    <main>
        <section class="container_form">
            <h2 class="titulo_form">Resumen de mis reservas</h2>
            <?php
            if (!empty($reservasUsuario)) {

                foreach ($reservasUsuario as $reserva) {
                    echo "
                    <div class='reserva_detalles'>
                    <div class='cabecera_reserva'>
                            <span><strong>Código:</strong> " . htmlspecialchars($reserva['id_reserva']) . "</span>                            
                            <span><strong>Fecha:</strong> " . htmlspecialchars(date('d/m/Y', strtotime($reserva['fecha']))) . "</span>
                            <span><strong>Hora:</strong> " . htmlspecialchars($reserva['hora_inicio']) . "</span>
                            <span><strong>Mesa:</strong> " . htmlspecialchars($reserva['id_mesa']) . "</span>";
                    if ($reserva['comanda_previa'] == 1) {
                        echo " <span><strong>Comanda: </strong>Sí</span> ";
                    } else {
                        echo " <span><strong>Comanda: </strong>No</span> ";
                    }
                    echo "
                            <span><strong>Comensales:</strong> " . htmlspecialchars($reserva['numero_comensales']) . "</span></div>
                          ";

                    $ordenes = $orden->obtenerOrdenPorCodigoReserva($reserva['id_reserva']);
                    if (!empty($ordenes)) {
                        echo "<div class='productos_reserva'>";
                        $productosOrden = Producto::obtenerProductosReservaOrden($_SESSION['id_usuario'], $reserva['id_reserva'], $ordenes['id_orden']);

                        foreach ($productosOrden as $producto) {
                            echo "<p>" . htmlspecialchars($producto['nombre_corto']) . " ..... " . htmlspecialchars($producto['cantidad_pedido']) . " u ..... " .
                                number_format(htmlspecialchars($producto['precio_unitario']), 2, ',', '.') . " € ..... " . number_format(htmlspecialchars($producto['precio_unitario']) * htmlspecialchars($producto['cantidad_pedido']), 2, ',', '.') . " €</p>";
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


            ?>
                        <div class="botones">
                            <!-- Formulario para cancelar la reserva -->
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                                <input type="hidden" name="id_reserva" value="<?php echo htmlspecialchars($reserva['id_reserva']); ?>">
                                <input type="hidden" name="comanda_previa" value="<?php echo htmlspecialchars($reserva['comanda_previa']); ?>">
                                <input type="hidden" name="id_orden" value="<?php echo htmlspecialchars($ordenes['id_orden']); ?>">
                                <input type="submit" class="botones btn_cancelar" value="Cancelar reserva" name="cancelarReserva">
                            </form>

                            <!-- Formulario para modificar la reserva -->
                            <form action="/controllers/frontend/ReservaController.php" method="post">
                                <input type="hidden" name="id_reserva" value="<?php echo htmlspecialchars($reserva['id_reserva']); ?>">
                                <input type="hidden" name="fecha" value="<?php echo htmlspecialchars($reserva['fecha']); ?>">
                                <input type="hidden" name="hora_inicio" value="<?php echo htmlspecialchars($reserva['hora_inicio']); ?>">
                                <input type="hidden" name="numero_comensales" value="<?php echo htmlspecialchars($reserva['numero_comensales']); ?>">
                                <input type="hidden" name="comanda_previa" value="<?php echo htmlspecialchars($reserva['comanda_previa']); ?>">
                                <!--<input type="hidden" name="id_mesa" value="<?php echo htmlspecialchars($reserva['id_mesa']); ?>">-->
                                <input type="submit" class="botones btn_modificar" value="Modificar reserva" name="modificarReserva">
                            </form>



                            <?php
                        }

                        if (!empty($ordenes)) {

                            // Bandera que indica si debe mostrarse el botón
                            $mostrarBoton = false;

                            // Condición 1: reserva con fecha anterior (idReservasActivasPorFecha)
                            if (in_array($reserva['id_reserva'], array_column($idReservasActivasPorFecha, 'id_reserva'))) {
                                $mostrarBoton = true;

                                if (
                                    $mostrarBoton && $reserva['hora_inicio'] > date('H:i:s') && $reserva['fecha'] >= date('Y-m-d') ||
                                    $reserva['fecha'] > date('Y-m-d')
                                ) {
                            ?>

                                    <!-- Formulario para cancelar la orden -->
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                                        <input type="hidden" name="id_reserva" value="<?php echo htmlspecialchars($ordenes['id_reserva']); ?>">
                                        <input type="hidden" name="id_orden" value="<?php echo htmlspecialchars($ordenes['id_orden']); ?>">
                                        <input type="submit" class="botones btn_cancelar" value="Cancelar orden" name="cancelarOrden">
                                    </form>

                        <?php
                                }
                            }

                            // Condición 2: reservas activas por usuario (entre hora inicio y fin)
                            if (in_array($reserva['id_reserva'], array_column($idReservasActivasUsuario, 'id_reserva'))) {
                                $mostrarBoton = true;
                            }

                            // Mostrar el botón una sola vez
                            if ($mostrarBoton && $reserva['hora_fin'] > date('H:i:s') && $reserva['fecha'] >= date('Y-m-d')) {
                                echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="post">';
                                echo "<input type=\"hidden\" name=\"id_reserva\" value=\"" . htmlspecialchars($reserva['id_reserva']) . "\">";
                                echo "<input type=\"hidden\" name=\"id_orden\" value=\"" . htmlspecialchars($ordenes['id_orden']) . "\">";
                                echo "<input type=\"submit\" class=\"botones btn_modificar\" value=\"Modificar orden\" name=\"modificarOrden\">";
                                echo "</form>";
                            }
                        }


                        ?>

                        </div>
                        </div>
                        <br>
                <?php

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

                    $asuntoCorreo = "Cancelación de su reserva en Restaurante XITO";

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

                $asuntoCorreo = "Cancelación de su orden en Restaurante XITO";

                $resultadoEmail = enviarEmail($emailDestinatario, $nombreDestinatario, $asuntoCorreo, $contenidoCorreo);
            }


                ?>

        </section>


    </main>

    <?php include_once __DIR__ . '/../partials/footer.php'; ?>

</body>

</html>