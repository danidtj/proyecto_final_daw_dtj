<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {

        header("Location: /proyecto_final_daw_dtj/views/frontend/index.php");
    exit;
}

use ModelsFrontend\Rol;
use ModelsAdmin\Producto;

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
        <section class="container_form">
            <h2 class="titulo_form">STOCK COMIDAS</h2>

            <table class="tabla_stock">
                <tr>
                    <th class="th_stock">Nombre</th>
                    <th class="th_stock">Unidades</th>
                    <th class="th_stock">Precio</th>
                    <th class="th_stock">Código</th>
                    <th class="th_stock">Tipo</th>
                    <th class="th_stock">Modalidad</th>
                    <th class="th_stock">Eliminar</th>
                </tr>

                <?php

                if (isset($_POST['eliminarComida'])) {
                    Producto::eliminarProducto($_POST['codigo_producto']);
                }

                $comidas = Producto::getProductos('comida');

                foreach ($comidas as $comida) {
                    if ($comida->productos['uds_stock'] <= 10) {
                        echo "<tr style='background-color: #f23232ff;'>";
                        echo "<td>" . $comida->productos['nombre_corto'] . "</td>";
                        echo "<td>" . $comida->productos['uds_stock'] . "</td>";
                        echo "<td>" . number_format($comida->productos['precio_unitario'], 2, ',', '.') . " €</td>";
                        echo "<td>" . $comida->productos['id_producto'] . "</td>";
                        echo "<td>" . $comida->productos['tipo_categoria'] . "</td>";
                        echo "<td>" . $comida->productos['modalidad_producto'] . "</td>";
                        echo "<td>";
                        echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="POST">';
                        echo '<input type="hidden" name="codigo_producto" value="' . $comida->productos['id_producto'] . '">';
                        if ($nombre_rol === "Administrador") {
                            echo '<input type="submit" value="X" name="eliminarComida" style="background-color:red; border:none; color:white; cursor:pointer;">';
                        } else {
                            echo '<input type="submit" value="X" name="eliminarComida" style="background-color:red; border:none; color:white; cursor:not-allowed;" disabled>';
                        }
                        echo '</form>';
                        echo "</td>";
                        echo "</tr>";
                    } else {
                        echo "<tr>";
                        echo "<td>" . $comida->productos['nombre_corto'] . "</td>";
                        echo "<td>" . $comida->productos['uds_stock'] . "</td>";
                        echo "<td>" . number_format($comida->productos['precio_unitario'], 2, ',', '.') . " €</td>";
                        echo "<td>" . $comida->productos['id_producto'] . "</td>";
                        echo "<td>" . $comida->productos['tipo_categoria'] . "</td>";
                        echo "<td>" . $comida->productos['modalidad_producto'] . "</td>";
                        echo "<td>";
                        echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="POST">';
                        echo '<input type="hidden" name="codigo_producto" value="' . $comida->productos['id_producto'] . '">';
                        if ($nombre_rol === "Administrador") {
                            echo '<input type="submit" value="X" name="eliminarComida" style="background-color:red; border:none; color:white; cursor:pointer;">';
                        } else {
                            echo '<input type="submit" value="X" name="eliminarComida" style="background-color:red; border:none; color:white; cursor:not-allowed;" disabled>';
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
            <!-- Consultamos de nuevo los productos disponibles en la base de datos y aparecen esos mismos en un formulario para modificar su stock y precio -->
            <section class="container_form">
                <h2 class="titulo_form">MODIFICAR STOCK COMIDA</h2>
                <form action="/proyecto_final_daw_dtj/controllers/admin/ProductoController.php" method="post">

                    <table class="tabla_stock">
                        <thead>
                            <th class="th_stock">Platos</th>
                            <th class="th_stock">Uds</th>
                            <th class="th_stock">Precio</th>
                            <th class="th_stock">Tipo</th>
                            <th class="th_stock">Modalidad</th>
                        </thead>

                        <tbody>

                            <?php foreach ($comidas as $index => $comida): ?>
                                <tr>
                                    <td>
                                        <?= htmlspecialchars($comida->productos['nombre_corto']) ?>
                                        <input type="hidden" name="comidas[<?= $index ?>][id_producto]" value="<?= htmlspecialchars($comida->productos['id_producto']) ?>">
                                        <input type="hidden" name="comidas[<?= $index ?>][nombre_corto]" value="<?= htmlspecialchars($comida->productos['nombre_corto']) ?>">
                                        <input type="hidden" name="comidas[<?= $index ?>][nombre_categoria]" value="<?= htmlspecialchars($comida->productos['nombre_categoria']) ?>">
                                        <input type="hidden" name="comidas[<?= $index ?>][tipo_categoria]" value="<?= htmlspecialchars($comida->productos['tipo_categoria']) ?>">
                                        <input type="hidden" name="comidas[<?= $index ?>][modalidad_producto]" value="<?= htmlspecialchars($comida->productos['modalidad_producto']) ?>">
                                    </td>
                                    <td>
                                        <input type="number" name="comidas[<?= $index ?>][uds_stock]" value="" min="0">
                                    </td>
                                    <td>
                                        <input type="number" name="comidas[<?= $index ?>][precio_unitario]" value="" min="0" step="0.01">
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($comida->productos['tipo_categoria']) ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($comida->productos['modalidad_producto']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                    </table><br>

                    <input type="submit" class="btn_modificarStock" value="Modificar" name="modificarComida"><br>


                </form>
            </section>
        <?php
        }

        echo "</main>";
        include_once __DIR__ . '/../partials/footer.php';
        ?>
</body>

</html>