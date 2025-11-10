<?php
@session_start();

use ModelsFrontend\Reserva;
use ModelsFrontend\Orden;
use ModelsAdmin\Producto;

require_once dirname(__DIR__, 2) . '/models/frontend/Reserva.php';
require_once dirname(__DIR__, 2) . '/models/frontend/Orden.php';
require_once dirname(__DIR__, 2) . '/models/admin/Producto.php';

$reserva = new Reserva();
$reservas = $reserva->obtenerTodasLasReservas();

$orden = new Orden();


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante XITO</title>
    <link rel="stylesheet" href="/assets/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>
    <?php include_once __DIR__ . '/../partials/headerAdmin.php'; ?>

    <main>
        <hr id="hr1">
        <hr id="hr4">


        <h1 class="header_reserva">RESERVAS DE CLIENTES</h1>
        <section class="container_form">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <label for="telefono_usuario">Teléfono:</label>
                <input type="text" id="telefono_usuario" name="telefono_usuario">

                <label for="fecha_reserva">Fecha de la reserva:</label>
                <input type="date" id="fecha_reserva" name="fecha_reserva">

                <button type="submit" class="btn_buscarReserva" name="buscar_reserva">Buscar Reserva</button>
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar_reserva'])) {
                // Procesar el formulario
                $telefono = $_POST['telefono_usuario'] ?? '';
                $fecha_reserva = $_POST['fecha_reserva'] ?? '';

                $reserva = new Reserva();

                if (!empty($fecha_reserva) && !empty($telefono)) {
                    // Buscar reserva por teléfono y fecha
                    $reserva_encontrada = $reserva->obtenerReservaPorTelefonoYFecha($telefono, $fecha_reserva);

                    //Bloque de los detalles de la reserva
                    if ($reserva_encontrada) {
                        echo "<div class='reserva_detalles_admin'>";
                        echo "<br><p>Detalles de la reserva con teléfono " . htmlspecialchars($telefono) . " y fecha " .
                            date('d/m/Y', strtotime($fecha_reserva)) . "</p><br>";
                        echo "<div class='cabecera_reserva_admin'>";

                        echo "<span class='codigo_reserva'>Reserva: " . htmlspecialchars($reserva_encontrada['id_reserva']) . "</span>";
                        echo "<span>Mesa: " . htmlspecialchars($reserva_encontrada['id_mesa']) . "</span>";
                        echo "<span>Comensales: " . htmlspecialchars($reserva_encontrada['numero_comensales']) . "</span>";
                        if ($reserva_encontrada['comanda_previa'] == 1) {
                            echo "<span>Comanda Previa: Sí</span>";
                        } else {
                            echo "<span>Comanda Previa: No</span>";
                        }
                        echo "<span>Horario: " . htmlspecialchars($reserva_encontrada['hora_inicio']) . " - " .
                            htmlspecialchars($reserva_encontrada['hora_fin']) . "</span><br>";
                        echo "</div>";
                        echo "<div class='productos_reserva_admin'>";
                        //Bloque de la orden asociada a la reserva
                        if ($reserva_encontrada['comanda_previa'] == 1) {

                            $orden_reserva = $orden->obtenerOrdenPorCodigoReserva($reserva_encontrada['id_reserva']);
                            $productosOrden = Producto::obtenerProductosReservaOrden($reserva_encontrada['id_usuario'], $reserva_encontrada['id_reserva'], $orden_reserva['id_orden']);
                            if ($orden_reserva) {
                                echo "<p>Código de la orden: " . htmlspecialchars($orden_reserva['id_orden']) . "</p>";
                                echo "<p>Precio total: " . number_format($orden_reserva['precio_total'], 2, ',', '.') . " €</p>";
                                echo "<p>Montante adelantado (10%): " . number_format($orden_reserva['montante_adelantado'], 2, ',', '.') . " €</p>";
                            }
                            //Productos de la orden
                            if ($productosOrden) {
                                echo "<p>Productos de la orden:</p>";
                                foreach ($productosOrden as $producto) {
                                    echo "<p>" . htmlspecialchars($producto['nombre_corto']) . ": " .
                                        " ..... " . htmlspecialchars($producto['cantidad_pedido']) . "u ..... " . "
                                     " . number_format(htmlspecialchars($producto['precio_unitario']), 2, ',', '.') . " € ..... " . number_format(htmlspecialchars($producto['precio_unitario'])
                                            * htmlspecialchars($producto['cantidad_pedido']), 2, ',', '.') . " €</p>";
                                }
                            }
                            echo "</div>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No se encontró ninguna reserva para el teléfono y la fecha proporcionados.</p>";
                    }
                } elseif (!empty($fecha_reserva)) {
                    // Buscar todas las reservas de una fecha específica
                    $reservas = $reserva->obtenerReservasPorFecha($fecha_reserva);

                    if ($reservas) {
                        echo "<div class='reserva_detalles_admin'>";
                        echo "<br><p>Reservas para la fecha " . date('d/m/Y', strtotime($fecha_reserva)) . "</p><br>";
                        
                        foreach ($reservas as $r) {
                            echo "<div class='cabecera_reserva_admin'>";
                            echo "<span>Reserva: " . htmlspecialchars($r['id_reserva']) . "</span>";
                            echo "<span>Mesa: " . htmlspecialchars($r['id_mesa']) . "</span>";
                            echo "<span>Comensales: " . htmlspecialchars($r['numero_comensales']) . "</span>";
                            if ($r['comanda_previa'] == 1) {
                                echo "<span>Comanda Previa: Sí</span>";
                            } else {
                                echo "<span>Comanda Previa: No</span>";
                            }
                            echo "<span>Horario: " . htmlspecialchars($r['hora_inicio']) . " - " .
                                htmlspecialchars($r['hora_fin']) . "</span><br>";
                            echo "</div>";
                            echo "<div class='productos_reserva_admin'>";
                            if ($r['comanda_previa'] == 1) {
                                $orden_reserva = $orden->obtenerOrdenPorCodigoReserva($r['id_reserva']);
                                $productosOrden = Producto::obtenerProductosReservaOrden($r['id_usuario'], $r['id_reserva'], $orden_reserva['id_orden']);

                                if ($orden_reserva) {
                                    echo "<p>Código de la orden: " . htmlspecialchars($orden_reserva['id_orden']) . "</p>";
                                    echo "<p>Precio total: " . number_format($orden_reserva['precio_total'], 2, ',', '.') . " €</p>";
                                    echo "<p>Montante adelantado (10%): " . number_format($orden_reserva['montante_adelantado'], 2, ',', '.') . " €</p>";
                                }
                                //Productos de la orden
                                if ($productosOrden) {
                                    echo "<p>Productos de la orden:</p>";
                                    foreach ($productosOrden as $producto) {
                                        echo "<p>" . htmlspecialchars($producto['nombre_corto']) . ": " .
                                            " ..... " . htmlspecialchars($producto['cantidad_pedido']) . "u ..... " . "
                                     " . number_format(htmlspecialchars($producto['precio_unitario']), 2, ',', '.') . " € ..... " . number_format(htmlspecialchars($producto['precio_unitario'])
                                                * htmlspecialchars($producto['cantidad_pedido']), 2, ',', '.') . " €</p>";
                                    }
                                    echo "</div>";
                                }
                            }
                        }
                        
                        
                    } else {
                        echo "<p>No se encontraron reservas para la fecha proporcionada.</p>";
                    }
                } elseif (!empty($telefono)) {
                    // Buscar todas las reservas de un teléfono específico
                    $reservas = $reserva->obtenerReservasPorTelefono($telefono);

                    if ($reservas) {
                        echo "<div class='reserva_detalles_admin'>";
                        echo "<br><p>Reservas para el teléfono " . htmlspecialchars($telefono) . "</p><br>";
                        
                        foreach ($reservas as $r) {
                            echo "<div class='cabecera_reserva_admin'>";
                            echo "<span>Reserva: " . htmlspecialchars($r['id_reserva']) . "</span>";
                            echo "<span>Mesa: " . htmlspecialchars($r['id_mesa']) . "</span>";
                            echo "<span>Comensales: " . htmlspecialchars($r['numero_comensales']) . "</span>";
                            if (htmlspecialchars($r['comanda_previa']) === "1") {
                                echo "<span>Comanda Previa: Sí</span>";
                            } else {
                                echo "<span>Comanda Previa: No</span>";
                            }
                            echo "<span>Horario: " . htmlspecialchars($r['hora_inicio']) . " - " .
                                htmlspecialchars($r['hora_fin']) . "</span><br>";
                            echo "<span>Fecha: " . htmlspecialchars(date('d/m/Y', strtotime($r['fecha']))) . "</span><br>";

                            
                            echo "</div>";
                            echo "<div class='productos_reserva_admin'>";
                            if ($r['comanda_previa'] == 1) {
                                $orden_reserva = $orden->obtenerOrdenPorCodigoReserva($r['id_reserva']);
                                $productosOrden = Producto::obtenerProductosReservaOrden($r['id_usuario'], $r['id_reserva'], $orden_reserva['id_orden']);

                                if ($orden_reserva) {
                                    echo "<p>Código de la orden: " . htmlspecialchars($orden_reserva['id_orden']) . "</p>";
                                    echo "<p>Precio total: " . number_format($orden_reserva['precio_total'], 2, ',', '.') . " €</p>";
                                    echo "<p>Montante adelantado (10%): " . number_format($orden_reserva['montante_adelantado'], 2, ',', '.') . " €</p>";
                                }
                            }
                            //Productos de la orden
                            if ($productosOrden) {
                                echo "<p>Productos de la orden:</p>";
                                foreach ($productosOrden as $producto) {
                                    echo "<p>" . htmlspecialchars($producto['nombre_corto']) . ": " .
                                        " ..... " . htmlspecialchars($producto['cantidad_pedido']) . "u ..... " . "
                                     " . number_format(htmlspecialchars($producto['precio_unitario']), 2, ',', '.') . " € ..... " . number_format(htmlspecialchars($producto['precio_unitario'])
                                            * htmlspecialchars($producto['cantidad_pedido']), 2, ',', '.') . " €</p>";
                                     break;
                                }
                                echo "</div>";
                            }
                            
                            continue;
                            
                        }


                        
                        
                    } else {
                        echo "<p>No se encontraron reservas para el teléfono proporcionado.</p>";
                    }
                } else {
                    echo "<p>Por favor, ingrese al menos un criterio de búsqueda (teléfono o fecha).</p>";
                }
            }


            ?>
        </section>

    </main>


</body>

</html>