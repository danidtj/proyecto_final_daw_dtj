<?php
@session_start();
//Mediante este bloque se detecta la acción GET, accion=eliminar y el código del producto a eliminar por AJAX
if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && isset($_GET['codigo'])) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    //Almacena el código del producto del que se ha seleccionado el botón de eliminar
    $codigoEliminar = $_GET['codigo'];

    if (isset($_SESSION['carrito'])) {
        foreach ($_SESSION['carrito'] as $index => $producto) {
            if ($producto['id_producto'] == $codigoEliminar) {
                //Elimina de la superglobal el producto mediante el índice
                unset($_SESSION['carrito'][$index]);
                // Con array_values recuperamos los índices consecutivos del array ya que con unset se quedan los índices originales
                $_SESSION['carrito'] = array_values($_SESSION['carrito']);
                break;
            }
        }
    }

    //Comprobamos que el producto aún existe en el carrito después de la eliminación
    $productoExiste = false;
    foreach ($_SESSION['carrito'] as $producto) {
        if ($producto['id_producto'] == $codigoEliminar) {
            $productoExiste = true;
            break;
        }
    }

    //Calculamos el subtotal del producto eliminado (si aún existe en el carrito) teniendo en cuenta el número de unidades restantes
    $subtotal = 0;
    $resultado = array_count_values(array_column($_SESSION['carrito'], 'id_producto'));

    if ($productoExiste) {
        $cantidadRestante = $resultado[$codigoEliminar];
        foreach ($_SESSION['carrito'] as $producto) {
            if ($producto['id_producto'] == $codigoEliminar) {
                $subtotal = $producto['precio_unitario'] * $cantidadRestante;
                break;
            }
        }
    }

    //Una vez se ha eliminado el producto, recalculamos el nuevo precio total del carrito
    // Una vez se ha eliminado el producto, recalculamos el nuevo precio total del carrito
    $nuevoPrecioTotal = 0;
    if (!empty($_SESSION['carrito'])) {
        $resultado = array_count_values(array_column($_SESSION['carrito'], 'id_producto'));
        foreach ($resultado as $id => $cantidad) {
            foreach ($_SESSION['carrito'] as $producto) {
                if ($producto['id_producto'] == $id) {
                    $nuevoPrecioTotal += $producto['precio_unitario'] * $cantidad;
                    break;
                }
            }
        }
    }


    //Recalculamos el nuevo precio a pagar por adelantado (10% del total)
    $nuevoPagoAdelantado = $nuevoPrecioTotal * 0.1;
    $_SESSION['precioTotalCarrito'] = $nuevoPrecioTotal;
    $_SESSION['nuevoPagoAdelantado'] = $nuevoPagoAdelantado;

    // Devolvemos JSON con estado y total
    header('Content-Type: application/json');
    echo json_encode([
        "status" => "ok",
        "total" => $nuevoPrecioTotal,
        "nuevoPagoAdelantado" => $nuevoPagoAdelantado,
        "subtotal" => $subtotal,
        "productoExiste" => $productoExiste,
        "cantidadRestante" => $productoExiste
            ? (isset($resultado[$codigoEliminar]) ? $resultado[$codigoEliminar] : 0)
            : 0
    ]);

    exit;
}

//Vaciar carrito
if ($_GET['accion'] === 'vaciar') {
    $_SESSION['carrito'] = [];
    $_SESSION['precioTotalCarrito'] = 0;
    $_SESSION['nuevoPagoAdelantado'] = 0;

    header('Content-Type: application/json');
    echo json_encode([
        "status" => "ok",
        "mensaje" => "Carrito vaciado correctamente"
    ]);
    exit;
}
