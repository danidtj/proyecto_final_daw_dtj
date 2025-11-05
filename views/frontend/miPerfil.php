<?php
@session_start();

use ModelsFrontend\Reserva;
use ModelsFrontend\Orden;
use ModelsAdmin\Producto;

require_once dirname(__DIR__, 2) . '/models/frontend/Reserva.php';
require_once dirname(__DIR__, 2) . '/models/frontend/Orden.php';
require_once dirname(__DIR__, 2) . '/models/admin/Producto.php';
$nuevaReserva = new Reserva();
$reservasUsuario = $nuevaReserva->obtenerReservasPorUsuario($_SESSION['id_usuario']);
$orden = new Orden();

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
                            <span><strong>Fecha:</strong> " . htmlspecialchars(date('Y-m-d', strtotime($reserva['fecha']))) . "</span>
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

                    if ($reserva['comanda_previa'] == 1) {
                        echo "<div class='productos_reserva'>";
                        $ordenes = $orden->obtenerOrdenPorCodigoReserva($reserva['id_reserva']);

                        if (!empty($ordenes) && $reserva['comanda_previa'] == 1) {
                            $productosOrden = Producto::obtenerProductosReservaOrden($_SESSION['id_usuario'], $reserva['id_reserva'], $ordenes['id_orden']);

                            foreach ($productosOrden as $producto) {
                                echo "<p>" . htmlspecialchars($producto['nombre_corto']) . " ..... " . htmlspecialchars($producto['cantidad_pedido']) . " uds</p>";
                            }
                            echo "<br><p><strong>Precio total: " . htmlspecialchars($ordenes['precio_total']) . " €</strong></p>";
                            echo "<p><strong>Montante adelantado: " . htmlspecialchars($ordenes['montante_adelantado']) . " €</strong></p><br>";
                            //echo "<p>Número de mesa: " . htmlspecialchars($ordenes['id_mesa']) . "</p>";

                        }
                        echo "</div>";
                    }

            ?>
                    <div class="botones">
                        <!-- Formulario para cancelar la reserva -->
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                            <input type="hidden" name="id_reserva" value="<?php echo htmlspecialchars($reserva['id_reserva']); ?>">
                            <input type="submit" class="botones btn_cancelar" value="Cancelar reserva" name="cancelarReserva">
                        </form>

                        <!-- Formulario para modificar la reserva -->
                        <form action="/controllers/frontend/ReservaController.php" method="post">
                            <input type="hidden" name="id_reserva" value="<?php echo htmlspecialchars($reserva['id_reserva']); ?>">
                            <input type="hidden" name="fecha" value="<?php echo htmlspecialchars($reserva['fecha']); ?>">
                            <input type="hidden" name="hora_inicio" value="<?php echo htmlspecialchars($reserva['hora_inicio']); ?>">
                            <input type="hidden" name="numero_comensales" value="<?php echo htmlspecialchars($reserva['numero_comensales']); ?>">
                            <input type="hidden" name="comanda_previa" value="<?php echo htmlspecialchars($reserva['comanda_previa']); ?>">
                            <input type="hidden" name="id_mesa" value="<?php echo htmlspecialchars($reserva['id_mesa']); ?>">
                            <input type="submit" class="botones btn_modificar" value="Modificar reserva" name="modificarReserva">
                        </form>

                        <?php
                        if (!empty($ordenes) && $reserva['comanda_previa'] == 1) {
                            //Formulario para modificar la orden
                            echo "<form action=\"/controllers/frontend/ReservaController.php\" method=\"post\">";
                            echo "<input type=\"hidden\" name=\"id_reserva\" value=\"" . htmlspecialchars($reserva['id_reserva']) . "\">";
                            echo "<input type=\"hidden\" name=\"id_orden\" value=\"" . htmlspecialchars($ordenes['id_orden']) . "\">";
                            echo "<input type=\"submit\" class=\"botones btn_modificar\" value=\"Modificar orden\" name=\"modificarOrden\">";
                            echo "</form>";
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
                $nuevaReserva->cancelarReserva($_POST['id_reserva']);
            }

            /*if (isset($_POST['modificarReserva'])) {
                $_SESSION['modificar_reserva'] = $_POST['id_reserva'];
                header("Location: /views/frontend/reserva.php");
            }*/
            ?>

        </section>

    </main>

    <?php include_once __DIR__ . '/../partials/footer.php'; ?>

</body>

</html>