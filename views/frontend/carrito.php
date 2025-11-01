<?php
/*@ session_start();
require_once dirname(__DIR__, 2) . '/controllers/utilidades/SessionStorageController.php';
//$_SESSION['productosAlmacenados'];

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['eleccionCarta']) && !empty($_POST['productosCarrito'])) {

    $storage = new SessionStorageController();
    $productos = array();

    if (!empty($storage->getSessionStorage("productosCarrito"))) {
        //$productos[] = $storage->getSessionStorage("productosCarrito");
        //$storage->removeSessionStorage("productosCarrito");
        $storage->setSessionStorage("productosCarrito", $_POST['productosCarrito']);
        array_push($_SESSION['productosAlmacenados'], $storage->getSessionStorage("productosCarrito"));
    } else {
        $storage->setSessionStorage("productosCarrito", $_POST['productosCarrito']);
        $storage->setSessionStorage("productosAlmacenados", $storage->getSessionStorage("productosCarrito"));
        //$_SESSION['productosAlmacenados'] = $storage->getSessionStorage("productosCarrito");
    }
}*/



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
    <hr id="hr1">
    <hr id="hr4">

    <?php

    include_once __DIR__ . '/../partials/header.php';

    ?>
    <main>
        <section class="container_form">
            <h2 class="titulo_form">CARRITO</h2>
            <ul>

                <?php
                if (!empty($_SESSION['productosAlmacenados'])) {

                    //RecursiveIteratorIterator crea el iterador y RecursiveArrayIterator lo recorre linealmente.
                    $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($_SESSION['productosAlmacenados']));
                    foreach ($iterator as $value) {
                        echo $value . "<br>";
                    }
                }
                ?>
            </ul>
            <form action="" method="post">
            </form>
        </section>
    </main>

    <?php include_once __DIR__ . '/../partials/footer.php'; ?>
</body>

</html>