<?php
@session_start();

use ModelsFrontend\Mesa;

require_once dirname(__DIR__, 2) . '/models/frontend/Mesa.php';
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
    <style>
        /* --- Estilos adicionales para mostrar estado de mesas --- */
        .mesa-disponible {
            background-color: #c6f6c6; /* Verde claro */
            cursor: pointer;
        }

        .mesa-no-disponible {
            background-color: #f8d7da; /* Rojo claro */
            opacity: 0.6;
            cursor: not-allowed;
        }

        .estado-mesa {
            font-size: 0.8rem;
            text-align: center;
            margin-top: 4px;
            color: #333;
        }

        .tabla {
            border-collapse: collapse;
            margin: 20px auto;
        }

        .celda {
            width: 100px;
            height: 100px;
            text-align: center;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <?php include_once __DIR__ . '/../partials/header.php'; ?>

    <main>
        <hr id="hr1">
        <hr id="hr4">

        <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_POST['reservar'])): ?>
            <h1 class="header_reserva">RESERVA CON NOSOTROS</h1>
            <section class="container_form">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" id="formulario-reserva" class="formulario">
                    <!-- Campo oculto -->
                    <input type="hidden" id="mesa_id" name="mesa_id" value="">

                    <!-- Fecha -->
                    <div>
                        <label for="fecha_reserva">Selecciona la fecha:</label>
                        <input type="date" id="fecha_reserva" name="fecha" required value="<?php echo date('Y-m-d'); ?>" onkeydown="return false">
                        <p class="mensaje-error" id="error-fecha"></p>
                    </div>

                    <!-- Hora -->
                    <div>
                        <label for="hora">Hora:</label>
                        <select id="hora" name="hora" required>
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
                        <p class="mensaje-error" id="error-hora"></p>
                    </div>

                    <!-- Comensales -->
                    <div>
                        <label for="comensales">Número de comensales:</label>
                        <select id="comensales" name="comensales">
                            <?php for ($i = 1; $i <= 10; $i++): ?>
                                <option value="<?= $i ?>" <?= $i == 2 ? 'selected' : '' ?>><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                        <p class="mensaje-error" id="error-comensales"></p>
                    </div>

                    <!-- Comanda -->
                    <div>
                        <p>¿Quieres realizar ya tu comanda?</p>
                        <label><input type="radio" name="comanda" value="1" required> Sí</label>
                        <label><input type="radio" name="comanda" value="0" required> No</label>
                        <p class="mensaje-error" id="error-comanda"></p>
                    </div>

                    <div>
                        <button type="submit" id="boton-reservar" name="reservar" class="btn-reservar">Reservar</button>
                    </div>
                </form>
            </section>
        <?php endif; ?>


        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservar'])):
            // Guardamos los datos en sesión
            $_SESSION['fecha'] = $_POST['fecha'];
            $_SESSION['hora'] = $_POST['hora'];
            $_SESSION['comensales'] = $_POST['comensales'];
            $_SESSION['comanda'] = $_POST['comanda'];

            // Consultamos las mesas disponibles
            $mesa = new Mesa();
            $idMesasDisponibles = $mesa->obtenerMesasDisponibles($_SESSION['fecha'], $_SESSION['hora'], $_SESSION['comensales']);
        ?>
            <h1 class="header_reserva">SELECCIONA TU MESA</h1>
            <section class="container_form">
                <p><strong>Fecha:</strong> <?= $_SESSION['fecha'] ?> | 
                   <strong>Hora:</strong> <?= $_SESSION['hora'] ?> | 
                   <strong>Comensales:</strong> <?= $_SESSION['comensales'] ?> | 
                   <strong>Comanda:</strong> <?= $_SESSION['comanda'] === '1' ? 'Sí' : 'No' ?>
                </p>
            </section>

            <section class="container_plano">
                <form action="/controllers/frontend/ReservaController.php" method="post">
                    <input type="hidden" name="mesa_id" id="mesa_id" value="">

                    <?php
                    // Estructura de mesas
                    $planoMesas = [
                        [true,  false, true,  false, true,  false],
                        [false, true,  false, true,  false, true],
                        [true,  false, true,  false, true,  false],
                    ];

                    $idMesa = 1;

                    echo '<table class="tabla">';

                    foreach ($planoMesas as $fila) {
                        echo '<tr class="fila">';
                        foreach ($fila as $mesaCelda) {
                            if ($mesaCelda) {
                                // Verificamos si la mesa está disponible
                                $disponible = in_array($idMesa, $idMesasDisponibles);
                                $estado = $disponible ? 'Disponible' : 'No disponible';
                                $clase = $disponible ? 'mesa-disponible' : 'mesa-no-disponible';

                                echo "<td class='celda $clase' id='mesa-$idMesa' title='Mesa $idMesa - $estado'>";
                                echo '<span class="silla1"></span>';
                                echo '<span class="silla2"></span>';
                                echo '<span class="mesa"></span>';
                                echo '<span class="silla3"></span>';
                                echo '<span class="silla4"></span>';
                                echo "<p class='estado-mesa'>Mesa $idMesa<br><strong>$estado</strong></p>";
                                echo '</td>';

                                $idMesa++;
                            } else {
                                echo '<td class="celda"></td>';
                            }
                        }
                        echo '</tr>';
                    }

                    echo '</table>';
                    ?>

                    <div>
                        <button type="submit" id="boton-confirmar-reserva" name="confirmarReserva" disabled>Confirmar reserva</button>
                    </div>
                </form>
            </section>

        <?php endif; ?>
    </main>

    <?php include_once __DIR__ . '/../partials/footer.php'; ?>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservar'])): ?>
        <script src="/assets/js/validacionMesa.js"></script>
    <?php endif; ?>
</body>
</html>
