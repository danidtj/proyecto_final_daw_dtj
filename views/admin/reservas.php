<?php
@session_start();

if (!isset($_SESSION['id_usuario'])) {

    header("Location: ../frontend/home");
    exit;
}

use ModelsFrontend\Reserva;
use ModelsFrontend\Orden;
use ModelsFrontend\Mesa;
use ModelsAdmin\Producto;

require_once dirname(__DIR__, 2) . '/models/frontend/Reserva.php';
require_once dirname(__DIR__, 2) . '/models/frontend/Orden.php';
require_once dirname(__DIR__, 2) . '/models/frontend/Mesa.php';
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
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>
    <?php include_once __DIR__ . '/../partials/headerAdmin.php'; ?>

    <main>
        <h1 class="header_reserva admin_reservas_titulo">RESERVAS DE CLIENTES</h1>
        <?php
        if (!isset($_POST['reservar'])) { ?>

            <section class="container_form admin_reservas_container">
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

        <?php }
        if (!isset($_POST['buscar_reserva'])) {
        ?>

            <section class="container_form admin_reservas_plano_container">

                <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_POST['reservar'])):
                    unset($_SESSION['modificar_reserva']);
                    unset($_SESSION['fecha']);
                    unset($_SESSION['hora_inicio']);
                    unset($_SESSION['numero_comensales']);
                    unset($_SESSION['comanda_previa']);


                ?>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" id="formulario-reserva" class="formulario admin_reservas_container_formulario">
                        <!-- Campo oculto de mesa -->
                        <input type="hidden" id="mesa_id" name="mesa_id" value="">

                        <!-- Fecha -->
                        <div>
                            <label for="fecha">Selecciona la fecha:</label>
                            <input type="date" id="fecha" name="fecha" title="Elige la fecha" onkeydown="return false" required value="<?php echo date('Y-m-d'); ?>">

                            <p class="mensaje-error" id="error-fecha" role="alert" aria-live="assertive"></p>
                        </div>

                        <!-- Hora -->
                        <div>
                            <label for="hora_inicio">Hora:</label>
                            <select id="hora_inicio" name="hora_inicio" required>

                                <optgroup label="Mediodía">
                                    <option value="13:30">13:30</option>
                                    <option value="14:00" selected>14:00</option>
                                    <option value="14:30">14:30</option>
                                    <option value="15:00">15:00</option>
                                    <option value="15:30">15:30</option>
                                    <option value="16:00">16:00</option>
                                    <option value="16:30">16:30</option>
                                    <option value="17:00">17:00</option>
                                </optgroup>

                                <optgroup label="Noche">
                                    <option value="20:30">20:30</option>
                                    <option value="21:00">21:00</option>
                                    <option value="21:30">21:30</option>
                                    <option value="22:00">22:00</option>
                                    <option value="22:30">22:30</option>
                                    <option value="23:00">23:00</option>
                                    <option value="23:30">23:30</option>
                                </optgroup>
                            </select>

                            <p class="mensaje-error" id="error-hora" role="alert" aria-live="assertive"></p>
                        </div>

                        <!-- Comensales -->
                        <div>
                            <label for="numero_comensales">Número de comensales:</label>
                            <select id="numero_comensales" name="numero_comensales">
                                <option value="1" selected>1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>

                            <p class="mensaje-error" id="error-comensales" role="alert" aria-live="assertive"></p>
                        </div>

                        <!-- Botón -->
                        <div>
                            <button type="submit" id="boton-reservar" name="reservar" class="btn-reservar btn_reservar btn_buscarReserva">Consultar</button>
                        </div>

                    </form>


                <?php endif; ?>

                <?php



                if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['reservar']))):

                    $_SESSION['fecha'] = $_POST['fecha'];
                    $_SESSION['hora_inicio'] = $_POST['hora_inicio'];
                    $_SESSION['numero_comensales'] = $_POST['numero_comensales'];


                    $mesa = new Mesa();
                    $idMesasDisponibles = $mesa->obtenerMesasDisponibles($_SESSION['fecha'], $_SESSION['hora_inicio'], $_SESSION['numero_comensales']);

                ?>
                    <section class="container_form admin_reservas_container_formulario">
                        <?php
                        if (isset($_POST['reservar'])) { ?>

                            <!-- Fecha -->
                            <div>
                                <label for="fecha_reserva">Selecciona la fecha:</label>
                                <?php echo "<input type='date' id='fecha_reserva' name='fecha' title='Elige la fecha' value='" . $_SESSION['fecha'] . "' onkeydown='return false'>"; ?>
                                <p class="mensaje-error" id="error-fecha" role="alert" aria-live="assertive"></p>
                            </div>

                            <!-- Hora -->

                            <div>
                                <label for="hora_inicio">Hora:</label>
                                <select id="hora_inicio" name="hora_inicio" required>
                                    <option value="<?= $_SESSION['hora_inicio']; ?>" selected><?= $_SESSION['hora_inicio']; ?></option>
                                </select>
                                <p class="mensaje-error" id="error-hora" role="alert" aria-live="assertive"></p>
                            </div>

                            <!-- Comensales -->
                            <div>
                                <label for="numero_comensales">Número de comensales:</label>
                                <select id="numero_comensales" name="numero_comensales">
                                    <option value="<?= $_SESSION['numero_comensales']; ?>" selected><?= $_SESSION['numero_comensales']; ?></option>
                                </select>


                                <p class="mensaje-error" id="error-comensales" role="alert" aria-live="assertive"></p>
                            </div>



                        <?php
                        } ?>

                    </section>


                    <section class="container_plano admin_reservas_plano_container">


                        <!-- Para trasladar el ID de la mesa seleccionada -->
                        <input type="hidden" name="mesa_id" id="mesa_id" value="">

                        <?php
                        $planoMesas = [
                            // Subarrays para representar la estructura de mesas y huecos entre las mismas
                            [true,  false, true,  false, true,  false],
                            [false, true,  false, true,  false, true],
                            [true,  false, true,  false, true,  false],
                        ];

                        $idMesa = 1; // ID inicial de las mesas

                        echo '<table class="tabla">';

                        foreach ($planoMesas as $fila) {
                            echo '<tr class="fila">';

                            foreach ($fila as $mesa) {
                                if ($mesa) {
                                    $clase = 'celda mesa-seleccionada';
                                    if (!in_array($idMesa, $idMesasDisponibles)) {
                                        $clase .= ' mesa-no-disponible';
                                    }

                                    echo "<td class=\"$clase\" title=\"Número: $idMesa\" id=\"$idMesa\">";
                                    echo '<span class="silla1"></span>';
                                    echo '<span class="silla2"></span>';
                                    echo '<span class="mesa"></span>';
                                    echo '<span class="silla3"></span>';
                                    echo '<span class="silla4"></span>';
                                    echo '</td>';

                                    $idMesa++; // Solo incrementa si hay una mesa
                                } else {
                                    echo '<td class="celda"></td>';
                                }
                            }

                            echo '</tr>';
                        }

                        echo '</table>';
                        ?>

                    </section>

                <?php endif; ?>

            </section>

    </main>
<?php
        }
        include_once __DIR__ . '/../partials/footer.php'; ?>
</body>

</html>