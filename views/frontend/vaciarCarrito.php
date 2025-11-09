<?php
session_start();

if (isset($_SESSION['modificar_orden']) && $_SESSION['modificar_orden'] === true && isset($_SESSION['orden_original'])) {
    foreach ($_SESSION['carrito'] as $producto) {
        foreach ($_SESSION['orden_original'] as $ordenOriginal) {
            $orden->modificarOrdenPorCodigoOrden(
                $precioTotalCarrito,
                $nuevoPagoAdelantado,
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

    $idOrdenCreada = $orden->crearOrden($_SESSION['id_reserva_nueva'], 'Tarjeta de crédito', $precioTotalCarrito, $nuevoPagoAdelantado, $_SESSION['carrito']);
    unset($_SESSION['confirmarReserva']);
    unset($_SESSION['comanda_previa']);
    unset($_SESSION['carrito']); // Vaciamos el carrito después de crear la orden
    unset($_SESSION['id_reserva_nueva']);
    header("Location: /views/frontend/miPerfil.php");
    exit();
}

echo json_encode(['success' => true]);
?>
