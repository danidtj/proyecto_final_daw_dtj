<?php




use ControllerFrontend\CarritoController;
use ModelsFrontend\Orden;
use ModelsFrontend\Reserva;

@session_start();


require_once dirname(__DIR__, 2) . '/controllers/frontend/CarritoController.php';
require_once dirname(__DIR__, 2) . '/models/frontend/Orden.php';
require_once dirname(__DIR__, 2) . '/models/frontend/Reserva.php';
$orden = new Orden();
$reserva = new Reserva();
$carritoController = new CarritoController();
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
    <script src="https://js.stripe.com/v3/"></script>
</head>

<body>
    <hr id="hr1">
    <hr id="hr4">
    <?php

    include_once __DIR__ . '/../partials/header.php';

    ?>
    <main>
        <section class="container_form">
            <h2 class="titulo_form">CARRITO</h2>
            <?php

            if (session_status() === PHP_SESSION_ACTIVE) {

                if (isset($_SESSION['id_usuario']) && isset($_SESSION['carrito'])) {

                    $productosCarrito = $_SESSION['carrito'];
                    $precioTotalCarrito = 0;

                    //array_count_values para contar las cantidades de cada producto en la cesta y array_column para obtener una columna específica del ID
                    $resultado = array_count_values(array_column($productosCarrito, 'id_producto'));

                    foreach ($resultado as $id => $cantidad) {
                        foreach ($productosCarrito as $producto) {
                            if ($producto['id_producto'] == $id) {
                                $precioTotalCarrito += ($producto['precio_unitario'] * $cantidad);

                                // Sustituimos el formulario tradicional por un botón AJAX
                                echo "<div id='producto-{$producto['id_producto']}'>";
                                echo $producto['nombre_corto'] . " .... x" . "<span class='cantidad'>$cantidad</span><span class='subtotal'> ........ Subtotal: $" . ($producto['precio_unitario'] * $cantidad) . "</span>";
                                echo "<button type='button' onclick='eliminar(\"" . $producto['id_producto'] . "\")'>X</button>";
                                echo "</div>";

                                break;
                            }
                        }
                    }

                    if ($precioTotalCarrito > 0) {
                        $nuevoPagoAdelantado = $precioTotalCarrito * 0.1;
                        echo "--------------------------\n";
                        echo "<div id='precioTotal'>Precio total del carrito: $" . $precioTotalCarrito . "</div>\n";
                        echo "<div id='pagoAdelantado'>Precio a pagar por adelantado (10% del carrito): $" . $nuevoPagoAdelantado . "</div>\n";
                    } else {
                        echo "<div id='carritoVacio'>El carrito está vacío.</div>";
                    }


                    //Botón para proceder a la compra
                    if ($precioTotalCarrito > 0) {

                        echo "<form method='POST' id='formPagar' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>

                        <button type='submit' id='botonPagar' name='pagarCarrito'>Pagar</button>
                      </form>";
                    }


                    if (isset($_POST['pagarCarrito'])) {

                        if (isset($_SESSION['modificar_orden']) && $_SESSION['modificar_orden'] === true && isset($_SESSION['orden_original'])) {
                            foreach ($_SESSION['carrito'] as $producto) {
                                foreach ($_SESSION['orden_original'] as $ordenOriginal) {
                                    $orden->modificarOrdenPorCodigoOrden(
                                        $precioTotalCarrito,
                                        $nuevoPagoAdelantado,
                                        $ordenOriginal['id_orden'],
                                        $producto['id_producto'],
                                        array_count_values(array_column($_SESSION['carrito'], 'id_producto'))[$producto['id_producto']] ?? 0,
                                        $ordenOriginal['id_reserva'],
                                        $_SESSION['carrito']
                                    );
                                }
                            }
                            unset($_SESSION['confirmarModificacionReserva']);
                            unset($_SESSION['modificar_orden']);
                            unset($_SESSION['orden_original']);
                            unset($_SESSION['carrito']); // Vaciamos el carrito después de modificar la orden
                            header("Location: /views/frontend/miPerfil.php");
                            exit();
                        } else {
                            $codigo_reserva = $reserva->realizarReserva(
                                $_SESSION['fecha'],
                                $_SESSION['hora_inicio'],
                                $_SESSION['numero_comensales'],
                                $_SESSION['comanda_previa'],
                                $_SESSION['mesa_id'],
                                $_SESSION['id_usuario']
                            );

                            //Almacenamos el id de la nueva reserva en session
                            $_SESSION['id_reserva_nueva'] = $codigo_reserva;

                            $idOrdenCreada = $orden->crearOrden($_SESSION['id_reserva_nueva'], 'Tarjeta de crédito', $precioTotalCarrito, $nuevoPagoAdelantado, $_SESSION['carrito']);
                            unset($_SESSION['confirmarReserva']);
                            unset($_SESSION['comanda_previa']);
                            unset($_SESSION['carrito']); // Vaciamos el carrito después de crear la orden
                            unset($_SESSION['id_reserva_nueva']);
                            header("Location: /views/frontend/miPerfil.php");
                            exit();
                        }
                    }
                } else {
                    /*echo '<script>
        alert("Debes iniciar sesión para acceder a esta página.");
        window.location.href = "../../home";
    </script>';*/

                    // En caso de que JavaScript esté deshabilitado
                    /*header("Refresh: 3; URL=../../home"); // Espera 3 segundos y redirige
        exit();*/

                    echo "<div id='carritoVacio'>El carrito está vacío.</div>";
                }
            }







            /* --- SCRIPT JS PARA GESTIONAR ELIMINAR SIN RECARGAR Y ACTUALIZAR PRECIO --- */
            ?>

        </section>
        <section class="container_form">
            <div id="card-container">
                <div id="card-element"><!-- Stripe Card Element se montará aquí --></div>
                <div id="card-errors" role="alert" style="color:red;"></div>
            </div>
        </section>

    </main>
    <?php include_once __DIR__ . '/../partials/footer.php'; ?>

    <script>
        // Clave pública de Stripe (modo prueba)
        const stripe = Stripe('pk_test_51SMCTFC5kWSf4beJPvCblJMqF4QJaefwzvuun7PTsCezbS1fh9iPYGw72uVJ0QE5NrveHW1hOJSZbKltpFxQERYB00wN4miWCQ');

        const elements = stripe.elements();
        const card = elements.create('card');
        card.mount('#card-element');

        const botonPagar = document.getElementById('botonPagar');

        botonPagar.addEventListener('click', async () => {
            botonPagar.disabled = true;

            // Creamos PaymentIntent en el servidor
            const response = await fetch('crearPaymentIntent.php', {
                method: 'POST'
            });

            const data = await response.json();
            const clientSecret = data.clientSecret;

            if (!clientSecret) {
                document.getElementById('card-errors').textContent = 'Error al crear PaymentIntent';
                botonPagar.disabled = false;
                return;
            }

            // Confirmamos el pago
            const result = await stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: card
                }
            });

            if (result.error) {
                // Mostrar error al usuario
                document.getElementById('card-errors').textContent = result.error.message;
                botonPagar.disabled = false;
            } else {
                if (result.paymentIntent.status === 'succeeded') {
                    // Pago exitoso
                    alert('Pago realizado con éxito!');

                    // Vaciar carrito en el servidor
                    await fetch('vaciarCarrito.php', {
                        method: 'POST'
                    });
                    // Aquí puedes enviar formulario o redirigir
                    window.location.href = '/views/frontend/miPerfil.php';
                }
            }
        });
    </script>
    <script src="/assets/js/validacionCarrito.js"></script>


</body>

</html>