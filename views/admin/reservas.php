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
                <input type="text" id="telefono_usuario" name="telefono_usuario" >

                <label for="fecha_reserva">Fecha de la reserva:</label>
                <input type="date" id="fecha_reserva" name="fecha_reserva" >

                <button type="submit" class="btn_buscarReserva" name="buscar_reserva">Buscar Reserva</button>
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar_reserva'])) {
                // Procesar el formulario
                $telefono = $_POST['telefono_usuario'] ?? '';
                $fecha_reserva = $_POST['fecha_reserva'] ?? '';

                $reserva = new Reserva();
                $reserva_encontrada = $reserva->obtenerReservaPorTelefonoYFecha($telefono, $fecha_reserva);

                if ($reserva_encontrada) {
                    // Mostrar los detalles de la reserva
                    echo "<h2>Detalles de la Reserva</h2>";
                    echo "<p>Código de Reserva: " . htmlspecialchars($reserva_encontrada['codigo_reserva']) . "</p>";
                    echo "<p>Teléfono: " . htmlspecialchars($telefono) . "</p>";
                    echo "<p>Fecha de Reserva: " . htmlspecialchars($fecha_reserva) . "</p>";
                    // Mostrar más detalles según sea necesario
                } else {
                    echo "<p>No se encontró ninguna reserva para el teléfono y la fecha proporcionados.</p>";
                }
            }
           
            ?>
        </section>

    </main>


</body>

</html>