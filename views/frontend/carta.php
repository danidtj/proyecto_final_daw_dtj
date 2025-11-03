<?php @session_start();

use ModelsAdmin\Producto;

require_once dirname(__DIR__, 2) . '/models/admin/Producto.php';
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

        <?php if (isset($_SESSION['id_usuario']) && isset($_SESSION['confirmarReserva']) || $_SESSION['comanda_previa'] === '1'
        || isset($_SESSION['confirmarModificacionReserva'])) { ?>
            <section class="container_form">
                <h2 class="titulo_form">CARTA</h2>
                <!--<form action="/controllers/frontend/CarritoController.php" method="post">-->


                <?php

                //USAR AJAX PARA RECARGAR LA PÁGINA
                $productos = Producto::getProductos('bebida');

                echo "<h3>&nbsp;&nbsp;&nbspBebidas</h3><br>";
                //REFRESCOS
                echo "<h4>&nbsp;&nbsp;&nbspRefrescos</h4><br>";
                foreach ($productos as $producto) {

                    if ($producto->productos['tipo_categoria'] === 'Refresco') {
                        echo "<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='POST'>";
                        //echo "<input type=\"checkbox\" name=\"codigo_producto\" id=\"" . $producto->getNombreProducto() . "\" value=\"" . $producto->getCodigoProducto() . "\"> ";
                        //echo "<label for=\"" . $producto->getNombreProducto() . "\">" . $producto->getNombreProducto() . "</label> <span class=\"precio_producto\">...................." . $producto->getPrecioProducto() . " €</span><br>";
                        //echo "<input type=\"hidden\" name=\"productosCarrito[nombre_producto]" . $producto->getCodigoProducto() . "\" value=\"" . htmlspecialchars($producto->getNombreProducto(), ENT_QUOTES, 'UTF-8') . "\">";
                        //echo "<input type=\"hidden\" name=\"productosCarrito[precio_producto]" . $producto->getCodigoProducto() . "\" value=\"" . htmlspecialchars($producto->getPrecioProducto(), ENT_QUOTES, 'UTF-8') . "\">";
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $producto->getNombreCorto() . "\n";
                        echo "............" . $producto->getPrecioUnitario() . "\n";
                        echo "<input type='hidden' name='id_producto' value='" . $producto->getIdProducto() . "'>";
                        echo "<input type='hidden' name='nombre_corto' value='" . $producto->getNombreCorto() . "'>";
                        echo "<input type='hidden' name='precio_unitario' value='" . $producto->getPrecioUnitario() . "'>";
                        echo "<input type='submit' value='Añadir' name='añadir'><br><br>";
                        echo "</form>";
                    }
                }

                //BEBIDAS ALCOHÓLICAS
                echo "<h4>&nbsp;&nbsp;&nbspCon alcohol</h4><br>";
                foreach ($productos as $producto) {

                    if ($producto->productos['tipo_categoria'] === 'Con alcohol') {
                        echo "<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='POST'>";
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $producto->getNombreCorto() . "\n";
                        echo "............" . $producto->getPrecioUnitario() . "\n";
                        echo "<input type='hidden' name='id_producto' value='" . $producto->getIdProducto() . "'>";
                        echo "<input type='hidden' name='nombre_corto' value='" . $producto->getNombreCorto() . "'>";
                        echo "<input type='hidden' name='precio_unitario' value='" . $producto->getPrecioUnitario() . "'>";
                        echo "<input type='submit' value='Añadir' name='añadir'><br><br>";
                        echo "</form>";
                    }
                }

                //COMIDA
                echo "<h3>&nbsp;&nbsp;&nbspComida</h3><br>";
                $productos = Producto::getProductos('comida');
                //ENTRANTES
                echo "<h4>&nbsp;&nbsp;&nbspEntrantes</h4><br>";
                foreach ($productos as $producto) {

                    if ($producto->productos['tipo_categoria'] === 'Tapa') {
                        echo "<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='POST'>";
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $producto->getNombreCorto() . "\n";
                        echo "............" . $producto->getPrecioUnitario() . "\n";
                        echo "<input type='hidden' name='id_producto' value='" . $producto->getIdProducto() . "'>";
                        echo "<input type='hidden' name='nombre_corto' value='" . $producto->getNombreCorto() . "'>";
                        echo "<input type='hidden' name='precio_unitario' value='" . $producto->getPrecioUnitario() . "'>";
                        echo "<input type='submit' value='Añadir' name='añadir'><br><br>";
                        echo "</form>";
                    }
                }
                //RACIONES Y VARIADOS
                echo "<h4>&nbsp;&nbsp;&nbspRaciones</h4><br>";
                echo "<h5>&nbsp;&nbsp;&nbspVariados</h5><br>";
                foreach ($productos as $producto) {
                    if ($producto->productos['tipo_categoria'] === 'Tapa' && $producto->productos['modalidad_producto'] === 'Variado') {
                        echo "<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='POST'>";
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $producto->getNombreCorto() . "\n";
                        echo "............" . $producto->getPrecioUnitario() . "\n";
                        echo "<input type='hidden' name='id_producto' value='" . $producto->getIdProducto() . "'>";
                        echo "<input type='hidden' name='nombre_corto' value='" . $producto->getNombreCorto() . "'>";
                        echo "<input type='hidden' name='precio_unitario' value='" . $producto->getPrecioUnitario() . "'>";
                        echo "<input type='submit' value='Añadir' name='añadir'><br><br>";
                        echo "</form>";
                    }
                }
                //RACIONES Y CARNES
                echo "<h5>&nbsp;&nbsp;&nbspCarnes</h5><br>";
                foreach ($productos as $producto) {
                    if ($producto->productos['tipo_categoria'] === 'Ración' && $producto->productos['modalidad_producto'] === 'Carne') {
                        echo "<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='POST'>";
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $producto->getNombreCorto() . "\n";
                        echo "............" . $producto->getPrecioUnitario() . "\n";
                        echo "<input type='hidden' name='id_producto' value='" . $producto->getIdProducto() . "'>";
                        echo "<input type='hidden' name='nombre_corto' value='" . $producto->getNombreCorto() . "'>";
                        echo "<input type='hidden' name='precio_unitario' value='" . $producto->getPrecioUnitario() . "'>";
                        echo "<input type='submit' value='Añadir' name='añadir'><br><br>";
                        echo "</form>";
                    }
                }
                //RACIONES Y PESCADOS
                echo "<h5>&nbsp;&nbsp;&nbspPescados</h5><br>";
                foreach ($productos as $producto) {
                    if ($producto->productos['tipo_categoria'] === 'Ración' && $producto->productos['modalidad_producto'] === 'Pescado') {
                        echo "<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='POST'>";
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $producto->getNombreCorto() . "\n";
                        echo "............" . $producto->getPrecioUnitario() . "\n";
                        echo "<input type='hidden' name='id_producto' value='" . $producto->getIdProducto() . "'>";
                        echo "<input type='hidden' name='nombre_corto' value='" . $producto->getNombreCorto() . "'>";
                        echo "<input type='hidden' name='precio_unitario' value='" . $producto->getPrecioUnitario() . "'>";
                        echo "<input type='submit' value='Añadir' name='añadir'><br><br>";
                        echo "</form>";
                    }
                }
                //POSTRES
                echo "<h3>&nbsp;&nbsp;&nbspPostres</h3><br>";
                $productos = Producto::getProductos('postre');
                foreach ($productos as $producto) {
                    echo "<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='POST'>";
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $producto->getNombreCorto() . "\n";
                    echo "............" . $producto->getPrecioUnitario() . "\n";
                    echo "<input type='hidden' name='id_producto' value='" . $producto->getIdProducto() . "'>";
                    echo "<input type='hidden' name='nombre_corto' value='" . $producto->getNombreCorto() . "'>";
                    echo "<input type='hidden' name='precio_unitario' value='" . $producto->getPrecioUnitario() . "'>";
                    echo "<input type='submit' value='Añadir' name='añadir'><br><br>";
                    echo "</form>";
                }

                ?>
                <br>

                <!--<input type="submit" value="Enviar" name="eleccionCarta">
                </form>-->
            </section>
            <?php

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['añadir'])) {
                $producto = [
                    'id_producto' => $_POST['id_producto'],
                    'nombre_corto' => $_POST['nombre_corto'],
                    'precio_unitario' => $_POST['precio_unitario']
                ];

                // Añadir el producto al carrito
                $_SESSION['carrito'][] = $producto;
            }
        } else { ?>
            <section class="container_form">
                <h2 class="titulo_form">CARTA</h2>

                <?php

                $productos = Producto::getProductos('bebida');

                echo "<h3>&nbsp;&nbsp;&nbspBebidas</h3><br>";
                //REFRESCOS
                echo "<h4>&nbsp;&nbsp;&nbspRefrescos</h4><br>";
                foreach ($productos as $producto) {

                    if ($producto->productos['tipo_categoria'] === 'Refresco') {
                        echo "<p>" . $producto->getNombreCorto() . " - " . $producto->getPrecioUnitario() . "</p><br>";
                    }
                }

                //BEBIDAS ALCOHÓLICAS
                echo "<h4>&nbsp;&nbsp;&nbspCon alcohol</h4><br>";
                foreach ($productos as $producto) {

                    if ($producto->productos['tipo_categoria'] === 'Con alcohol') {
                        echo "<p>" . $producto->getNombreCorto() . " - " . $producto->getPrecioUnitario() . "</p><br>";
                    }
                }

                //COMIDA
                echo "<h3>&nbsp;&nbsp;&nbspComida</h3><br>";

                $productos = Producto::getProductos('comida');

                //ENTRANTES
                echo "<h4>&nbsp;&nbsp;&nbspEntrantes</h4><br>";

                foreach ($productos as $producto) {
                    if ($producto->productos['tipo_categoria'] === 'Tapa') {
                        echo "<p>" . $producto->getNombreCorto() . " - " . $producto->getPrecioUnitario() . "</p><br>";
                    }
                }

                //RACIONES Y VARIADOS
                echo "<h4>&nbsp;&nbsp;&nbspRaciones</h4><br>";
                echo "<h5>&nbsp;&nbsp;&nbspVariados</h5><br>";

                foreach ($productos as $producto) {
                    if ($producto->productos['tipo_categoria'] === 'Tapa' && $producto->productos['modalidad_producto'] === 'Variado') {
                        echo "<p>" . $producto->getNombreCorto() . " - " . $producto->getPrecioUnitario() . "</p><br>";
                    }
                }

                //RACIONES Y CARNES
                echo "<h5>&nbsp;&nbsp;&nbspCarnes</h5><br>";

                foreach ($productos as $producto) {
                    if ($producto->productos['tipo_categoria'] === 'Ración' && $producto->productos['modalidad_producto'] === 'Carne') {
                        echo "<p>" . $producto->getNombreCorto() . " - " . $producto->getPrecioUnitario() . "</p><br>";
                    }
                }

                //RACIONES Y PESCADOS
                echo "<h5>&nbsp;&nbsp;&nbspPescados</h5><br>";

                foreach ($productos as $producto) {
                    if ($producto->productos['tipo_categoria'] === 'Ración' && $producto->productos['modalidad_producto'] === 'Pescado') {
                        echo "<p>" . $producto->getNombreCorto() . " - " . $producto->getPrecioUnitario() . "</p><br>";
                    }
                }

                //POSTRES
                echo "<h3>&nbsp;&nbsp;&nbspPostres</h3><br>";

                $productos = Producto::getProductos('postre');

                foreach ($productos as $producto) {
                    echo "<p>" . $producto->getNombreCorto() . " - " . $producto->getPrecioUnitario() . "</p><br>";
                }
                ?>

            </section>

        <?php }
        ?>
    </main>

    <?php include_once __DIR__ . '/../partials/footer.php'; ?>

</body>

</html>