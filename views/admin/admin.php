<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante XITO</title>
    <link rel="stylesheet" href="/assets/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>


<body>
    <?php include_once __DIR__ . '/../partials/headerAdmin.php'; ?>
    <main>
        <hr id="hr1">
        <hr id="hr4">

        <section class="container_form">
            <p>Modificar el stock de BEBIDAS</p><br>
            <a href="/views/admin/stockBebida.php" class="btn_modificarStock">Modificar</a>
        </section>
        <section class="container_form">            
            <p>Modificar el stock de COMIDAS</p><br>
            <a href="/views/admin/stockComida.php" class="btn_modificarStock">Modificar</a>
        </section>
        <section class="container_form">
            <p>Modificar el stock de POSTRES</p><br>
            <a href="/views/admin/stockPostre.php" class="btn_modificarStock">Modificar</a>
        </section>
    </main>
</body>

</html>