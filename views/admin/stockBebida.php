<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {

        header("Location: /proyecto_final_daw_dtj/views/frontend/index.php");
    exit;
}

use ModelsFrontend\Rol;
use ModelsAdmin\Producto;

require_once dirname(__DIR__, 2) . '/models/admin/Producto.php';
require_once dirname(__DIR__, 2) . '/models/frontend/Rol.php';
$rol = new Rol();
$nombre_rol = $rol->obtenerNombreRolPorIdUsuario($_SESSION['id_usuario']);
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
    <?php include_once __DIR__ . '/../partials/headerAdmin.php'; ?>
    <main>
        <section class="container_form productos_stock_container">
            <h2 class="titulo_form productos_stock_titulo">STOCK BEBIDAS</h2>

            <table class="tabla_stock">
                <tr>
                    <th class="th_stock">Nombre</th>
                    <th class="th_stock">Unidades</th>
                    <th class="th_stock">Precio</th>
                    <th class="th_stock">Código</th>
                    <th class="th_stock">Tipo</th>
                    <th class="th_stock">Eliminar</th>
                </tr>

                <?php

                if (isset($_POST['eliminarBebida'])) {
                    Producto::eliminarProducto($_POST['codigo_producto']);
                }

                $bebidas = Producto::getProductos('bebida');

                foreach ($bebidas as $bebida) {
                    if ($bebida->productos['uds_stock'] <= 10) {
                        echo "<tr style='background-color: #f23232ff;'>";
                        echo "<td>" . $bebida->productos['nombre_corto'] . "</td>";
                        echo "<td>" . $bebida->productos['uds_stock'] . "</td>";
                        echo "<td>" . number_format($bebida->productos['precio_unitario'], 2, ',', '.') . " €</td>";
                        echo "<td>" . $bebida->productos['id_producto'] . "</td>";
                        echo "<td>" . $bebida->productos['tipo_categoria'] . "</td>";
                        echo "<td>";
                        echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="POST">';
                        echo '<input type="hidden" name="codigo_producto" value="' . $bebida->productos['id_producto'] . '">';
                        if ($nombre_rol === "Administrador") {
                            echo '<input type="submit" value="X" name="eliminarBebida" style="background-color:red; border:none; color:white; cursor:pointer;">';
                        } else {
                            echo '<input type="submit" value="X" name="eliminarBebida" style="background-color:red; border:none; color:white; cursor:not-allowed;" disabled>';
                        }
                        echo '</form>';
                        echo "</td>";
                        echo "</tr>";
                    } else {
                        echo "<tr>";
                        echo "<td>" . $bebida->productos['nombre_corto'] . "</td>";
                        echo "<td>" . $bebida->productos['uds_stock'] . "</td>";
                        echo "<td>" . number_format($bebida->productos['precio_unitario'], 2, ',', '.') . " €</td>";
                        echo "<td>" . $bebida->productos['id_producto'] . "</td>";
                        echo "<td>" . $bebida->productos['tipo_categoria'] . "</td>";
                        echo "<td>";
                        echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="POST">';
                        echo '<input type="hidden" name="codigo_producto" value="' . $bebida->productos['id_producto'] . '">';
                        if ($nombre_rol === "Administrador") {
                            echo '<input type="submit" value="X" name="eliminarBebida" style="background-color:red; border:none; color:white; cursor:pointer;">';
                        } else {
                            echo '<input type="submit" value="X" name="eliminarBebida" style="background-color:red; border:none; color:white; cursor:not-allowed;" disabled>';
                        }
                        echo '</form>';
                        echo "</td>";
                        echo "</tr>";
                    }
                }


                echo "</table>";


                ?>
        </section>

        <?php
        if ($nombre_rol === "Administrador") {

        ?>

            <section class="container_form productos_modificar_container">
                <h2 class="titulo_form productos_modificar_titulo">MODIFICAR STOCK BEBIDAS</h2>
                <form action="/proyecto_final_daw_dtj/controllers/admin/ProductoController.php" method="post">

                    <table class="tabla_stock">
                        <thead>
                            <th class="th_stock">Refresco</th>
                            <th class="th_stock">Uds</th>
                            <th class="th_stock">Precio</th>
                            <th class="th_stock">Tipo</th>
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
                                        <input class="datos_productos" type="number" name="bebidas[<?= $index ?>][uds_stock]" value="" min="0">
                                    </td>
                                    <td>
                                        <input class="datos_productos" type="number" name="bebidas[<?= $index ?>][precio_unitario]" value="" min="0" step="0.01">
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($bebida->productos['tipo_categoria']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table><br>

                    <input type="submit" class="btn_modificarStock boton_modificarAdmin" value="Modificar" name="modificarBebida"><br>
                </form>
            </section>
        <?php
        }

        echo "</main>";
        include_once __DIR__ . '/../partials/footer.php';
        ?>
</body>

</html>