<?php
@session_start();

use ModelsFrontend\Rol;

require_once dirname(__DIR__, 2) . '/models/frontend/Rol.php';
$rol = new Rol();
$nombre_rol = $rol->obtenerNombreRolPorIdUsuario($_SESSION['id_usuario']);

$paginaActual = basename($_SERVER['PHP_SELF']);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/general.css">
<link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/adminHeader.css">
<link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/adminIndex.css">
<link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/adminFooter.css">
<link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/adminTablasproductos.css">
<link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/admin_mediaqueriesHeader.css">
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

    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css_mediaqueries/mediaqueries_index.css">-->
    <title>Restaurante XITO - Administrador</title>
</head>

<body>
    <header class="container_header header_container">
        <div class="logo"><a href="/proyecto_final_daw_dtj/views/admin/admin.php" class="volver_ppal"><span class="x">&#88;</span><span class="ito">ITO</span></a></div>
        <div class="menu_nav">
            <nav class="nav">
                <ul>
                    <li><a href="/proyecto_final_daw_dtj/views/admin/stockComida.php" class="link_menu <?= $paginaActual == 'stockComida.php' ? 'activo' : '' ?>">COMIDAS</a></li>
                    <li><a href="/proyecto_final_daw_dtj/views/admin/stockBebida.php" class="link_menu <?= $paginaActual == 'stockBebida.php' ? 'activo' : '' ?>">BEBIDAS</a></li>
                    <li><a href="/proyecto_final_daw_dtj/views/admin/stockPostre.php" class="link_menu <?= $paginaActual == 'stockPostre.php' ? 'activo' : '' ?>">POSTRES</a></li>
                    <?php if ($nombre_rol === "Administrador") { ?>
                        <li><a href="/proyecto_final_daw_dtj/views/admin/nuevosProductos.php" class="link_menu <?= $paginaActual == 'nuevosProductos.php' ? 'activo' : '' ?>">PRODUCTOS</a></li>
                    <?php } ?>
                    <li><a href="/proyecto_final_daw_dtj/views/admin/reservas.php" class="link_menu <?= $paginaActual == 'reservas.php' ? 'activo' : '' ?>">RESERVAS</a></li>
                </ul>
            </nav>
        </div>

        <div class="cotainer_form">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);  ?>" method="post">
                <input type="submit" class="btn_logoff" value="Cerrar Sesión" name="cerrarSesion">
            </form>
        </div>
    </header>
</body>

</html>

<?php
if (isset($_POST['cerrarSesion'])) {
    @session_start();

    // Y la eliminamos
    unset($_SESSION['id_usuario']);
    header("Location: /proyecto_final_daw_dtj/views/frontend/index.php");
}


?>