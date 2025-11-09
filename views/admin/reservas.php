<?php
@session_start();

use ModelsFrontend\Reserva;

require_once dirname(__DIR__, 2) . '/models/frontend/Reserva.php';
$reserva = new Reserva();
$reservas = $reserva->obtenerTodasLasReservas();

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

                    if ($reserva_encontrada) {
                        echo "<br><h2>Detalles de la reserva con teléfono: " . htmlspecialchars($telefono) . " y fecha " . date('d/m/Y', strtotime($fecha_reserva)) . "</h2><br>";
                        echo "<p>Código de Reserva: " . htmlspecialchars($reserva_encontrada['id_reserva']) . "</p>";
                        echo "<p>Número de mesa: " . htmlspecialchars($reserva_encontrada['id_mesa']) . "</p>";
                        echo "<p>Comensales: " . htmlspecialchars($reserva_encontrada['numero_comensales']) . "</p>";
                        if($reserva_encontrada['comanda_previa'] == 1){
                            echo "<p>Comanda Previa: Sí</p>";
                        } else {
                            echo "<p>Comanda Previa: No</p>";
                        }
                        echo "<p>Hora de Inicio: " . htmlspecialchars($reserva_encontrada['hora_inicio']) . "</p>";
                        echo "<p>Hora de Fin: " . htmlspecialchars($reserva_encontrada['hora_fin']) . "</p>";
                    } else {
                        echo "<p>No se encontró ninguna reserva para el teléfono y la fecha proporcionados.</p>";
                    }
                } elseif (!empty($fecha_reserva)) {
                    // Buscar todas las reservas de una fecha específica
                    $reservas = $reserva->obtenerReservasPorFecha($fecha_reserva);

                    if ($reservas) {
                        echo "<br><h2>Reservas para la fecha " . date('d/m/Y', strtotime($fecha_reserva)) . "</h2><br>";
                        foreach ($reservas as $r) {
                            echo "<p>Código de Reserva: " . htmlspecialchars($r['id_reserva']) . "</p>";
                            echo "<p>Número de mesa: " . htmlspecialchars($r['id_mesa']) . "</p>";
                            echo "<p>Comensales: " . htmlspecialchars($r['numero_comensales']) . "</p>";
                            if($r['comanda_previa'] == 1){
                                echo "<p>Comanda Previa: Sí</p>";
                            } else {
                                echo "<p>Comanda Previa: No</p>";
                            }
                            echo "<p>Hora de Inicio: " . htmlspecialchars($r['hora_inicio']) . "</p>";
                            echo "<p>Hora de Fin: " . htmlspecialchars($r['hora_fin']) . "</p>";
                            echo "<p>Fecha de Reserva: " . htmlspecialchars(date('d/m/Y', strtotime($r['fecha']))) . "</p><br>";
                        }
                    } else {
                        echo "<p>No se encontraron reservas para la fecha proporcionada.</p>";
                    }
                } elseif (!empty($telefono)) {
                    // Buscar todas las reservas de un teléfono específico
                    $reservas = $reserva->obtenerReservasPorTelefono($telefono);

                    if ($reservas) {
                        echo "<br><h2>Reservas para el teléfono " . htmlspecialchars($telefono) . "</h2><br>";
                        foreach ($reservas as $r) {
                            echo "<p>Código de Reserva: " . htmlspecialchars($r['id_reserva']) . "</p>";
                            echo "<p>Número de mesa: " . htmlspecialchars($r['id_mesa']) . "</p>";
                            echo "<p>Comensales: " . htmlspecialchars($r['numero_comensales']) . "</p>";
                            if(htmlspecialchars($r['comanda_previa']) === "1"){
                                echo "<p>Comanda Previa: Sí</p>";
                            } else {
                                echo "<p>Comanda Previa: No</p>";
                            }
                            echo "<p>Hora de Inicio: " . htmlspecialchars($r['hora_inicio']) . "</p>";
                            echo "<p>Hora de Fin: " . htmlspecialchars($r['hora_fin']) . "</p>";
                            echo "<p>Fecha de Reserva: " . htmlspecialchars(date('d/m/Y', strtotime($r['fecha']))) . "</p><br>";
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