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
<link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/general.css">

<link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/header.css">
<link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/footer.css">
<link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/carta.css">
<link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/reserva.css">
<link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/miPerfil.css">
<link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/contacto.css">
<link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/carrito.css">
<link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/index.css">
<link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/popupTerminos.css">
<link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/mediaqueries_header.css">

    <!--<link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css_pages/admin.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css_pages/carrito.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css_pages/carta.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css_pages/contacto.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css_pages/footer.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css_pages/general.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css_pages/header.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css_pages/images.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css_pages/index.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css_pages/login.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css_pages/miPerfil.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css_pages/nuevosProductos.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css_pages/popup.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css_pages/productosAdmin.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css_pages/registro.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css_pages/reserva.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css_pages/reservasAdmin.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css_pages/terminos.css">
    

    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css_mediaqueries/mediaqueries_index.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css_mediaqueries/mediaqueries_forms.css">-->
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
                    <!-- <li><a class="link_menu" href="#carrito">CARRITO</a></li> -->
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

