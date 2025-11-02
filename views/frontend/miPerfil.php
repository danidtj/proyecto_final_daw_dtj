<?php
@session_start();

use ModelsFrontend\Reserva;
use ModelsFrontend\Orden;

require_once dirname(__DIR__, 2) . '/models/frontend/Reserva.php';
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
                    echo "<div class='reserva_item'>
                            <p><strong>Código de la reserva:</strong> " . htmlspecialchars($reserva['codigo_reserva']) . "</p>
                            <p><strong>Fecha:</strong> " . htmlspecialchars($reserva['fecha_reserva']) . "</p>
                            <p><strong>Hora:</strong> " . htmlspecialchars($reserva['hora_reserva']) . "</p>
                            <p><strong>Número de personas:</strong> " . htmlspecialchars($reserva['numero_comensales']) . "</p>
                          </div>";

            ?>
                    <!-- Formulario para cancelar la reserva -->
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        <input type="hidden" name="codigo_reserva" value="<?php echo htmlspecialchars($reserva['codigo_reserva']); ?>">
                        <input type="submit" value="Cancelar reserva" name="cancelarReserva">
                    </form>

                    <!-- Formulario para modificar la reserva -->
                    <form action="/controllers/frontend/ReservaController.php" method="post">
                        <input type="hidden" name="codigo_reserva" value="<?php echo htmlspecialchars($reserva['codigo_reserva']); ?>">
                        <input type="hidden" name="fecha" value="<?php echo htmlspecialchars($reserva['fecha_reserva']); ?>">
                        <input type="hidden" name="hora" value="<?php echo htmlspecialchars($reserva['hora_reserva']); ?>">
                        <input type="hidden" name="comensales" value="<?php echo htmlspecialchars($reserva['numero_comensales']); ?>">
                        <input type="hidden" name="comanda" value="<?php echo htmlspecialchars($reserva['comanda_previa']); ?>">
                        <input type="hidden" name="numero_mesa" value="<?php echo htmlspecialchars($reserva['numero_mesa']); ?>">
                        <input type="submit" value="Modificar reserva" name="modificarReserva">
                    </form>
                    <br>
            <?php
                    if ($reserva['comanda_previa'] == 1) {
                        $ordenes = $orden->obtenerOrden($reserva['codigo_reserva']);
                        if ($ordenes) {
                            echo "<p><strong>Orden asociada a la reserva:</strong></p>";
                            echo "<p>Código de reserva: " . htmlspecialchars($ordenes['codigo_reserva']) . "</p>";
                            echo "<p>Número de mesa: " . htmlspecialchars($ordenes['numero_mesa']) . "</p>";
                            echo "<p>Estatus: " . htmlspecialchars($ordenes['estatus']) . "</p>";
                            echo "<hr>";
                        }
                    }
                }
            } else {
                echo "<p>No tienes reservas realizadas.</p>";
            }
            //Cancelar una reserva
            if (isset($_POST['cancelarReserva'])) {
                $nuevaReserva->cancelarReserva($_POST['codigo_reserva']);
            }

            /*if (isset($_POST['modificarReserva'])) {
                $_SESSION['modificar_reserva'] = $_POST['codigo_reserva'];
                header("Location: /views/frontend/reserva.php");
            }*/
            ?>

        </section>

    </main>

    <?php include_once __DIR__ . '/../partials/footer.php'; ?>

</body>

</html>