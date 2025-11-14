<?php @session_start();
$paginaActual = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>


<body>
    <header class="container_header">
        <div class="logo"><a href="/views/frontend/index.php" class="volver_ppal"><span class="x">&#88;</span><span class="ito">ITO</span></a></div>

        <div class="menu_nav">
            <nav class="nav">
                <ul>
                    <?php if (isset($_SESSION['id_usuario'])) { ?>
                        <li>
                            <a href="/views/frontend/miPerfil.php"
                                class="link_menu <?= $paginaActual == 'miPerfil.php' ? 'activo' : '' ?>">
                                MI PERFIL
                            </a>
                        </li>
                        <li>
                            <a href="/controllers/frontend/ReservaController.php"
                                class="link_menu <?= $paginaActual == 'ReservaController.php' ? 'activo' : '' ?>">
                                RESERVA
                            </a>
                        </li>
                    <?php } ?>


                    <li><a href="/views/frontend/carta.php" class="link_menu <?= $paginaActual == 'carta.php' ? 'activo' : '' ?>">CARTA</a></li>
                    <!-- <li><a class="link_menu" href="#carrito">CARRITO</a></li> -->
                    <li><a href="/views/frontend/contacto.php" class="link_menu <?= $paginaActual == 'contacto.php' ? 'activo' : '' ?>">CONTACTO</a></li>
                </ul>
            </nav>
        </div>
        <?php if (isset($_SESSION['id_usuario'])): ?>
            <div class="container_carrito"> 
                <a href="/views/frontend/carrito.php" class="carrito <?= $paginaActual == 'carrito.php' ? 'activo' : '' ?>" title="Ir al carrito"> 
                    <i class="fas fa-shopping-cart"></i> 
                </a> 
            </div>
        <?php endif; ?>

        <div class="cotainer_form">

            <?php if (isset($_SESSION['id_usuario'])) { ?>

                <a href="/views/frontend/logoff.php" class="btn_logoff">Cerrar Sesión</a>

            <?php

            } else {

            ?>

                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);  ?>" method="post">
                    <input type="submit" class="btn_login" value="Iniciar Sesión" name="iniciarSesion">
                </form>

            <?php } ?>
        </div>
    </header>
</body>

</html>

<?php
if (isset($_POST['iniciarSesion'])) {
    header("Location: /views/frontend/login.php");
    exit;
}


?>