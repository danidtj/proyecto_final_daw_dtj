<?php
session_start();
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
    <?php include_once __DIR__ . '/../partials/headerAdmin.php'; ?>
    <hr id="hr1">
    <hr id="hr4">
    <main>
        <section class="container_form">
            <h2 class="titulo_form">STOCK PRODUCTOS</h2>
            <?php
            require_once dirname(__DIR__, 2) . '/models/admin/Producto.php';

            use ModelsAdmin\Producto;

            ?>

            <table border="1">
                <tr>
                    <th>Nombre</th>
                    <th>Unidades</th>
                    <th>Precio</th>
                    <th>Código</th>
                    <th>Tipo</th>
                </tr>

                
                <?php

                if (isset($_POST['eliminarProducto'])) {
                    Producto::eliminarProducto($_POST['codigo_producto']);
                }

                $productos = Producto::getProductos('postre,bebida,comida');

                foreach ($productos as $producto) {
                    if ($producto->productos['nombre_producto'] === '' || $producto->productos['tipo_producto'] === '' || 
                    $producto->productos['codigo_producto'] === 'null' || $producto->productos['uds_producto'] === '' || 
                    $producto->productos['precio_producto'] === '') {
                        // Saltar producto si no tiene datos esenciales
                        continue;
                    }
                    echo "<tr>";
                    echo "<td>" . $producto->productos['nombre_producto'] . "</td>";
                    echo "<td>" . $producto->productos['uds_producto'] . "</td>";
                    echo "<td>" . $producto->productos['precio_producto'] . "</td>";
                    echo "<td>" . $producto->productos['codigo_producto'] . "</td>";
                    echo "<td>" . $producto->productos['tipo_producto'] . "</td>";
                    echo "<td>";
                    echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="POST">';
                    echo '<input type="hidden" name="codigo_producto" value="' . $producto->productos['codigo_producto'] . '">';
                    echo '<input type="submit" class="btn_crearProducto" value="X" name="eliminarProducto">';
                    echo '</form>';
                    echo "</td>";
                    echo "</tr>";
                }


                echo "</table>";


                ?>
                <br><a href="/views/admin/producto.php">Volver</a>
        </section>
    </main>
</body>

</html>