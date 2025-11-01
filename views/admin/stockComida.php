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
            <h2 class="titulo_form">STOCK COMIDAS</h2>
            <?php
            require_once dirname(__DIR__, 2) . '/models/admin/Comida.php';

            use ModelsAdmin\Producto;

            ?>

            <table border="1">
                <tr>
                    <th>Nombre</th>
                    <th>Unidades</th>
                    <th>Precio</th>
                    <th>Código</th>
                    <th>Tipo</th>
                    <th>Modalidad</th>
                </tr>

                <?php

                if (isset($_POST['eliminarComida'])) {
                    Producto::eliminarProducto($_POST['codigo_producto']);
                }

                $comidas = Producto::getProductos('comida');

                foreach ($comidas as $comida) {
                    echo "<tr>";
                    echo "<td>" . $comida->productos['nombre_producto'] . "</td>";
                    echo "<td>" . $comida->productos['uds_producto'] . "</td>";
                    echo "<td>" . $comida->productos['precio_producto'] . "</td>";
                    echo "<td>" . $comida->productos['codigo_producto'] . "</td>";
                    echo "<td>" . $comida->productos['tipo_producto'] . "</td>";
                    echo "<td>" . $comida->productos['modalidad_producto'] . "</td>";
                    echo "<td>";
                    echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="POST">';
                    echo '<input type="hidden" name="codigo_producto" value="' . $comida->productos['codigo_producto'] . '">';
                    echo '<input type="submit" value="X" name="eliminarComida" style="background-color:red; border:none; color:white; cursor:pointer;">';
                    echo '</form>';
                    echo "</td>";
                    echo "</tr>";
                }

                echo "</table>";



                ?>


                
        </section>

        <section class="container_form">
            <h2 class="titulo_form">MODIFICAR STOCK COMIDA</h2>
            <form action="/controllers/admin/ProductoController.php" method="post">

                <h4>Entrantes</h4><br>

                <table border="1">
                    <thead>
                        <th>Entrante</th>
                        <th>Uds</th>
                        <th>Precio</th>
                    </thead>

                    <tr>
                        <td>
                            Tapa de jamón ibérico:
                            <input type="hidden" name="comidas[0][codigo_producto]" value="0030">
                            <input type="hidden" name="comidas[0][nombre_producto]" value="Tapa de jamón ibérico">
                            <input type="hidden" name="comidas[0][categoria_producto]" value="comida">
                            <input type="hidden" name="comidas[0][tipo_producto]" value="Tapa">
                            <input type="hidden" name="comidas[0][modalidad_producto]" value="Embutido">
                        </td>
                        <td><input type="number" name="comidas[0][uds_producto]" id="jamon"></td>
                        <td><input type="number" name="comidas[0][precio_producto]" id="precio-jamon" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Tabla de quesos:
                            <input type="hidden" name="comidas[1][codigo_producto]" value="0032">
                            <input type="hidden" name="comidas[1][nombre_producto]" value="Tabla de quesos">
                            <input type="hidden" name="comidas[1][categoria_producto]" value="comida">
                            <input type="hidden" name="comidas[1][tipo_producto]" value="Tapa">
                            <input type="hidden" name="comidas[1][modalidad_producto]" value="Embutido">
                        </td>
                        <td><input type="number" name="comidas[1][uds_producto]" id="queso"></td>
                        <td><input type="number" name="comidas[1][precio_producto]" id="precio-queso" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Patatas bravas:
                            <input type="hidden" name="comidas[2][codigo_producto]" value="0033">
                            <input type="hidden" name="comidas[2][nombre_producto]" value="Patatas bravas">
                            <input type="hidden" name="comidas[2][categoria_producto]" value="comida">
                            <input type="hidden" name="comidas[2][tipo_producto]" value="Tapa">
                            <input type="hidden" name="comidas[2][modalidad_producto]" value="Variado">
                        </td>
                        <td><input type="number" name="comidas[2][uds_producto]" id="patatas-bravas"></td>
                        <td><input type="number" name="comidas[2][precio_producto]" id="precio-bravas" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Patatas con alioli:
                            <input type="hidden" name="comidas[3][codigo_producto]" value="0034">
                            <input type="hidden" name="comidas[3][nombre_producto]" value="Patatas con alioli">
                            <input type="hidden" name="comidas[3][categoria_producto]" value="comida">
                            <input type="hidden" name="comidas[3][tipo_producto]" value="Tapa">
                            <input type="hidden" name="comidas[3][modalidad_producto]" value="Variado">
                        </td>
                        <td><input type="number" name="comidas[3][uds_producto]" id="patatas-alioli"></td>
                        <td><input type="number" name="comidas[3][precio_producto]" id="precio-alioli" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Lagrimitas de pollo:
                            <input type="hidden" name="comidas[4][codigo_producto]" value="0035">
                            <input type="hidden" name="comidas[4][nombre_producto]" value="Lagrimitas de pollo">
                            <input type="hidden" name="comidas[4][categoria_producto]" value="comida">
                            <input type="hidden" name="comidas[4][tipo_producto]" value="Tapa">
                            <input type="hidden" name="comidas[4][modalidad_producto]" value="Carne">
                        </td>
                        <td><input type="number" name="comidas[4][uds_producto]" id="lagrimitas"></td>
                        <td><input type="number" name="comidas[4][precio_producto]" id="precio-lagrimitas" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Tapa de torreznos:
                            <input type="hidden" name="comidas[5][codigo_producto]" value="0036">
                            <input type="hidden" name="comidas[5][nombre_producto]" value="Tapa de torreznos">
                            <input type="hidden" name="comidas[5][categoria_producto]" value="comida">
                            <input type="hidden" name="comidas[5][tipo_producto]" value="Tapa">
                            <input type="hidden" name="comidas[5][modalidad_producto]" value="Carne">
                        </td>
                        <td><input type="number" name="comidas[5][uds_producto]" id="tapa-torreznos"></td>
                        <td><input type="number" name="comidas[5][precio_producto]" id="precio-tapa-torreznos" min="0" step="0.01"></td>
                    </tr>


                </table><br>

                <h4>Raciones</h4><br>
                <h5>Variado</h5><br>

                <table border="1">
                    <thead>
                        <th>Ración</th>
                        <th>Uds</th>
                        <th>Precio</th>
                    </thead>
                    <tr>
                        <td>
                            Bacalao dorado:
                            <input type="hidden" name="comidas[6][codigo_producto]" value="0037">
                            <input type="hidden" name="comidas[6][nombre_producto]" value="Bacalao dorado">
                            <input type="hidden" name="comidas[6][categoria_producto]" value="comida">
                            <input type="hidden" name="comidas[6][tipo_producto]" value="Ración">
                            <input type="hidden" name="comidas[6][modalidad_producto]" value="Pescado">
                        </td>
                        <td><input type="number" name="comidas[6][uds_producto]" id="bacalao-dorado"></td>
                        <td><input type="number" name="comidas[6][precio_producto]" id="precio-bacalao" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Surtido de croquetas:
                            <input type="hidden" name="comidas[7][codigo_producto]" value="0038">
                            <input type="hidden" name="comidas[7][nombre_producto]" value="Surtido de croquetas">
                            <input type="hidden" name="comidas[7][categoria_producto]" value="comida">
                            <input type="hidden" name="comidas[7][tipo_producto]" value="Ración">
                            <input type="hidden" name="comidas[7][modalidad_producto]" value="Carne">
                        </td>
                        <td><input type="number" name="comidas[7][uds_producto]" id="surtido-croquetas"></td>
                        <td><input type="number" name="comidas[7][precio_producto]" id="precio-croquetas" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Torreznos:
                            <input type="hidden" name="comidas[8][codigo_producto]" value="0039">
                            <input type="hidden" name="comidas[8][nombre_producto]" value="Torreznos">
                            <input type="hidden" name="comidas[8][categoria_producto]" value="comida">
                            <input type="hidden" name="comidas[8][tipo_producto]" value="Ración">
                            <input type="hidden" name="comidas[8][modalidad_producto]" value="Carne">
                        </td>
                        <td><input type="number" name="comidas[8][uds_producto]" id="racion-torreznos"></td>
                        <td><input type="number" name="comidas[8][precio_producto]" id="precio-racion-torreznos" min="0" step="0.01"></td>
                    </tr>
                </table><br>





                <h5>Carnes</h5><br>

                <table border="1">
                    <thead>
                        <th>Carne</th>
                        <th>Uds</th>
                        <th>Precio</th>
                    </thead>

                    <tr>
                        <td>
                            Solomillo al ajo tostado:
                            <input type="hidden" name="comidas[9][codigo_producto]" value="0040">
                            <input type="hidden" name="comidas[9][nombre_producto]" value="Solomillo al ajo tostado">
                            <input type="hidden" name="comidas[9][categoria_producto]" value="comida">
                            <input type="hidden" name="comidas[9][tipo_producto]" value="Ración">
                            <input type="hidden" name="comidas[9][modalidad_producto]" value="Carne">
                        </td>
                        <td><input type="number" name="comidas[9][uds_producto]" id="solomillo-tostado"></td>
                        <td><input type="number" name="comidas[9][precio_producto]" id="precio-solomillo-tostado" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Solomillo al ajillo:
                            <input type="hidden" name="comidas[10][codigo_producto]" value="0041">
                            <input type="hidden" name="comidas[10][nombre_producto]" value="Solomillo al ajillo">
                            <input type="hidden" name="comidas[10][categoria_producto]" value="comida">
                            <input type="hidden" name="comidas[10][tipo_producto]" value="Ración">
                            <input type="hidden" name="comidas[10][modalidad_producto]" value="Carne">
                        </td>
                        <td><input type="number" name="comidas[10][uds_producto]" id="solomillo-ajillo"></td>
                        <td><input type="number" name="comidas[10][precio_producto]" id="precio-solomillo-ajillo" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Solomillo a la pimienta:
                            <input type="hidden" name="comidas[11][codigo_producto]" value="0042">
                            <input type="hidden" name="comidas[11][nombre_producto]" value="Solomillo a la pimienta">
                            <input type="hidden" name="comidas[11][categoria_producto]" value="comida">
                            <input type="hidden" name="comidas[11][tipo_producto]" value="Ración">
                            <input type="hidden" name="comidas[11][modalidad_producto]" value="Carne">
                        </td>
                        <td><input type="number" name="comidas[11][uds_producto]" id="solomillo-pimienta"></td>
                        <td><input type="number" name="comidas[11][precio_producto]" id="precio-solomillo-pimienta" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Lagarto a la brasa:
                            <input type="hidden" name="comidas[12][codigo_producto]" value="0043">
                            <input type="hidden" name="comidas[12][nombre_producto]" value="Lagarto a la brasa">
                            <input type="hidden" name="comidas[12][categoria_producto]" value="comida">
                            <input type="hidden" name="comidas[12][tipo_producto]" value="Ración">
                            <input type="hidden" name="comidas[12][modalidad_producto]" value="Carne">
                        </td>
                        <td><input type="number" name="comidas[12][uds_producto]" id="lagarto"></td>
                        <td><input type="number" name="comidas[12][precio_producto]" id="precio-lagarto" min="0" step="0.01"></td>
                    </tr>
                </table><br>

                <h5>Pescados</h5><br>

                <table border="1">
                    <thead>
                        <th>Pescado</th>
                        <th>Uds</th>
                        <th>Precio</th>
                    </thead>

                    <tr>
                        <td>
                            Choco:
                            <input type="hidden" name="comidas[13][codigo_producto]" value="0044">
                            <input type="hidden" name="comidas[13][nombre_producto]" value="Choco">
                            <input type="hidden" name="comidas[13][categoria_producto]" value="comida">
                            <input type="hidden" name="comidas[13][tipo_producto]" value="Ración">
                            <input type="hidden" name="comidas[13][modalidad_producto]" value="Pescado">
                        </td>
                        <td><input type="number" name="comidas[13][uds_producto]" id="choco"></td>
                        <td><input type="number" name="comidas[13][precio_producto]" id="precio-choco" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Merluza:
                            <input type="hidden" name="comidas[14][codigo_producto]" value="0045">
                            <input type="hidden" name="comidas[14][nombre_producto]" value="Merluza">
                            <input type="hidden" name="comidas[14][categoria_producto]" value="comida">
                            <input type="hidden" name="comidas[14][tipo_producto]" value="Ración">
                            <input type="hidden" name="comidas[14][modalidad_producto]" value="Pescado">
                        </td>
                        <td><input type="number" name="comidas[14][uds_producto]" id="merluza"></td>
                        <td><input type="number" name="comidas[14][precio_producto]" id="precio-merluza" min="0" step="0.01"></td>
                    </tr>

                    
                    <tr>
                        <td>
                            Lubina:
                            <input type="hidden" name="comidas[15][codigo_producto]" value="0046">
                            <input type="hidden" name="comidas[15][nombre_producto]" value="Lubina">
                            <input type="hidden" name="comidas[15][categoria_producto]" value="comida">
                            <input type="hidden" name="comidas[15][tipo_producto]" value="Ración">
                            <input type="hidden" name="comidas[15][modalidad_producto]" value="Pescado">
                        </td>
                        <td><input type="number" name="comidas[15][uds_producto]" id="lubina"></td>
                        <td><input type="number" name="comidas[15][precio_producto]" id="precio-lubina" min="0" step="0.01"></td>
                    </tr>
                </table><br>


                <!--Producto Nuevo:
                <label for="comidaNueva"><b>Uds:</b></label>
                <input type="number" name="comidas[][uds]" id="comidaNueva">
                <label for="precio-comidaNueva"><b>Precio:</b></label>
                <input type="number" name="comidas[][precio]" id="precio-comidaNueva" min="0" step="0.01">
                <label for="nombre-comidaNueva"><b>Nombre:</b></label>
                <input type="text" name="comidas[][nombre]" id="nombre-comidaNueva">
                <input type="hidden" name="comidas[][codigo]" value="0017">
                <input type="hidden" name="comidas[][tipo]" value="Pescados"><br><br>-->


                <input type="submit" value="Enviar" name="modificarComida"><br>
            </form>
            <br><a href="/views/admin/admin.php">Volver</a>
        </section>
    </main>
</body>

</html>