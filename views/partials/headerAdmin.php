<?php
@session_start();

use ModelsFrontend\Rol;

require_once dirname(__DIR__, 2) . '/models/frontend/Rol.php';
$rol = new Rol();
$nombre_rol = $rol->obtenerNombreRolPorIdUsuario($_SESSION['id_usuario']);

$paginaActual = basename($_SERVER['PHP_SELF']);

if (isset($_POST['cerrarSesion'])) {

    // Y la eliminamos
    unset($_SESSION['id_usuario']);
    header("Location: /proyecto_final_daw_dtj/views/frontend/index.php");
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css/general.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css/adminheader.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css/adminIndex.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css/adminFooter.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css/adminTablasproductos.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css/adminProductos.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css/adminReservas.css">
    <link rel="stylesheet" href="/proyecto_final_daw_dtj/assets/css/admin_mediaqueriesHeader.css">
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