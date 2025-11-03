<?php

use ControllerFrontend\CarritoController;

@session_start();


require_once dirname(__DIR__, 2) . '/controllers/frontend/CarritoController.php';
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
                        echo "--------------------------\n";
                        echo "<div id='precioTotal'>Precio total del carrito: $" . $precioTotalCarrito . "</div>\n";
                    } else {
                        echo "<div id='carritoVacio'>El carrito está vacío.</div>";
                    }
                } else {
                    /*echo '<script>
        alert("Debes iniciar sesión para acceder a esta página.");
        window.location.href = "../../home";
    </script>';*/

                    // En caso de que JavaScript esté deshabilitado
                    /*header("Refresh: 3; URL=../../home"); // Espera 3 segundos y redirige
        exit();*/

                    $carritoController = new CarritoController();
                    $carritoController->mostrarVistaCarrito();
                }
            }







            /* --- SCRIPT JS PARA GESTIONAR ELIMINAR SIN RECARGAR Y ACTUALIZAR PRECIO --- */
            ?>

        </section>
    </main>
    <?php include_once __DIR__ . '/../partials/footer.php'; ?>

    <script>

    </script>
    <script src="/assets/js/validacionCarrito.js"></script>


</body>

</html>