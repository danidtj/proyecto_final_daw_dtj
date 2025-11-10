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
            <h2 class="titulo_form">STOCK POSTRES</h2>
            <?php
            require_once dirname(__DIR__, 2) . '/models/admin/Postre.php';

            use ModelsAdmin\Producto;

            ?>

            <table border="1" cellpadding="5" cellspacing="0">
                <tr>
                    <th>Nombre</th>
                    <th>Unidades</th>
                    <th>Precio</th>
                    <th>Código</th>
                    <th>Tipo</th>
                    <th>Modalidad</th>
                </tr>

                <?php

                if (isset($_POST['eliminarPostre'])) {
                    Producto::eliminarProducto($_POST['codigo_producto']);
                }

                $postres = Producto::getProductos('Postre');

                foreach ($postres as $postre) {
                    if ($postre->productos['uds_stock'] <= 5) {
                        echo "<tr style='background-color: #f23232ff;'>";
                        echo "<td>" . $postre->productos['nombre_corto'] . "</td>";
                        echo "<td>" . $postre->productos['uds_stock'] . "</td>";
                        echo "<td>" . number_format($postre->productos['precio_unitario'], 2, ',', '.') . " €</td>";
                        echo "<td>" . $postre->productos['id_producto'] . "</td>";
                        echo "<td>" . $postre->productos['tipo_categoria'] . "</td>";
                        echo "<td>" . $postre->productos['modalidad_producto'] . "</td>";
                        echo "<td>";
                        echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="POST">';
                        echo '<input type="hidden" name="codigo_producto" value="' . $postre->productos['id_producto'] . '">';
                        echo '<input type="submit" value="X" name="eliminarPostre" style="background-color:red; border:none; color:white; cursor:pointer;">';
                        echo '</form>';
                        echo "</td>";
                        echo "</tr>";
                    } else {
                        echo "<tr>";
                        echo "<td>" . $postre->productos['nombre_corto'] . "</td>";
                        echo "<td>" . $postre->productos['uds_stock'] . "</td>";
                        echo "<td>" . number_format($postre->productos['precio_unitario'], 2, ',', '.') . " €</td>";
                        echo "<td>" . $postre->productos['id_producto'] . "</td>";
                        echo "<td>" . $postre->productos['tipo_categoria'] . "</td>";
                        echo "<td>" . $postre->productos['modalidad_producto'] . "</td>";
                        echo "<td>";
                        echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="POST">';
                        echo '<input type="hidden" name="codigo_producto" value="' . $postre->productos['id_producto'] . '">';
                        echo '<input type="submit" value="X" name="eliminarPostre" style="background-color:red; border:none; color:white; cursor:pointer;">';
                        echo '</form>';
                        echo "</td>";
                        echo "</tr>";
                    }
                }


                echo "</table>";




                ?>
        </section>

        <section class="container_form">
            <h2 class="titulo_form">MODIFICAR STOCK POSTRES</h2>
            <form action="/controllers/admin/ProductoController.php" method="post">

                <table border="1" cellpadding="5" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Postre</th>
                            <th>Uds</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($postres as $index => $postre): ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars($postre->productos['nombre_corto']) ?>
                                    <!-- Inputs ocultos -->
                                    <input type="hidden" name="postres[<?= $index ?>][id_producto]" value="<?= htmlspecialchars($postre->productos['id_producto']) ?>">
                                    <input type="hidden" name="postres[<?= $index ?>][nombre_corto]" value="<?= htmlspecialchars($postre->productos['nombre_corto']) ?>">
                                    <input type="hidden" name="postres[<?= $index ?>][nombre_categoria]" value="<?= htmlspecialchars($postre->productos['nombre_categoria']) ?>">
                                    <input type="hidden" name="postres[<?= $index ?>][tipo_categoria]" value="<?= htmlspecialchars($postre->productos['tipo_categoria']) ?>">
                                    <input type="hidden" name="postres[<?= $index ?>][modalidad_producto]" value="<?= htmlspecialchars($postre->productos['modalidad_producto']) ?>">
                                </td>
                                <td>
                                    <input type="number" name="postres[<?= $index ?>][uds_stock]"
                                        value="" min="0">
                                </td>
                                <td>
                                    <input type="number" name="postres[<?= $index ?>][precio_unitario]"
                                        value="" min="0" step="0.01">
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table><br>

                <input type="submit" class="btn_modificarStock" value="Modificar" name="modificarPostre"><br>
            </form>
        </section>
    </main>
</body>

</html>