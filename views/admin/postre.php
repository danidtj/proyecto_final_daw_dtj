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
            <h2 class="titulo_form">MODIFICAR STOCK POSTRES</h2>
            <form action="/controllers/admin/PostreController.php" method="post">

                <h4>Postres</h4><br>
                <table border="1">
                    <tr>
                        <th>Postre</th>
                        <th>Uds</th>
                        <th>Precio</th>
                    </tr>

                    <tr>
                        <td>
                            Tarta de chocolate
                            <input type="hidden" name="postres[0][codigo]" value="0047">
                            <input type="hidden" name="postres[0][nombre]" value="Tarta de chocolate">
                            <input type="hidden" name="postres[0][tipo]" value="Tartas">
                        </td>
                        <td><input type="number" name="postres[0][uds]" id="tarta-chocolate"></td>
                        <td><input type="number" name="postres[0][precio]" id="precio-tarta-chocolate" min="0" step="0.01"></td>
                    </tr>

                    
                    <tr>
                        <td>
                            Tarta de queso:
                            <input type="hidden" name="postres[1][codigo]" value="0048">
                            <input type="hidden" name="postres[1][nombre]" value="Tarta de queso">
                            <input type="hidden" name="postres[1][tipo]" value="Tartas">
                        </td>
                        <td><input type="number" name="postres[1][uds]" id="tarta-queso"></td>
                        <td><input type="number" name="postres[1][precio]" id="precio-tarta-queso" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Tarta red velvet:
                            <input type="hidden" name="postres[2][codigo]" value="0049">
                            <input type="hidden" name="postres[2][nombre]" value="Tarta red velvet">
                            <input type="hidden" name="postres[2][tipo]" value="Tartas">
                        </td>
                        <td><input type="number" name="postres[2][uds]" id="tarta-velvet"></td>
                        <td><input type="number" name="postres[2][precio]" id="precio-tarta-velvet" min="0" step="0.01"></td>
                    </tr>

                    <tr>
                        <td>
                            Variado de frutas:
                            <input type="hidden" name="postres[3][codigo]" value="0050">
                            <input type="hidden" name="postres[3][nombre]" value="Variado de frutas">
                            <input type="hidden" name="postres[3][tipo]" value="Fruta">
                        </td>
                        <td><input type="number" name="postres[3][uds]" id="fruta"></td>
                        <td><input type="number" name="postres[3][precio]" id="precio-fruta" min="0" step="0.01"></td>
                    </tr>
                </table><br>

                <input type="submit" value="Enviar" name="modificarPostre"><br>
            </form>
            <br><a href="/views/admin/admin.php">Volver</a>
        </section>
    </main>
</body>

</html>