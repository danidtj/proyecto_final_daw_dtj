<?php

session_start();

if (!isset($_SESSION['id_usuario'])) {

    header("Location: /proyecto_final_daw_dtj/views/frontend/index.php");
    exit;
}

use ModelsFrontend\Rol;

require_once dirname(__DIR__, 2) . '/models/frontend/Rol.php';
$rol = new Rol();
$nombre_rol = $rol->obtenerNombreRolPorIdUsuario($_SESSION['id_usuario']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante XITO</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>


<body>
    <?php include_once __DIR__ . '/../partials/headerAdmin.php'; ?>
    <main>
        <?php
        

        if ($nombre_rol === "Administrador") { ?>

            <section class="container_form admin_index_container">
                <p>Modificar el stock de BEBIDAS</p><br>
                <a href="/proyecto_final_daw_dtj/views/admin/stockBebida.php" class="btn_modificarStock">Modificar</a>
            </section>
            <section class="container_form admin_index_container">
                <p>Modificar el stock de COMIDAS</p><br>
                <a href="/proyecto_final_daw_dtj/views/admin/stockComida.php" class="btn_modificarStock">Modificar</a>
            </section>
            <section class="container_form admin_index_container">
                <p>Modificar el stock de POSTRES</p><br>
                <a href="/proyecto_final_daw_dtj/views/admin/stockPostre.php" class="btn_modificarStock">Modificar</a>
            </section>

        <?php } else { ?>

            <section class="container_form admin_index_container">
                <p>Consultar el stock de BEBIDAS</p><br>
                <a href="/proyecto_final_daw_dtj/views/admin/stockBebida.php" class="btn_modificarStock">Consultar</a>
            </section>
            <section class="container_form admin_index_container">
                <p>Consultar el stock de COMIDAS</p><br>
                <a href="/proyecto_final_daw_dtj/views/admin/stockComida.php" class="btn_modificarStock">Consultar</a>
            </section>
            <section class="container_form admin_index_container">
                <p>Consultar el stock de POSTRES</p><br>
                <a href="/proyecto_final_daw_dtj/views/admin/stockPostre.php" class="btn_modificarStock">Consultar</a>
            </section>

        <?php }
        ?>


    </main>
    <?php include_once __DIR__ . '/../partials/footer.php'; ?>
</body>

</html>