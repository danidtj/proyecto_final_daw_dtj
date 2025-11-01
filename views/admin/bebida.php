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
            <h2 class="titulo_form">MODIFICAR STOCK BEBIDAS</h2>
            <form action="/controllers/admin/BebidaController.php" method="post">

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
                            <input type="hidden" name="bebidas[0][codigo]" value="0017">
                            <input type="hidden" name="bebidas[0][nombre]" value="Coca-Cola">
                            <input type="hidden" name="bebidas[0][tipo]" value="Refrescos">
                        </td>
                        <td><input type="number" name="bebidas[0][uds]" id="cocacola"></td>
                        <td><input type="number" name="bebidas[0][precio]" id="precio-cocacola" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Coca-Cola Zero:
                            <input type="hidden" name="bebidas[1][codigo]" value="0018">
                            <input type="hidden" name="bebidas[1][nombre]" value="Coca-Cola Zero">
                            <input type="hidden" name="bebidas[1][tipo]" value="Refrescos">
                        </td>
                        <td><input type="number" name="bebidas[1][uds]" id="cocacola-zero"></td>
                        <td><input type="number" name="bebidas[1][precio]" id="precio-cocacola-zero" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Coca-Cola Zero Zero:
                            <input type="hidden" name="bebidas[2][codigo]" value="0019">
                            <input type="hidden" name="bebidas[2][nombre]" value="Coca-Cola Zero Zero">
                            <input type="hidden" name="bebidas[2][tipo]" value="Refrescos">
                        </td>
                        <td><input type="number" name="bebidas[2][uds]" id="cocacola-zerozero"></td>
                        <td><input type="number" name="bebidas[2][precio]" id="precio-cocacola-zero" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Nestea:
                            <input type="hidden" name="bebidas[3][codigo]" value="0020">
                            <input type="hidden" name="bebidas[3][nombre]" value="Nestea">
                            <input type="hidden" name="bebidas[3][tipo]" value="Refrescos">
                        </td>
                        <td><input type="number" name="bebidas[3][uds]" id="nestea"></td>
                        <td><input type="number" name="bebidas[3][precio]" id="precio-nestea" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Fanta de naranja:
                            <input type="hidden" name="bebidas[4][codigo]" value="0021">
                            <input type="hidden" name="bebidas[4][nombre]" value="Fanta de naranja">
                            <input type="hidden" name="bebidas[4][tipo]" value="Refrescos">
                        </td>
                        <td><input type="number" name="bebidas[4][uds]" id="fanta-naranja"></td>
                        <td><input type="number" name="bebidas[4][precio]" id="precio-fanta-naranja" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Fanta de limón:
                            <input type="hidden" name="bebidas[5][codigo]" value="0022">
                            <input type="hidden" name="bebidas[5][nombre]" value="Fanta de limón">
                            <input type="hidden" name="bebidas[5][tipo]" value="Refrescos">
                        </td>
                        <td><input type="number" name="bebidas[5][uds]" id="fanta-limon"></td>
                        <td><input type="number" name="bebidas[5][precio]" id="precio-fanta-limon" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Tónica:
                            <input type="hidden" name="bebidas[6][codigo]" value="0023">
                            <input type="hidden" name="bebidas[6][nombre]" value="Tónica">
                            <input type="hidden" name="bebidas[6][tipo]" value="Refrescos">
                        </td>
                        <td><input type="number" name="bebidas[6][uds]" id="tonica"></td>
                        <td><input type="number" name="bebidas[6][precio]" id="precio-tonica" min="0" step="0.01"></td>
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
                            <input type="hidden" name="bebidas[7][codigo]" value="0024">
                            <input type="hidden" name="bebidas[7][nombre]" value="Cerveza de barril">
                            <input type="hidden" name="bebidas[7][tipo]" value="Alcohol">
                        </td>
                        <td><input type="number" name="bebidas[7][uds]" id="cerveza-barril"></td>
                        <td><input type="number" name="bebidas[7][precio]" id="precio-cerveza-barril" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Cerveza tostada:
                            <input type="hidden" name="bebidas[8][codigo]" value="0025">
                            <input type="hidden" name="bebidas[8][nombre]" value="Cerveza tostada">
                            <input type="hidden" name="bebidas[8][tipo]" value="Alcohol">
                        </td>
                        <td><input type="number" name="bebidas[8][uds]" id="cerveza-tostada"></td>
                        <td><input type="number" name="bebidas[8][precio]" id="precio-cerveza-tostada" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Cerveza Cruzcampo:
                            <input type="hidden" name="bebidas[9][codigo]" value="0026">
                            <input type="hidden" name="bebidas[9][nombre]" value="Cerveza Cruzcampo">
                            <input type="hidden" name="bebidas[9][tipo]" value="Alcohol">
                        </td>
                        <td><input type="number" name="bebidas[9][uds]" id="cerveza-cruzcampo"></td>
                        <td><input type="number" name="bebidas[9][precio]" id="precio-cerveza-cruzcampo" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Vino tinto:
                            <input type="hidden" name="bebidas[10][codigo]" value="0027">
                            <input type="hidden" name="bebidas[10][nombre]" value="Vino tinto">
                            <input type="hidden" name="bebidas[10][tipo]" value="Alcohol">
                        </td>
                        <td><input type="number" name="bebidas[10][uds]" id="vino-tinto"></td>
                        <td><input type="number" name="bebidas[10][precio]" id="precio-vino-tinto" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Vino blanco:
                            <input type="hidden" name="bebidas[11][codigo]" value="0028">
                            <input type="hidden" name="bebidas[11][nombre]" value="Vino blanco">
                            <input type="hidden" name="bebidas[11][tipo]" value="Alcohol">
                        </td>
                        <td><input type="number" name="bebidas[11][uds]" id="vino-blanco"></td>
                        <td><input type="number" name="bebidas[11][precio]" id="precio-vino-blanco" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Vino dulce:
                            <input type="hidden" name="bebidas[12][codigo]" value="0029">
                            <input type="hidden" name="bebidas[12][nombre]" value="Vino dulce">
                            <input type="hidden" name="bebidas[12][tipo]" value="Alcohol">
                        </td>
                        <td><input type="number" name="bebidas[12][uds]" id="vino-dulce"></td>
                        <td><input type="number" name="bebidas[12][precio]" id="precio-vino-dulce" min="0" step="0.01"></td>
                    </tr>
                </table><br>

                <input type="submit" value="Enviar" name="modificarBebida"><br>
            </form>
            <br><a href="/views/admin/admin.php">Volver</a>
        </section>
    </main>
</body>

</html>