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
            <h2 class="titulo_form">STOCK BEBIDAS</h2>
            <?php
            require_once dirname(__DIR__, 2) . '/models/admin/Bebida.php';

            use ModelsAdmin\Producto;

            ?>

            <table border="1" cellpadding="5" cellspacing="0">
                <tr>
                    <th>Nombre</th>
                    <th>Unidades</th>
                    <th>Precio</th>
                    <th>Código</th>
                    <th>Tipo</th>
                </tr>

                <?php

                if (isset($_POST['eliminarBebida'])) {
                    Producto::eliminarProducto($_POST['codigo_producto']);
                }

                $bebidas = Producto::getProductos('bebida');

                foreach ($bebidas as $bebida) {
                    echo "<tr>";
                    echo "<td>" . $bebida->productos['nombre_corto'] . "</td>";
                    echo "<td>" . $bebida->productos['uds_stock'] . "</td>";
                    echo "<td>" . $bebida->productos['precio_unitario'] . "</td>";
                    echo "<td>" . $bebida->productos['id_producto'] . "</td>";
                    echo "<td>" . $bebida->productos['tipo_categoria'] . "</td>";
                    echo "<td>";
                    echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="POST">';
                    echo '<input type="hidden" name="codigo_producto" value="' . $bebida->productos['id_producto'] . '">';
                    echo '<input type="submit" value="X" name="eliminarBebida" style="background-color:red; border:none; color:white; cursor:pointer;">';
                    echo '</form>';
                    echo "</td>";
                    echo "</tr>";
                }

                echo "</table>";


                ?>
        </section>

        <section class="container_form">
            <h2 class="titulo_form">MODIFICAR STOCK BEBIDAS</h2>
            <form action="/controllers/admin/ProductoController.php" method="post">

                <table border="1" cellpadding="5" cellspacing="0">
                    <thead>
                        <th>Refresco</th>
                        <th>Uds</th>
                        <th>Precio</th>
                        <th>Tipo</th>
                    </thead>
                    <tbody>
                        <?php foreach ($bebidas as $index => $bebida): ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars($bebida->productos['nombre_corto']) ?>
                                    <!-- Inputs ocultos -->
                                    <input type="hidden" name="bebidas[<?= $index ?>][id_producto]" value="<?= htmlspecialchars($bebida->productos['id_producto']) ?>">
                                    <input type="hidden" name="bebidas[<?= $index ?>][nombre_corto]" value="<?= htmlspecialchars($bebida->productos['nombre_corto']) ?>">
                                    <input type="hidden" name="bebidas[<?= $index ?>][nombre_categoria]" value="<?= htmlspecialchars($bebida->productos['nombre_categoria']) ?>">
                                    <input type="hidden" name="bebidas[<?= $index ?>][tipo_categoria]" value="<?= htmlspecialchars($bebida->productos['tipo_categoria']) ?>">
                                    <input type="hidden" name="bebidas[<?= $index ?>][modalidad_producto]" value="<?= htmlspecialchars($bebida->productos['modalidad_producto']) ?>">
                                </td>
                                <td>
                                    <input type="number" name="bebidas[<?= $index ?>][uds_stock]" value="" min="0">
                                </td>
                                <td>
                                    <input type="number" name="bebidas[<?= $index ?>][precio_unitario]" value="" min="0" step="0.01">
                                </td>
                                <td>
                                    <?= htmlspecialchars($bebida->productos['tipo_categoria']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table><br>

                <input type="submit" value="Enviar" name="modificarBebida"><br>
            </form>
            <br><a href="/views/admin/admin.php">Volver</a>
        </section>
    </main>
</body>

</html>