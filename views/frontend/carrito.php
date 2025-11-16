<?php

session_start();
if (!isset($_SESSION['id_usuario'])) {

    header("Location: home");
    exit;
}

use ControllerFrontend\CarritoController;
use ModelsFrontend\Orden;
use ModelsFrontend\Reserva;



require_once dirname(__DIR__, 2) . '/config/DB.php';



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
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Restaurante XITO</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>

<body>
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
                                echo $producto['nombre_corto'] . " .... x" . "<span class='cantidad'>$cantidad</span><span class='subtotal'> ........ 
                                Subtotal: " . number_format($producto['precio_unitario'] * $cantidad, 2, ',', '.') . " €</span>";
                                echo " <button type='button' onclick='eliminar(\"" . $producto['id_producto'] . "\")'>X</button>";
                                echo "</div>";

                                break;
                            }
                        }
                    }

                    $pagarNuevoCarrito = false;
                    if ($precioTotalCarrito > 0) {
                        $nuevoPagoAdelantado = $precioTotalCarrito * 0.1;
                        echo "--------------------------\n";
                        echo "<div id='precioTotal'>Precio total del carrito: " . number_format($precioTotalCarrito, 2, ',', '.') . " €</div>\n";
                        echo "<div id='pagoAdelantado'>Precio a pagar por adelantado (10% del carrito): " .
                            number_format($nuevoPagoAdelantado, 2, ',', '.') . " €</div>\n";

                        echo "<form method='POST' id='formPagar' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
                        echo "<button type='submit' id='botonPagar' name='pagarCarrito'>Pagar</button>";
                        echo "<button type='submit' id='botonVaciarCarrito' name='vaciarCarrito'>Vaciar carrito</button>";
                        echo "</form>";

                        if ($pagarNuevoCarrito === false) {
                            $_SESSION['precioTotalCarrito'] = $precioTotalCarrito;
                            $_SESSION['nuevoPagoAdelantado'] = $nuevoPagoAdelantado;
                        }
                    } else {
                        echo "<div id='carritoVacio'>El carrito está vacío.</div>";
                    }
                } else {

                    echo "<div id='carritoVacio'>El carrito está vacío.</div>";
                }
            }

            if (!empty($_SESSION['carrito'])) {
            ?>

        </section>
        <section id="pago-stripe" class="container_form">
            <div id="card-container">
                <label for="cardholder-name">Nombre en la tarjeta</label>
                <input id="cardholder-name" type="text" placeholder="Ej: Juan Pérez" required>

                <div id="card-element"><!-- Stripe Card Element se montará aquí --></div>
                <div id="card-errors" role="alert" style="color:red;"></div>
            </div>
        </section>
    <?php } ?>

    </main>
    <?php include_once __DIR__ . '/../partials/footer.php'; ?>

    <script>
        // Clave pública de Stripe (modo prueba)
        const stripe = Stripe('<?php echo STRIPE_PUBLIC_KEY; ?>');

        const elements = stripe.elements();
        const card = elements.create('card');
        card.mount('#card-element');

        const botonPagar = document.getElementById('botonPagar');

        botonPagar.addEventListener('click', async () => {
            botonPagar.disabled = true;

            // Capturamos el nombre del titular
            const cardholderName = document.getElementById('cardholder-name').value.trim();

            if (!cardholderName) {
                document.getElementById('card-errors').textContent = 'Por favor, introduce el nombre en la tarjeta.';
                botonPagar.disabled = false;
                return;
            }

            // Creamos el PaymentIntent en el servidor
            const response = await fetch('crearPaymentIntent.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: cardholderName
                })
            });

            const data = await response.json();
            const clientSecret = data.clientSecret;

            if (!clientSecret) {
                document.getElementById('card-errors').textContent = 'Error al crear PaymentIntent';
                botonPagar.disabled = false;
                return;
            }

            // Confirmamos el pago con el nombre incluido
            const result = await stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: card,
                    billing_details: {
                        name: cardholderName
                    }
                }
            });

            if (result.error) {
                // Mostrar error al usuario
                document.getElementById('card-errors').textContent = result.error.message;
                botonPagar.disabled = false;
            } else {
                if (result.paymentIntent.status === 'succeeded') {
                    alert('Pago realizado con éxito!');

                    // Llamamos al backend para registrar la orden
                    await fetch('comprobacionesPago.php', {
                        method: 'POST'
                    });

                    // Redirigimos al perfil
                    window.location.href = '/views/frontend/miPerfil.php';
                }
            }
        });
    </script>

    <script src="/assets/js/validacionCarrito.js"></script>

</body>

</html>