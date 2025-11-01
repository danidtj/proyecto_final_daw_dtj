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
            <h2 class="titulo_form">MODIFICAR STOCK PRODUCTOS</h2>
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
                            <input type="hidden" name="productos[0017][codigo]" value="0017">
                            <input type="hidden" name="productos[0017][nombre]" value="Coca-Cola">
                            <input type="hidden" name="productos[0017][tipo]" value="Refrescos">
                        </td>
                        <td><input type="number" name="productos[0017][uds]" id="cocacola"></td>
                        <td><input type="number" name="productos[0017][precio]" id="precio-cocacola" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Coca-Cola Zero:
                            <input type="hidden" name="productos[0018][codigo]" value="0018">
                            <input type="hidden" name="productos[0018][nombre]" value="Coca-Cola Zero">
                            <input type="hidden" name="productos[0018][tipo]" value="Refrescos">
                        </td>
                        <td><input type="number" name="productos[0018][uds]" id="cocacola-zero"></td>
                        <td><input type="number" name="productos[0018][precio]" id="precio-cocacola-zero" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Coca-Cola Zero Zero:
                            <input type="hidden" name="productos[0019][codigo]" value="0019">
                            <input type="hidden" name="productos[0019][nombre]" value="Coca-Cola Zero Zero">
                            <input type="hidden" name="productos[0019][tipo]" value="Refrescos">
                        </td>
                        <td><input type="number" name="productos[0019][uds]" id="cocacola-zerozero"></td>
                        <td><input type="number" name="productos[0019][precio]" id="precio-cocacola-zero" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Nestea:
                            <input type="hidden" name="productos[0020][codigo]" value="0020">
                            <input type="hidden" name="productos[0020][nombre]" value="Nestea">
                            <input type="hidden" name="productos[0020][tipo]" value="Refrescos">
                        </td>
                        <td><input type="number" name="productos[0020][uds]" id="nestea"></td>
                        <td><input type="number" name="productos[0020][precio]" id="precio-nestea" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Fanta de naranja:
                            <input type="hidden" name="productos[0021][codigo]" value="0021">
                            <input type="hidden" name="productos[0021][nombre]" value="Fanta de naranja">
                            <input type="hidden" name="productos[0021][tipo]" value="Refrescos">
                        </td>
                        <td><input type="number" name="productos[0021][uds]" id="fanta-naranja"></td>
                        <td><input type="number" name="productos[0021][precio]" id="precio-fanta-naranja" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Fanta de limón:
                            <input type="hidden" name="productos[0022][codigo]" value="0022">
                            <input type="hidden" name="productos[0022][nombre]" value="Fanta de limón">
                            <input type="hidden" name="productos[0022][tipo]" value="Refrescos">
                        </td>
                        <td><input type="number" name="productos[0022][uds]" id="fanta-limon"></td>
                        <td><input type="number" name="productos[0022][precio]" id="precio-fanta-limon" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Tónica:
                            <input type="hidden" name="productos[0023][codigo]" value="0023">
                            <input type="hidden" name="productos[0023][nombre]" value="Tónica">
                            <input type="hidden" name="productos[0023][tipo]" value="Refrescos">
                        </td>
                        <td><input type="number" name="productos[0023][uds]" id="tonica"></td>
                        <td><input type="number" name="productos[0023][precio]" id="precio-tonica" min="0" step="0.01"></td>
                    </tr>
                </table><br>



                <!-- productos ALCOHÓLICAS -->
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
                            <input type="hidden" name="productos[0024][codigo]" value="0024">
                            <input type="hidden" name="productos[0024][nombre]" value="Cerveza de barril">
                            <input type="hidden" name="productos[0024][tipo]" value="Alcohol">
                        </td>
                        <td><input type="number" name="productos[0024][uds]" id="cerveza-barril"></td>
                        <td><input type="number" name="productos[0024][precio]" id="precio-cerveza-barril" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Cerveza tostada:
                            <input type="hidden" name="productos[0025][codigo]" value="0025">
                            <input type="hidden" name="productos[0025][nombre]" value="Cerveza tostada">
                            <input type="hidden" name="productos[0025][tipo]" value="Alcohol">
                        </td>
                        <td><input type="number" name="productos[0025][uds]" id="cerveza-tostada"></td>
                        <td><input type="number" name="productos[0025][precio]" id="precio-cerveza-tostada" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Cerveza Cruzcampo:
                            <input type="hidden" name="productos[0026][codigo]" value="0026">
                            <input type="hidden" name="productos[0026][nombre]" value="Cerveza Cruzcampo">
                            <input type="hidden" name="productos[0026][tipo]" value="Alcohol">
                        </td>
                        <td><input type="number" name="productos[0026][uds]" id="cerveza-cruzcampo"></td>
                        <td><input type="number" name="productos[0026][precio]" id="precio-cerveza-cruzcampo" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Vino tinto:
                            <input type="hidden" name="productos[0027][codigo]" value="0027">
                            <input type="hidden" name="productos[0027][nombre]" value="Vino tinto">
                            <input type="hidden" name="productos[0027][tipo]" value="Alcohol">
                        </td>
                        <td><input type="number" name="productos[0027][uds]" id="vino-tinto"></td>
                        <td><input type="number" name="productos[0027][precio]" id="precio-vino-tinto" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Vino blanco:
                            <input type="hidden" name="productos[0028][codigo]" value="0028">
                            <input type="hidden" name="productos[0028][nombre]" value="Vino blanco">
                            <input type="hidden" name="productos[0028][tipo]" value="Alcohol">
                        </td>
                        <td><input type="number" name="productos[0028][uds]" id="vino-blanco"></td>
                        <td><input type="number" name="productos[0028][precio]" id="precio-vino-blanco" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Vino dulce:
                            <input type="hidden" name="productos[0029][codigo]" value="0029">
                            <input type="hidden" name="productos[0029][nombre]" value="Vino dulce">
                            <input type="hidden" name="productos[0029][tipo]" value="Alcohol">
                        </td>
                        <td><input type="number" name="productos[0029][uds]" id="vino-dulce"></td>
                        <td><input type="number" name="productos[0029][precio]" id="precio-vino-dulce" min="0" step="0.01"></td>
                    </tr>
                </table><br>

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
                            <input type="hidden" name="productos[0030][codigo]" value="0030">
                            <input type="hidden" name="productos[0030][nombre]" value="Tapa de jamón ibérico">
                            <input type="hidden" name="productos[0030][tipo]" value="Entrantes">
                        </td>
                        <td><input type="number" name="productos[0030][uds]" id="jamon"></td>
                        <td><input type="number" name="productos[0030][precio]" id="precio-jamon" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Tabla de quesos:
                            <input type="hidden" name="productos[0032][codigo]" value="0032">
                            <input type="hidden" name="productos[0032][nombre]" value="Tabla de quesos">
                            <input type="hidden" name="productos[0032][tipo]" value="Entrantes">
                        </td>
                        <td><input type="number" name="productos[0032][uds]" id="queso"></td>
                        <td><input type="number" name="productos[0032][precio]" id="precio-queso" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Patatas bravas:
                            <input type="hidden" name="productos[0033][codigo]" value="0033">
                            <input type="hidden" name="productos[0033][nombre]" value="Patatas bravas">
                            <input type="hidden" name="productos[0033][tipo]" value="Entrantes">
                        </td>
                        <td><input type="number" name="productos[0033][uds]" id="patatas-bravas"></td>
                        <td><input type="number" name="productos[0033][precio]" id="precio-bravas" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Patatas con alioli:
                            <input type="hidden" name="productos[0034][codigo]" value="0034">
                            <input type="hidden" name="productos[0034][nombre]" value="Patatas con alioli">
                            <input type="hidden" name="productos[0034][tipo]" value="Entrantes">
                        </td>
                        <td><input type="number" name="productos[0034][uds]" id="patatas-alioli"></td>
                        <td><input type="number" name="productos[0034][precio]" id="precio-alioli" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Lagrimitas de pollo:
                            <input type="hidden" name="productos[0035][codigo]" value="0035">
                            <input type="hidden" name="productos[0035][nombre]" value="Lagrimitas de pollo">
                            <input type="hidden" name="productos[0035][tipo]" value="Entrantes">
                        </td>
                        <td><input type="number" name="productos[0035][uds]" id="lagrimitas"></td>
                        <td><input type="number" name="productos[0035][precio]" id="precio-lagrimitas" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Tapa de torreznos:
                            <input type="hidden" name="productos[0036][codigo]" value="0036">
                            <input type="hidden" name="productos[0036][nombre]" value="Tapa de torreznos">
                            <input type="hidden" name="productos[0036][tipo]" value="Entrantes">
                        </td>
                        <td><input type="number" name="productos[0036][uds]" id="tapa-torreznos"></td>
                        <td><input type="number" name="productos[0036][precio]" id="precio-tapa-torreznos" min="0" step="0.01"></td>
                    </tr>


                </table><br>

                <h4>Raciones</h4><br>
                <h5>Raciones</h5><br>

                <table border="1">
                    <thead>
                        <th>Ración</th>
                        <th>Uds</th>
                        <th>Precio</th>
                    </thead>
                    <tr>
                        <td>
                            Bacalao dorado:
                            <input type="hidden" name="productos[0037][codigo]" value="0037">
                            <input type="hidden" name="productos[0037][nombre]" value="Bacalao dorado">
                            <input type="hidden" name="productos[0037][tipo]" value="Raciones">
                        </td>
                        <td><input type="number" name="productos[0037][uds]" id="bacalao-dorado"></td>
                        <td><input type="number" name="productos[0037][precio]" id="precio-bacalao" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Surtido de croquetas:
                            <input type="hidden" name="productos[0038][codigo]" value="0038">
                            <input type="hidden" name="productos[0038][nombre]" value="Surtido de croquetas">
                            <input type="hidden" name="productos[0038][tipo]" value="Raciones">
                        </td>
                        <td><input type="number" name="productos[0038][uds]" id="surtido-croquetas"></td>
                        <td><input type="number" name="productos[0038][precio]" id="precio-croquetas" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Torreznos:
                            <input type="hidden" name="productos[0039][codigo]" value="0039">
                            <input type="hidden" name="productos[0039][nombre]" value="Torreznos">
                            <input type="hidden" name="productos[0039][tipo]" value="Raciones">
                        </td>
                        <td><input type="number" name="productos[0039][uds]" id="racion-torreznos"></td>
                        <td><input type="number" name="productos[0039][precio]" id="precio-racion-torreznos" min="0" step="0.01"></td>
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
                            <input type="hidden" name="productos[0040][codigo]" value="0040">
                            <input type="hidden" name="productos[0040][nombre]" value="Solomillo al ajo tostado">
                            <input type="hidden" name="productos[0040][tipo]" value="Carnes">
                        </td>
                        <td><input type="number" name="productos[0040][uds]" id="solomillo-tostado"></td>
                        <td><input type="number" name="productos[0040][precio]" id="precio-solomillo-tostado" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Solomillo al ajillo:
                            <input type="hidden" name="productos[0041][codigo]" value="0041">
                            <input type="hidden" name="productos[0041][nombre]" value="Solomillo al ajillo">
                            <input type="hidden" name="productos[0041][tipo]" value="Carnes">
                        </td>
                        <td><input type="number" name="productos[0041][uds]" id="solomillo-ajillo"></td>
                        <td><input type="number" name="productos[0041][precio]" id="precio-solomillo-ajillo" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Solomillo a la pimienta:
                            <input type="hidden" name="productos[0042][codigo]" value="0042">
                            <input type="hidden" name="productos[0042][nombre]" value="Solomillo a la pimienta">
                            <input type="hidden" name="productos[0042][tipo]" value="Carnes">
                        </td>
                        <td><input type="number" name="productos[0042][uds]" id="solomillo-pimienta"></td>
                        <td><input type="number" name="productos[0042][precio]" id="precio-solomillo-pimienta" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Lagarto a la brasa:
                            <input type="hidden" name="productos[0043][codigo]" value="0043">
                            <input type="hidden" name="productos[0043][nombre]" value="Lagarto a la brasa">
                            <input type="hidden" name="productos[0043][tipo]" value="Carnes">
                        </td>
                        <td><input type="number" name="productos[0043][uds]" id="lagarto"></td>
                        <td><input type="number" name="productos[0043][precio]" id="precio-lagarto" min="0" step="0.01"></td>
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
                            <input type="hidden" name="productos[0044][codigo]" value="0044">
                            <input type="hidden" name="productos[0044][nombre]" value="Choco">
                            <input type="hidden" name="productos[0044][tipo]" value="Pescados">
                        </td>
                        <td><input type="number" name="productos[0044][uds]" id="choco"></td>
                        <td><input type="number" name="productos[0044][precio]" id="precio-choco" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Merluza:
                            <input type="hidden" name="productos[0045][codigo]" value="0045">
                            <input type="hidden" name="productos[0045][nombre]" value="Merluza">
                            <input type="hidden" name="productos[0045][tipo]" value="Pescados">
                        </td>
                        <td><input type="number" name="productos[0045][uds]" id="merluza"></td>
                        <td><input type="number" name="productos[0045][precio]" id="precio-merluza" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Lubina:
                            <input type="hidden" name="productos[0046][codigo]" value="0046">
                            <input type="hidden" name="productos[0046][nombre]" value="Lubina">
                            <input type="hidden" name="productos[0046][tipo]" value="Pescados">
                        </td>
                        <td><input type="number" name="productos[0046][uds]" id="lubina"></td>
                        <td><input type="number" name="productos[0046][precio]" id="precio-lubina" min="0" step="0.01"></td>
                    </tr>
                </table><br>

                <h4>productos</h4><br>
                <table border="1">
                    <tr>
                        <th>Postre</th>
                        <th>Uds</th>
                        <th>Precio</th>
                    </tr>

                    <tr>
                        <td>
                            Tarta de chocolate
                            <input type="hidden" name="productos[0047][codigo]" value="0047">
                            <input type="hidden" name="productos[0047][nombre]" value="Tarta de chocolate">
                            <input type="hidden" name="productos[0047][tipo]" value="Tartas">
                        </td>
                        <td><input type="number" name="productos[0047][uds]" id="tarta-chocolate"></td>
                        <td><input type="number" name="productos[0047][precio]" id="precio-tarta-chocolate" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Tarta de queso:
                            <input type="hidden" name="productos[0048][codigo]" value="0048">
                            <input type="hidden" name="productos[0048][nombre]" value="Tarta de queso">
                            <input type="hidden" name="productos[0048][tipo]" value="Tartas">
                        </td>
                        <td><input type="number" name="productos[0048][uds]" id="tarta-queso"></td>
                        <td><input type="number" name="productos[0048][precio]" id="precio-tarta-queso" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Tarta red velvet:
                            <input type="hidden" name="productos[0049][codigo]" value="0049">
                            <input type="hidden" name="productos[0049][nombre]" value="Tarta red velvet">
                            <input type="hidden" name="productos[0049][tipo]" value="Tartas">
                        </td>
                        <td><input type="number" name="productos[0049][uds]" id="tarta-velvet"></td>
                        <td><input type="number" name="productos[0049][precio]" id="precio-tarta-velvet" min="0" step="0.01"></td>
                    </tr>

                    
                    <tr>
                        <td>
                            Variado de frutas:
                            <input type="hidden" name="productos[0050][codigo]" value="0050">
                            <input type="hidden" name="productos[0050][nombre]" value="Variado de frutas">
                            <input type="hidden" name="productos[0050][tipo]" value="Fruta">
                        </td>
                        <td><input type="number" name="productos[0050][uds]" id="fruta"></td>
                        <td><input type="number" name="productos[0050][precio]" id="precio-fruta" min="0" step="0.01"></td>
                    </tr>
                </table><br>


                <!--Producto Nuevo:
                <label for="comidaNueva"><b>Uds:</b></label>
                <input type="number" name="productos[][uds]" id="comidaNueva">
                <label for="precio-comidaNueva"><b>Precio:</b></label>
                <input type="number" name="productos[][precio]" id="precio-comidaNueva" min="0" step="0.01">
                <label for="nombre-comidaNueva"><b>Nombre:</b></label>
                <input type="text" name="productos[][nombre]" id="nombre-comidaNueva">
                <input type="hidden" name="productos[][codigo]" value="0017">
                <input type="hidden" name="productos[][tipo]" value="Pescados"><br><br>-->


                <input type="submit" value="Enviar" name="modificarProducto"><br>
            </form>
            <br><a href="/views/admin/admin.php">Volver</a>
        </section>
    </main>
</body>

</html>