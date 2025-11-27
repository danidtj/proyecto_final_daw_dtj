<?php
@session_start();

$paginaActual = basename($_SERVER['PHP_SELF']);

if (isset($_POST['iniciarSesion'])) {
    header("Location: /proyecto_final_daw_dtj/views/frontend/login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css/general.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css/header.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css/footer.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css/carta.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css/index.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css/reserva.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css/miPerfil.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css/contacto.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css/carrito.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css/popupTerminos.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css/popup.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css/mediaqueries_header.css">
    <title>Restaurante XITO</title>
</head>


<body>
    <header class="container_header header_container">
        <div class="logo"><a href="/proyecto_final_daw_dtj/views/frontend/index.php" class="volver_ppal"><span class="x">&#88;</span><span class="ito">ITO</span></a></div>

        <div class="menu_nav">
            <nav class="nav">
                <ul>
                    <?php if (isset($_SESSION['id_usuario'])) { ?>
                        <li>
                            <a href="/proyecto_final_daw_dtj/views/frontend/miPerfil.php"
                                class="link_menu <?= $paginaActual == 'miPerfil.php' ? 'activo' : '' ?>">
                                MI PERFIL
                            </a>
                        </li>
                        <li>
                            <a href="/proyecto_final_daw_dtj/controllers/frontend/ReservaController.php"
                                class="link_menu <?= $paginaActual == 'ReservaController.php' ? 'activo' : '' ?>">
                                RESERVA
                            </a>
                        </li>
                    <?php } ?>


                    <li><a href="/proyecto_final_daw_dtj/views/frontend/carta.php" class="link_menu <?= $paginaActual == 'carta.php' ? 'activo' : '' ?>">CARTA</a></li>
                    
                    <li><a href="/proyecto_final_daw_dtj/views/frontend/contacto.php" class="link_menu <?= $paginaActual == 'contacto.php' ? 'activo' : '' ?>">CONTACTO</a></li>
                </ul>
            </nav>
        </div>
        <?php if (isset($_SESSION['id_usuario'])): ?>
            <div class="container_carrito">
                <a href="/proyecto_final_daw_dtj/views/frontend/carrito.php" class="carrito <?= $paginaActual == 'carrito.php' ? 'activo' : '' ?>" title="Ir al carrito">
                    <i class="fas fa-shopping-cart"></i>
                </a>
            </div>
        <?php endif; ?>

        <div class="cotainer_form">

            <?php if (isset($_SESSION['id_usuario'])) { ?>

                <a href="/proyecto_final_daw_dtj/views/frontend/logoff.php" class="btn_logoff">Cerrar Sesión</a>

            <?php

            } else {

            ?>

                <form class="header_formulario" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);  ?>" method="post">
                    <input type="submit" class="btn_login" value="Iniciar Sesión" name="iniciarSesion">
                </form>

            <?php } ?>
        </div>
    </header>

    <script>
        // Animar icono del carrito
        function animarCarrito() {
            const icono = document.querySelector(".carrito i");
            if (!icono) return;

            icono.classList.add("animar");

            setTimeout(() => {
                icono.classList.remove("animar");
            }, 300);
        }
    </script>
</body>

</html>