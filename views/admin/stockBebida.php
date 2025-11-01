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

            use ModelsAdmin\Bebida;
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

                if (isset($_POST['eliminarBebida'])) {
                    Producto::eliminarProducto($_POST['codigo_producto']);
                }

                $bebidas = Producto::getProductos('bebida');

                foreach ($bebidas as $bebida) {
                    echo "<tr>";
                    echo "<td>" . $bebida->productos['nombre_producto'] . "</td>";
                    echo "<td>" . $bebida->productos['uds_producto'] . "</td>";
                    echo "<td>" . $bebida->productos['precio_producto'] . "</td>";
                    echo "<td>" . $bebida->productos['codigo_producto'] . "</td>";
                    echo "<td>" . $bebida->productos['tipo_producto'] . "</td>";
                    echo "<td>";
                    echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="POST">';
                    echo '<input type="hidden" name="codigo_producto" value="' . $bebida->productos['codigo_producto'] . '">';
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

                <h4>Refrescos</h4><br>

                <table border="1">
                    <thead>
                        <th>Refresco</th>
                        <th>Uds</th>
                        <th>Precio</th>
                    </thead>

                    <tr>
                        <td>
                            Coca-Cola:
                            <input type="hidden" name="bebidas[0][codigo_producto]" value="0017">
                            <input type="hidden" name="bebidas[0][nombre_producto]" value="Coca-Cola">
                            <input type="hidden" name="bebidas[0][categoria_producto]" value="bebida">
                            <input type="hidden" name="bebidas[0][tipo_producto]" value="Refresco">
                            <input type="hidden" name="bebidas[0][modalidad_producto]" value="">
                        </td>
                        <td><input type="number" name="bebidas[0][uds_producto]" id="cocacola"></td>
                        <td><input type="number" name="bebidas[0][precio_producto]" id="precio-cocacola" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Coca-Cola Zero:
                            <input type="hidden" name="bebidas[1][codigo_producto]" value="0018">
                            <input type="hidden" name="bebidas[1][nombre_producto]" value="Coca-Cola Zero">
                            <input type="hidden" name="bebidas[1][categoria_producto]" value="bebida">
                            <input type="hidden" name="bebidas[1][tipo_producto]" value="Refresco">
                            <input type="hidden" name="bebidas[1][modalidad_producto]" value="">
                        </td>
                        <td><input type="number" name="bebidas[1][uds_producto]" id="cocacola-zero"></td>
                        <td><input type="number" name="bebidas[1][precio_producto]" id="precio-cocacola-zero" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Coca-Cola Zero Zero:
                            <input type="hidden" name="bebidas[2][codigo_producto]" value="0019">
                            <input type="hidden" name="bebidas[2][nombre_producto]" value="Coca-Cola Zero Zero">
                            <input type="hidden" name="bebidas[2][categoria_producto]" value="bebida">
                            <input type="hidden" name="bebidas[2][tipo_producto]" value="Refresco">
                            <input type="hidden" name="bebidas[2][modalidad_producto]" value="">
                        </td>
                        <td><input type="number" name="bebidas[2][uds_producto]" id="cocacola-zerozero"></td>
                        <td><input type="number" name="bebidas[2][precio_producto]" id="precio-cocacola-zero" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Nestea:
                            <input type="hidden" name="bebidas[3][codigo_producto]" value="0020">
                            <input type="hidden" name="bebidas[3][nombre_producto]" value="Nestea">
                            <input type="hidden" name="bebidas[3][categoria_producto]" value="bebida">
                            <input type="hidden" name="bebidas[3][tipo_producto]" value="Refresco">
                            <input type="hidden" name="bebidas[3][modalidad_producto]" value="">
                        </td>
                        <td><input type="number" name="bebidas[3][uds_producto]" id="nestea"></td>
                        <td><input type="number" name="bebidas[3][precio_producto]" id="precio-nestea" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Fanta de naranja:
                            <input type="hidden" name="bebidas[4][codigo_producto]" value="0021">
                            <input type="hidden" name="bebidas[4][nombre_producto]" value="Fanta de naranja">
                            <input type="hidden" name="bebidas[4][categoria_producto]" value="bebida">
                            <input type="hidden" name="bebidas[4][tipo_producto]" value="Refresco">
                            <input type="hidden" name="bebidas[4][modalidad_producto]" value="">
                        </td>
                        <td><input type="number" name="bebidas[4][uds_producto]" id="fanta-naranja"></td>
                        <td><input type="number" name="bebidas[4][precio_producto]" id="precio-fanta-naranja" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Fanta de limón:
                            <input type="hidden" name="bebidas[5][codigo_producto]" value="0022">
                            <input type="hidden" name="bebidas[5][nombre_producto]" value="Fanta de limón">
                            <input type="hidden" name="bebidas[5][categoria_producto]" value="bebida">
                            <input type="hidden" name="bebidas[5][tipo_producto]" value="Refresco">
                            <input type="hidden" name="bebidas[5][modalidad_producto]" value="">
                        </td>
                        <td><input type="number" name="bebidas[5][uds_producto]" id="fanta-limon"></td>
                        <td><input type="number" name="bebidas[5][precio_producto]" id="precio-fanta-limon" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Tónica:
                            <input type="hidden" name="bebidas[6][codigo_producto]" value="0023">
                            <input type="hidden" name="bebidas[6][nombre_producto]" value="Tónica">
                            <input type="hidden" name="bebidas[6][categoria_producto]" value="bebida">
                            <input type="hidden" name="bebidas[6][tipo_producto]" value="Refresco">
                            <input type="hidden" name="bebidas[6][modalidad_producto]" value="">
                        </td>
                        <td><input type="number" name="bebidas[6][uds_producto]" id="tonica"></td>
                        <td><input type="number" name="bebidas[6][precio_producto]" id="precio-tonica" min="0" step="0.01"></td>
                    </tr>
                </table><br>



                <!-- BEBIDAS ALCOHÓLICAS -->
                <h4>Alcohol</h4><br>

                <table border="1">
                    <thead>
                        <th>Bebida</th>
                        <th>Uds</th>
                        <th>Precio</th>
                    </thead>

                    <tr>
                        <td>
                            Cerveza de barril:
                            <input type="hidden" name="bebidas[7][codigo_producto]" value="0024">
                            <input type="hidden" name="bebidas[7][nombre_producto]" value="Cerveza de barril">
                            <input type="hidden" name="bebidas[7][categoria_producto]" value="bebida">
                            <input type="hidden" name="bebidas[7][tipo_producto]" value="Con alcohol">
                            <input type="hidden" name="bebidas[7][modalidad_producto]" value="">
                        </td>
                        <td><input type="number" name="bebidas[7][uds_producto]" id="cerveza-barril"></td>
                        <td><input type="number" name="bebidas[7][precio_producto]" id="precio-cerveza-barril" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Cerveza tostada:
                            <input type="hidden" name="bebidas[8][codigo_producto]" value="0025">
                            <input type="hidden" name="bebidas[8][nombre_producto]" value="Cerveza tostada">
                            <input type="hidden" name="bebidas[8][categoria_producto]" value="bebida">
                            <input type="hidden" name="bebidas[8][tipo_producto]" value="Con alcohol">
                            <input type="hidden" name="bebidas[8][modalidad_producto]" value="">
                        </td>
                        <td><input type="number" name="bebidas[8][uds_producto]" id="cerveza-tostada"></td>
                        <td><input type="number" name="bebidas[8][precio_producto]" id="precio-cerveza-tostada" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Cerveza Cruzcampo:
                            <input type="hidden" name="bebidas[9][codigo_producto]" value="0026">
                            <input type="hidden" name="bebidas[9][nombre_producto]" value="Cerveza Cruzcampo">
                            <input type="hidden" name="bebidas[9][categoria_producto]" value="bebida">
                            <input type="hidden" name="bebidas[9][tipo_producto]" value="Con alcohol">
                            <input type="hidden" name="bebidas[9][modalidad_producto]" value="">
                        </td>
                        <td><input type="number" name="bebidas[9][uds_producto]" id="cerveza-cruzcampo"></td>
                        <td><input type="number" name="bebidas[9][precio_producto]" id="precio-cerveza-cruzcampo" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Vino tinto:
                            <input type="hidden" name="bebidas[10][codigo_producto]" value="0027">
                            <input type="hidden" name="bebidas[10][nombre_producto]" value="Vino tinto">
                            <input type="hidden" name="bebidas[10][categoria_producto]" value="bebida">
                            <input type="hidden" name="bebidas[10][tipo_producto]" value="Con alcohol">
                            <input type="hidden" name="bebidas[10][modalidad_producto]" value="">
                        </td>
                        <td><input type="number" name="bebidas[10][uds_producto]" id="vino-tinto"></td>
                        <td><input type="number" name="bebidas[10][precio_producto]" id="precio-vino-tinto" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Vino blanco:
                            <input type="hidden" name="bebidas[11][codigo_producto]" value="0028">
                            <input type="hidden" name="bebidas[11][nombre_producto]" value="Vino blanco">
                            <input type="hidden" name="bebidas[11][categoria_producto]" value="bebida">
                            <input type="hidden" name="bebidas[11][tipo_producto]" value="Con alcohol">
                            <input type="hidden" name="bebidas[11][modalidad_producto]" value="">
                        </td>
                        <td><input type="number" name="bebidas[11][uds_producto]" id="vino-blanco"></td>
                        <td><input type="number" name="bebidas[11][precio_producto]" id="precio-vino-blanco" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Vino dulce:
                            <input type="hidden" name="bebidas[12][codigo_producto]" value="0029">
                            <input type="hidden" name="bebidas[12][nombre_producto]" value="Vino dulce">
                            <input type="hidden" name="bebidas[12][categoria_producto]" value="bebida">
                            <input type="hidden" name="bebidas[12][tipo_producto]" value="Con alcohol">
                            <input type="hidden" name="bebidas[12][modalidad_producto]" value="">
                        </td>
                        <td><input type="number" name="bebidas[12][uds_producto]" id="vino-dulce"></td>
                        <td><input type="number" name="bebidas[12][precio_producto]" id="precio-vino-dulce" min="0" step="0.01"></td>
                    </tr>
                </table><br>

                <input type="submit" value="Enviar" name="modificarBebida"><br>
            </form>
            <br><a href="/views/admin/admin.php">Volver</a>
        </section>


    </main>
</body>

</html>