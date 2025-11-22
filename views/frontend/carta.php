<?php @session_start();

use ModelsAdmin\Producto;

require_once dirname(__DIR__, 2) . '/models/admin/Producto.php';
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
</head>


<body>
    <?php include_once __DIR__ . '/../partials/header.php'; ?>
    <main>

        <?php if (
            isset($_SESSION['id_usuario']) && isset($_SESSION['confirmarReserva']) && isset($_SESSION['comanda_previa']) && $_SESSION['comanda_previa'] === '1'
            || isset($_SESSION['modificar_orden']) && $_SESSION['modificar_orden'] === true || isset($_SESSION['confirmarModificacionReserva'])
            && $_SESSION['confirmarModificacionReserva'] === true && isset($_SESSION['mod_reserva_con_comanda']) && $_SESSION['mod_reserva_con_comanda'] == "1"
        ) { ?>
            <section class="container_form carta_container">
                <h2 class="titulo_form carta_titulo">CARTA</h2>

                <?php
                // --- BEBIDAS ---
                $productos = Producto::getProductos('bebida');
                echo '<div class="accordion">';
                echo '<div class="accordion-header">Bebidas</div>';
                echo '<div class="accordion-content">';

                // REFRESCOS
                echo '<div class="accordion-header">Refrescos</div>';
                echo '<div class="accordion-content">';
                foreach ($productos as $producto) {
                    if ($producto->productos['tipo_categoria'] === 'Refresco' && $producto->productos['modalidad_producto'] === 'Refresco' && $producto->getUdsStock() > 0) {
                        echo "<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='POST'>";
                        echo $producto->getNombreCorto() . " - " . number_format($producto->getPrecioUnitario(), 2, ',', '.') . " €";
                        echo "<input type='hidden' name='id_producto' value='" . $producto->getIdProducto() . "'>";
                        echo "<input type='hidden' name='nombre_corto' value='" . $producto->getNombreCorto() . "'>";
                        echo "<input type='hidden' name='precio_unitario' value='" . $producto->getPrecioUnitario() . "'>";
                        echo "<input type='submit' class='btn_añadir' value='Añadir' name='añadir'><br><br>";
                        echo "</form>";
                    }
                }
                echo '</div>';

                // CON ALCOHOL
                echo '<div class="accordion-header">Con alcohol</div>';
                echo '<div class="accordion-content">';
                foreach ($productos as $producto) {
                    if ($producto->productos['tipo_categoria'] === 'Con alcohol' && $producto->productos['modalidad_producto'] === 'Con alcohol' && $producto->getUdsStock() > 0) {
                        echo "<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='POST'>";
                        echo $producto->getNombreCorto() . " - " . number_format($producto->getPrecioUnitario(), 2, ',', '.') . " €";
                        echo "<input type='hidden' name='id_producto' value='" . $producto->getIdProducto() . "'>";
                        echo "<input type='hidden' name='nombre_corto' value='" . $producto->getNombreCorto() . "'>";
                        echo "<input type='hidden' name='precio_unitario' value='" . $producto->getPrecioUnitario() . "'>";
                        echo "<input type='submit' class='btn_añadir' value='Añadir' name='añadir'><br><br>";
                        echo "</form>";
                    }
                }
                echo '</div>';

                echo '</div>'; // fin Bebidas
                echo '</div>';

                // --- COMIDA ---
                $productos = Producto::getProductos('comida');
                echo '<div class="accordion">';
                echo '<div class="accordion-header">Comida</div>';
                echo '<div class="accordion-content">';

                // ENTRANTES
                echo '<div class="accordion-header">Entrantes</div>';
                echo '<div class="accordion-content">';
                foreach ($productos as $producto) {
                    if ($producto->productos['tipo_categoria'] === 'Tapa' && $producto->productos['modalidad_producto'] === 'Embutido' && $producto->getUdsStock() > 0) {
                        echo "<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='POST'>";
                        echo $producto->getNombreCorto() . " - " . number_format($producto->getPrecioUnitario(), 2, ',', '.') . " €";
                        echo "<input type='hidden' name='id_producto' value='" . $producto->getIdProducto() . "'>";
                        echo "<input type='hidden' name='nombre_corto' value='" . $producto->getNombreCorto() . "'>";
                        echo "<input type='hidden' name='precio_unitario' value='" . $producto->getPrecioUnitario() . "'>";
                        echo "<input type='submit' class='btn_añadir' value='Añadir' name='añadir'><br><br>";
                        echo "</form>";
                    }
                }
                echo '</div>';

                // RACIONES
                echo '<div class="accordion-header">Raciones</div>';
                echo '<div class="accordion-content">';

                // Variados
                echo '<div class="accordion-header">Variados</div>';
                echo '<div class="accordion-content">';
                foreach ($productos as $producto) {
                    if ($producto->productos['tipo_categoria'] === 'Tapa' && $producto->productos['modalidad_producto'] === 'Variado' && $producto->getUdsStock() > 0) {
                        echo "<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='POST'>";
                        echo $producto->getNombreCorto() . " - " . number_format($producto->getPrecioUnitario(), 2, ',', '.') . " €";
                        echo "<input type='hidden' name='id_producto' value='" . $producto->getIdProducto() . "'>";
                        echo "<input type='hidden' name='nombre_corto' value='" . $producto->getNombreCorto() . "'>";
                        echo "<input type='hidden' name='precio_unitario' value='" . $producto->getPrecioUnitario() . "'>";
                        echo "<input type='submit' class='btn_añadir' value='Añadir' name='añadir'><br><br>";
                        echo "</form>";
                    }
                }
                echo '</div>';

                // Carnes
                echo '<div class="accordion-header">Carnes</div>';
                echo '<div class="accordion-content">';
                foreach ($productos as $producto) {
                    if ($producto->productos['tipo_categoria'] === 'Ración' && $producto->productos['modalidad_producto'] === 'Carne' && $producto->getUdsStock() > 0) {
                        echo "<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='POST'>";
                        echo $producto->getNombreCorto() . " - " . number_format($producto->getPrecioUnitario(), 2, ',', '.') . " €";
                        echo "<input type='hidden' name='id_producto' value='" . $producto->getIdProducto() . "'>";
                        echo "<input type='hidden' name='nombre_corto' value='" . $producto->getNombreCorto() . "'>";
                        echo "<input type='hidden' name='precio_unitario' value='" . $producto->getPrecioUnitario() . "'>";
                        echo "<input type='submit' class='btn_añadir' value='Añadir' name='añadir'><br><br>";
                        echo "</form>";
                    }
                }
                echo '</div>';

                // Pescados
                echo '<div class="accordion-header">Pescados</div>';
                echo '<div class="accordion-content">';
                foreach ($productos as $producto) {
                    if ($producto->productos['tipo_categoria'] === 'Ración' && $producto->productos['modalidad_producto'] === 'Pescado' && $producto->getUdsStock() > 0) {
                        echo "<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='POST'>";
                        echo $producto->getNombreCorto() . " - " . number_format($producto->getPrecioUnitario(), 2, ',', '.') . " €";
                        echo "<input type='hidden' name='id_producto' value='" . $producto->getIdProducto() . "'>";
                        echo "<input type='hidden' name='nombre_corto' value='" . $producto->getNombreCorto() . "'>";
                        echo "<input type='hidden' name='precio_unitario' value='" . $producto->getPrecioUnitario() . "'>";
                        echo "<input type='submit' class='btn_añadir' value='Añadir' name='añadir'><br><br>";
                        echo "</form>";
                    }
                }
                echo '</div>';

                echo '</div>'; // fin Raciones

                echo '</div>'; // fin Comida
                echo '</div>';

                // --- POSTRES ---
                $productos = Producto::getProductos('postre');
                echo '<div class="accordion">';
                echo '<div class="accordion-header">Postres</div>';
                echo '<div class="accordion-content">';
                foreach ($productos as $producto) {
                    if ($producto->getUdsStock() > 0) {
                        echo "<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='POST'>";
                        echo $producto->getNombreCorto() . " - " . number_format($producto->getPrecioUnitario(), 2, ',', '.') . " €";
                        echo "<input type='hidden' name='id_producto' value='" . $producto->getIdProducto() . "'>";
                        echo "<input type='hidden' name='nombre_corto' value='" . $producto->getNombreCorto() . "'>";
                        echo "<input type='hidden' name='precio_unitario' value='" . $producto->getPrecioUnitario() . "'>";
                        echo "<input type='submit' class='btn_añadir' value='Añadir' name='añadir'><br><br>";
                        echo "</form>";
                    }
                }
                echo '</div>';
                echo '</div>';
                ?>
            </section>

            <?php

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['añadir'])) {
                $producto = [
                    'id_producto' => $_POST['id_producto'],
                    'nombre_corto' => $_POST['nombre_corto'],
                    'precio_unitario' => $_POST['precio_unitario']
                ];

                //Comprobamos que el carrito no existe para crearlo
                if (!isset($_SESSION['carrito'])) {
                    $_SESSION['carrito'] = array();
                }
                // Añadir el producto al carrito
                $_SESSION['carrito'][] = $producto;
            }
        } else { ?>
            <section class="container_form carta_container">
                <h2 class="titulo_form carta_titulo">CARTA</h2>

                <?php
                // --- BEBIDAS ---
                $productos = Producto::getProductos('bebida');
                echo '<div class="accordion">';
                echo '<div class="accordion-header">Bebidas</div>';
                echo '<div class="accordion-content">';

                // REFRESCOS
                echo '<div class="accordion-header">Refrescos</div>';
                echo '<div class="accordion-content">';
                foreach ($productos as $producto) {
                    if ($producto->productos['tipo_categoria'] === 'Refresco' && $producto->productos['modalidad_producto'] === 'Refresco' && $producto->getUdsStock() > 0) {
                        echo "<p>" . $producto->getNombreCorto() . " - " . number_format($producto->getPrecioUnitario(), 2, ',', '.') . " €</p>";
                    }
                }
                echo '</div>';

                // CON ALCOHOL
                echo '<div class="accordion-header">Con alcohol</div>';
                echo '<div class="accordion-content">';
                foreach ($productos as $producto) {
                    if ($producto->productos['tipo_categoria'] === 'Con alcohol' && $producto->productos['modalidad_producto'] === 'Con alcohol' && $producto->getUdsStock() > 0) {
                        echo "<p>" . $producto->getNombreCorto() . " - " . number_format($producto->getPrecioUnitario(), 2, ',', '.') . " €</p>";
                    }
                }
                echo '</div>';

                echo '</div>'; // fin Bebidas
                echo '</div>';

                // --- COMIDA ---
                $productos = Producto::getProductos('comida');
                echo '<div class="accordion">';
                echo '<div class="accordion-header">Comida</div>';
                echo '<div class="accordion-content">';

                // ENTRANTES
                echo '<div class="accordion-header">Entrantes</div>';
                echo '<div class="accordion-content">';
                foreach ($productos as $producto) {
                    if ($producto->productos['tipo_categoria'] === 'Tapa' && $producto->productos['modalidad_producto'] === 'Embutido' && $producto->getUdsStock() > 0) {
                        echo "<p>" . $producto->getNombreCorto() . " - " . number_format($producto->getPrecioUnitario(), 2, ',', '.') . " €</p>";
                    }
                }
                echo '</div>';

                // RACIONES
                echo '<div class="accordion-header">Raciones</div>';
                echo '<div class="accordion-content">';

                // VARIADOS
                echo '<div class="accordion-header">Variados</div>';
                echo '<div class="accordion-content">';
                foreach ($productos as $producto) {
                    if ($producto->productos['tipo_categoria'] === 'Tapa' && $producto->productos['modalidad_producto'] === 'Variado' && $producto->getUdsStock() > 0) {
                        echo "<p>" . $producto->getNombreCorto() . " - " . number_format($producto->getPrecioUnitario(), 2, ',', '.') . " €</p>";
                    }
                }
                echo '</div>';

                // CARNES
                echo '<div class="accordion-header">Carnes</div>';
                echo '<div class="accordion-content">';
                foreach ($productos as $producto) {
                    if ($producto->productos['tipo_categoria'] === 'Ración' && $producto->productos['modalidad_producto'] === 'Carne' && $producto->getUdsStock() > 0) {
                        echo "<p>" . $producto->getNombreCorto() . " - " . number_format($producto->getPrecioUnitario(), 2, ',', '.') . " €</p>";
                    }
                }
                echo '</div>';

                // PESCADOS
                echo '<div class="accordion-header">Pescados</div>';
                echo '<div class="accordion-content">';
                foreach ($productos as $producto) {
                    if ($producto->productos['tipo_categoria'] === 'Ración' && $producto->productos['modalidad_producto'] === 'Pescado' && $producto->getUdsStock() > 0) {
                        echo "<p>" . $producto->getNombreCorto() . " - " . number_format($producto->getPrecioUnitario(), 2, ',', '.') . " €</p>";
                    }
                }
                echo '</div>';

                echo '</div>'; // fin Raciones

                echo '</div>'; // fin Comida
                echo '</div>';

                // --- POSTRES ---
                $productos = Producto::getProductos('postre');
                echo '<div class="accordion">';
                echo '<div class="accordion-header">Postres</div>';
                echo '<div class="accordion-content">';
                foreach ($productos as $producto) {
                    if ($producto->getUdsStock() > 0) {
                        echo "<p>" . $producto->getNombreCorto() . " - " . number_format($producto->getPrecioUnitario(), 2, ',', '.') . " €</p>";
                    }
                }
                echo '</div>';
                echo '</div>';
                ?>
            </section>


        <?php }
        ?>
    </main>

    <?php
    include_once __DIR__ . '/../partials/footer.php'; ?>

    <script src="/proyecto_final_daw_dtj/assets/js/accordionCarta.js"></script>

</body>

</html>