<?php
@session_start();

use ModelsFrontend\Mesa;
use ModelsFrontend\Reserva;

require_once dirname(__DIR__, 2) . '/models/frontend/Mesa.php';
require_once dirname(__DIR__, 2) . '/models/frontend/Reserva.php';
?>
<!DOCTYPE html>
<html lang="en">

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
    <?php include_once __DIR__ . '/../partials/header.php'; ?>

    <main>
        <hr id="hr1">
        <!--<hr id="hr2">
        <hr id="hr3">-->
        <hr id="hr4">


        <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_POST['reservar']) || isset($_POST['modificarReserva'])):
            unset($_SESSION['modificar_reserva']);
            unset($_SESSION['fecha']);
            unset($_SESSION['hora_inicio']);
            unset($_SESSION['numero_comensales']);
            unset($_SESSION['comanda_previa']);
        ?>
            <h1 class="header_reserva">RESERVA CON NOSOTROS</h1>
            <section class="container_form">

                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" id="formulario-reserva" class="formulario">
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
                            <option value="1">1</option>
                            <option value="2" selected>2</option>
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

                    <!-- Comanda -->
                    <div>
                        <p>¿Quieres realizar ya tu comanda?</p>
                        <label><input type="radio" name="comanda_previa" value="1" required> Sí</label>
                        <label><input type="radio" name="comanda_previa" value="0" required> No</label>
                        <p class="mensaje-error" id="error-comanda" role="alert" aria-live="assertive"></p>
                    </div>

                    <!-- Botón -->
                    <?php if (!isset($_POST['modificarReserva'])): ?>
                        <div>
                            <button type="submit" id="boton-reservar" name="reservar" class="btn-reservar btn_reservar">Reservar</button>
                        </div>
                    <?php else: ?>
                        <div>
                            <button type="submit" id="boton-reservar" name="modificar" class="btn-reservar btn_reservar">Modificar reserva</button>
                        </div>
                    <?php endif; ?>
                </form>

            </section>

        <?php endif; ?>

        <?php



        if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['reservar']) || isset($_POST['modificar']))):

            /*if (isset($_POST['modificarReserva'])) {
                $_SESSION['fecha'] = $_POST['fecha_reserva'];
                $_SESSION['hora'] = $_POST['hora_reserva'];
                $_SESSION['comensales'] = $_POST['numero_comensales'];
                $_SESSION['comanda'] = $_POST['comanda_previa'];
            } */

            
                unset($_SESSION['fecha']);
                unset($_SESSION['hora_inicio']);
                unset($_SESSION['numero_comensales']);
                unset($_SESSION['comanda_previa']);
                $_SESSION['fecha'] = $_POST['fecha'];
                $_SESSION['hora_inicio'] = $_POST['hora_inicio'];
                $_SESSION['numero_comensales'] = $_POST['numero_comensales'];
                $_SESSION['comanda_previa'] = $_POST['comanda_previa'];
                //$_SESSION['numero_mesa'] = $_POST['numero_mesa'];
                //$_SESSION['codigo_reserva'] = $_POST['codigo_reserva'];
            



            $mesa = new Mesa();
            $idMesasDisponibles = $mesa->obtenerMesasDisponibles($_SESSION['fecha'], $_SESSION['hora_inicio'], $_SESSION['numero_comensales']);

        ?>
            <h1 class="header_reserva">RESERVA CON NOSOTROS</h1>
            <section class="container_form">
                <?php
                if (isset($_POST['reservar']) || isset($_POST['modificar'])) { ?>


                
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

                    <!-- Comanda -->
                    <div>
                        <p>¿Quieres realizar ya tu comanda?</p>
                        <label><input type="radio" name="comanda_previa" value="1" <?= $_SESSION['comanda_previa'] === '1' ? 'checked' : ''; ?>> Sí</label>
                        <label><input type="radio" name="comanda_previa" value="0" <?= $_SESSION['comanda_previa'] === '0' ? 'checked' : ''; ?>> No</label>

                        <p class="mensaje-error" id="error-comanda" role="alert" aria-live="assertive"></p>
                    </div>
                <?php } ?>

            </section>

            <section class="container_plano">

                <form action="/controllers/frontend/ReservaController.php" name="formulario-reserva" method="post">
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

                                echo "<td class=\"$clase\" title=\"Elige tu mesa\" id=\"$idMesa\">";
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

                    <div>
                        <?php if (isset($_POST['reservar'])): ?>
                            <p class="mensaje-error" id="error-mesa" role="alert" aria-live="assertive"></p>
                            <button type="submit" id="boton-confirmar-reserva" class="btn_reservar" name="confirmarReserva" disabled>Confirmar reserva</button>
                        <?php endif;
                        if (isset($_POST['modificar'])): ?>
                            <p class="mensaje-error" id="error-mesa" role="alert" aria-live="assertive"></p>
                            <button type="submit" id="boton-confirmar-reserva" class="btn_reservar" name="confirmarModificacionReserva" disabled>Modificar reserva</button>
                        <?php endif; ?>
                    </div>
                </form>
            </section>

        <?php endif; ?>
    </main>

    <?php include_once __DIR__ . '/../partials/footer.php'; ?>



    <!--  <script src="/assets/js/validacionReserva.js"></script> -->


    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['reservar']) || isset($_POST['modificar']))): ?>

        <script src="/assets/js/validacionMesa.js"></script>

    <?php endif; ?>



</body>


</html>