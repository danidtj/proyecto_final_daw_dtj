<?php

use ModelsFrontend\Orden;
use ModelsFrontend\Reserva;
use ModelsAdmin\Producto;

@session_start();

require_once dirname(__DIR__, 2) . '/models/frontend/Orden.php';
require_once dirname(__DIR__, 2) . '/models/frontend/Reserva.php';
require_once dirname(__DIR__, 2) . '/models/admin/Producto.php';
$orden = new Orden();
$reserva = new Reserva();

if (isset($_SESSION['modificar_orden']) && $_SESSION['modificar_orden'] === true && isset($_SESSION['orden_original'])) {
    foreach ($_SESSION['carrito'] as $producto) {
        foreach ($_SESSION['orden_original'] as $ordenOriginal) {
            $idReservaEmail = $ordenOriginal['id_reserva'];
            $idOrdenEmail = $ordenOriginal['id_orden'];
            $orden->modificarOrdenPorCodigoOrden(
                $_SESSION['precioTotalCarrito'],
                $_SESSION['nuevoPagoAdelantado'],
                $ordenOriginal['id_orden'],
                $producto['id_producto'],
                array_count_values(array_column($_SESSION['carrito'], 'id_producto'))[$producto['id_producto']] ?? 0,
                $ordenOriginal['id_reserva'],
                $_SESSION['carrito']
            );
        }
    }

    unset($_SESSION['confirmarModificacionReserva']);
    unset($_SESSION['modificar_orden']);
    unset($_SESSION['orden_original']);
    unset($_SESSION['carrito']); // Vaciamos el carrito después de modificar la orden
    header("Location: /views/frontend/miPerfil.php");

    $emailDestinatario = $_SESSION['email_usuario'];
    $nombreDestinatario = $_SESSION['nombre_usuario'];


    $ordenEmail = $orden->obtenerOrdenPorCodigoReserva($idReservaEmail);
    $reservaEmail = $reserva->obtenerReservaPorCodigo($idReservaEmail);
    $productosOrdenEmail = Producto::obtenerProductosReservaOrden($_SESSION['id_usuario'], $idReservaEmail, $idOrdenEmail);

    $contenidoCorreo = "";

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
    }

    exit();
} else {
    $codigo_reserva = $reserva->realizarReserva(
        $_SESSION['fecha'],
        $_SESSION['hora_inicio'],
        $_SESSION['numero_comensales'],
        $_SESSION['comanda_previa'],
        $_SESSION['mesa_id'],
        $_SESSION['id_usuario']
    );

    //Almacenamos el id de la nueva reserva en session
    $_SESSION['id_reserva_nueva'] = $codigo_reserva;

    $idOrdenCreada = $orden->crearOrden($_SESSION['id_reserva_nueva'], 'Tarjeta de crédito', $_SESSION['precioTotalCarrito'], $_SESSION['nuevoPagoAdelantado'], $_SESSION['carrito']);
    unset($_SESSION['confirmarReserva']);
    unset($_SESSION['comanda_previa']);
    unset($_SESSION['carrito']); // Vaciamos el carrito después de crear la orden
    unset($_SESSION['id_reserva_nueva']);
    header("Location: /views/frontend/miPerfil.php");
    exit();
}

echo json_encode(['success' => true]);
