<?php
@session_start();

if (!isset($_SESSION['id_usuario'])) {

    header("Location: /proyecto_final_daw_dtj/views/frontend/index.php");
    exit;
}

use ModelsFrontend\Mesa;
use ModelsFrontend\Orden;
use ModelsFrontend\Reserva;

require_once dirname(__DIR__, 2) . '/models/frontend/Mesa.php';
require_once dirname(__DIR__, 2) . '/models/frontend/Orden.php';
require_once dirname(__DIR__, 2) . '/models/frontend/Reserva.php';

$orden = new Orden();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante XITO</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <?php include_once __DIR__ . '/../partials/header.php'; ?>

    <main>

        <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_POST['reservar']) || isset($_POST['modificarReserva'])):
            unset($_SESSION['modificar_reserva']);
            unset($_SESSION['fecha']);
            unset($_SESSION['hora_inicio']);
            unset($_SESSION['numero_comensales']);

            unset($_SESSION['id_mesa']);

            unset($_SESSION['id_reserva_nueva']);
            unset($_SESSION['codigo_reserva']);
            unset($_SESSION['idOrdenCreada']);
            unset($_SESSION['confirmarReserva']);
            unset($_SESSION['confirmarModificacionReserva']);

        ?>
            <h1 class="header_reserva reserva_header">RESERVA CON NOSOTROS</h1>
            <section class="container_form reserva_container">

                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" id="formulario-reserva" class="formulario reserva_formulario">
                    <!-- Campo oculto de mesa -->
                    <input type="hidden" id="mesa_id" name="mesa_id" value="">

                    <!-- Fecha -->
                    <div>
                        <label for="fecha">Selecciona la fecha:</label>
                        <input type="date" id="fecha" name="fecha" title="Elige la fecha" onkeydown="return false" required value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>">

                        <p class="mensaje-error" id="error-fecha" role="alert" aria-live="assertive"></p>
                    </div>

                    <!-- Hora -->
                    <div>
                        <label for="hora_inicio">Hora:</label>
                        <select id="hora_inicio" name="hora_inicio" required>
                            <optgroup label="Mediodía">
                                <option value="13:28">13:28</option>
                                <option value="14:00">14:00</option>
                                <option value="14:30">14:30</option>
                                <option value="15:00">15:00</option>
                                <option value="15:30">15:30</option>
                                <option value="16:00">16:00</option>
                                <option value="16:30">16:30</option>
                                <option value="17:00">17:00</option>
                            </optgroup>
                            <optgroup label="Noche">
                                <option value="20:00">20:00</option>
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
                        <label for="numero_comensales">Comensales:</label>
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


                    <?php
                    
                    if (
                        isset($_POST['modificarReserva']) && isset($_SESSION['mod_reserva_sin_comanda']) &&
                        $_SESSION['mod_reserva_sin_comanda'] == "0"
                    ) {
                    ?>
                        <!-- Comanda -->
                        <div>
                            <p>¿Quieres realizar ya tu orden?</p>
                            <label><input type="radio" name="comanda_previa" value="1" required> Sí</label>
                            <label><input type="radio" name="comanda_previa" value="0" required> No</label>
                            <p>Abono del 10% por adelantado.</p>
                            <p class="mensaje-error" id="error-comanda" role="alert" aria-live="assertive"></p>
                        </div>
                    <?php
                    }
                    if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_POST['reservar'])) {
                    ?>
                        <!-- Comanda -->
                        <div>
                            <p>¿Quieres realizar ya tu orden?</p>
                            <label><input type="radio" name="comanda_previa" value="1" required> Sí</label>
                            <label><input type="radio" name="comanda_previa" value="0" required> No</label>
                            <p>Abono del 10% por adelantado.</p>
                            <p class="mensaje-error" id="error-comanda" role="alert" aria-live="assertive"></p>
                        </div>
                    <?php
                    }

                    if (!isset($_POST['modificarReserva'])):
                    ?>
                        <div class="reservas_botones">
                            <button type="submit" id="boton-reservar" name="reservar" class="btn-reservar btn_reservar">Reservar</button>
                        </div>
                    <?php else:
                    ?>
                        <div class="reservas_botones">
                            <button type="submit" id="boton-reservar" name="modificar" class="btn-reservar btn_reservar">Modificar reserva</button>
                        </div>
                    <?php endif;
                    ?>
                </form>

            </section>

        <?php endif; ?>

        <?php



        if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['reservar']) || isset($_POST['modificar']))):


            if (isset($_POST['modificar'])) {

                unset($_SESSION['fecha']);
                unset($_SESSION['hora_inicio']);
                unset($_SESSION['numero_comensales']);
                unset($_SESSION['comanda_previa']);

                if (isset($_POST['comanda_previa']) && $_POST['comanda_previa'] == "0") {
                    $_SESSION['mod_reserva_sin_comanda'] = $_POST['comanda_previa'];
                } elseif (isset($_POST['comanda_previa']) && $_POST['comanda_previa'] == "1") {
                    $_SESSION['mod_reserva_con_comanda'] = "1";
                } else {
                    unset($_SESSION['mod_reserva_sin_comanda']);
                    $_SESSION['mod_reserva_con_comanda'] = "0";
                    
                }
            } else {
                $_SESSION['comanda_previa'] = $_POST['comanda_previa'];
            }

            $_SESSION['fecha'] = $_POST['fecha'];
            $_SESSION['hora_inicio'] = $_POST['hora_inicio'];
            $_SESSION['numero_comensales'] = $_POST['numero_comensales'];



            $mesa = new Mesa();
            $idMesasDisponibles = $mesa->obtenerMesasDisponibles($_SESSION['fecha'], $_SESSION['hora_inicio'], $_SESSION['numero_comensales']);

        ?>
            <h1 class="header_reserva reserva_header">RESERVA CON NOSOTROS</h1>
            <section class="container_form reserva_container">
                <div class="reserva_informacion">
                    <?php
                    if (isset($_POST['reservar']) || isset($_POST['modificar'])) { ?>



                        <!-- Fecha -->
                        <div>
                            <label for="fecha_reserva">Selecciona la fecha:</label>
                            <?php echo "<input type='date' id='fecha_reserva' name='fecha' title='Elige la fecha' value='" . $_SESSION['fecha'] . "' onkeydown='return false' min='" . date('Y-m-d') . "'>"; ?>
                            <p class="mensaje-error" id="error-fecha" role="alert" aria-live="assertive"></p>
                        </div>

                        <!-- Hora -->

                        <div>
                            <label for="hora_inicio_reserva">Hora:</label>
                            <select id="hora_inicio_reserva" name="hora_inicio" required>
                                <option value="<?= $_SESSION['hora_inicio']; ?>" selected><?= $_SESSION['hora_inicio']; ?></option>
                            </select>
                            <p class="mensaje-error" id="error-hora" role="alert" aria-live="assertive"></p>
                        </div>

                        <!-- Comensales -->
                        <div>
                            <label for="numero_comensales_reserva">Comensales:</label>
                            <select id="numero_comensales_reserva" name="numero_comensales">
                                <option value="<?= $_SESSION['numero_comensales']; ?>" selected><?= $_SESSION['numero_comensales']; ?></option>
                            </select>


                            <p class="mensaje-error" id="error-comensales" role="alert" aria-live="assertive"></p>
                        </div>

                        <!-- Comanda -->
                        <?php if (isset($_POST['modificar']) && isset($_SESSION['mod_reserva_sin_comanda']) && $_SESSION['mod_reserva_sin_comanda'] == "0") { ?>
                            <div>
                                <p>¿Quieres realizar ya tu orden?</p>
                                <label><input type="radio" name="comanda_previa" value="1" <?= $_SESSION['mod_reserva_sin_comanda'] == '1' ? 'checked' : ''; ?>> Sí</label>
                                <label><input type="radio" name="comanda_previa" value="0" <?= $_SESSION['mod_reserva_sin_comanda'] == '0' ? 'checked' : ''; ?>> No</label>

                                <p class="mensaje-error" id="error-comanda" role="alert" aria-live="assertive"></p>
                            </div>

                        <?php } elseif (isset($_POST['modificar']) && isset($_SESSION['mod_reserva_con_comanda']) && $_SESSION['mod_reserva_con_comanda'] == "1") { ?>
                            <div>
                                <p>¿Quieres realizar ya tu orden?</p>
                                <label><input type="radio" name="comanda_previa" value="1" <?= $_SESSION['mod_reserva_con_comanda'] == '1' ? 'checked' : ''; ?>> Sí</label>
                                <label><input type="radio" name="comanda_previa" value="0" <?= $_SESSION['mod_reserva_con_comanda'] == '0' ? 'checked' : ''; ?>> No</label>

                                <p class="mensaje-error" id="error-comanda" role="alert" aria-live="assertive"></p>
                            </div>
                            <?php

                        } else {
                            if (!isset($_POST['modificar'])) {
                            ?>
                                <!-- Comanda -->
                                <div>
                                    <p>¿Quieres realizar ya tu orden?</p>
                                    <label><input type="radio" name="comanda_previa" value="1" <?= $_SESSION['comanda_previa'] == '1' ? 'checked' : ''; ?>> Sí</label>
                                    <label><input type="radio" name="comanda_previa" value="0" <?= $_SESSION['comanda_previa'] == '0' ? 'checked' : ''; ?>> No</label>

                                    <p class="mensaje-error" id="error-comanda" role="alert" aria-live="assertive"></p>
                                </div>
                    <?php }
                        }
                    } ?>
                </div>
            </section>

            <section class="container_plano reserva_container_plano">

                <form action="/proyecto_final_daw_dtj/controllers/frontend/ReservaController.php" name="formulario-reserva" method="post">
                    <!-- Para trasladar el ID de la mesa seleccionada -->
                    <input type="hidden" name="mesa_id" id="mesa_id" value="">
                    <div class="scroll-tabla">
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
                                    echo '<span class="mesa mesa_reservar"></span>';
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
                    </div>
                    <div class="reserva_boton_modificar">
                        <?php if (isset($_POST['reservar'])): ?>
                            <p class="mensaje-error" id="error-mesa" role="alert" aria-live="assertive"></p>
                            <button type="submit" id="boton-confirmar-reserva" class="btn_reservar boton-confirmar-reserva" name="confirmarReserva" disabled>Confirmar reserva</button>
                        <?php endif;
                        if (isset($_POST['modificar'])): ?>
                            <p class="mensaje-error" id="error-mesa" role="alert" aria-live="assertive"></p>
                            <button type="submit" id="boton-confirmar-modificacion" class="btn_reservar boton-confirmar-reserva" name="confirmarModificacionReserva" disabled>Modificar reserva</button>
                        <?php endif; ?>
                    </div>
                </form>
            </section>

        <?php endif; ?>
    </main>

    <?php include_once __DIR__ . '/../partials/footer.php'; ?>



    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['reservar']) || isset($_POST['modificar']) || isset($_POST['confirmarModificacionReserva']))): ?>

        <script src="/proyecto_final_daw_dtj/assets/js/validacionMesa.js"></script>


    <?php endif; ?>

    <script src="/proyecto_final_daw_dtj/assets/js/horasReserva.js"></script>
</body>


</html>