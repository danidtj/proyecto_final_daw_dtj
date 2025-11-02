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
            if ($producto['codigo_producto'] == $codigoEliminar) {
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
        if ($producto['codigo_producto'] == $codigoEliminar) {
            $productoExiste = true;
            break;
        }
    }

    //Calculamos el subtotal del producto eliminado (si aún existe en el carrito) teniendo en cuenta el número de unidades restantes
    $subtotal = 0;
    $resultado = array_count_values(array_column($_SESSION['carrito'], 'codigo_producto'));

    if ($productoExiste) {
        $cantidadRestante = $resultado[$codigoEliminar];
        foreach ($_SESSION['carrito'] as $producto) {
            if ($producto['codigo_producto'] == $codigoEliminar) {
                $subtotal = $producto['precio_producto'] * $cantidadRestante;
                break;
            }
        }
    }

    //Una vez se ha eliminado el producto, recalculamos el nuevo precio total del carrito
    $nuevoPrecioTotal = 0;
    if (!empty($_SESSION['carrito'])) {
        foreach ($_SESSION['carrito'] as $producto) {
            $nuevoPrecioTotal += $producto['precio_producto'];
        }
    }

    // Devolvemos JSON con estado y total
    header('Content-Type: application/json');
    echo json_encode([
        "status" => "ok",
        "total" => $nuevoPrecioTotal,
        "productoExiste" => $productoExiste,
        "cantidadRestante" => $productoExiste ? count(array_filter($_SESSION['carrito'], function ($prod) use ($codigoEliminar) {
            return $prod['codigo_producto'] == $codigoEliminar;
        })) : 0,
        "subtotal" => $subtotal,
    ]);
    exit;
}
