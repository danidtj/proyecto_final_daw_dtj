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
    <title>Restaurante XITO - Administrador</title>
</head>

<body>
    <header class="container_header">
        <div class="logo"><a href="/views/admin/admin.php" class="volver_ppal"><span class="x">&#88;</span><span class="ito">ITO</span></a></div>
        <div class="menu_nav">
            <nav class="nav">
                <ul>
                    <li><a href="/views/admin/stockComida.php" class="link_menu <?= $paginaActual == 'stockComida.php' ? 'activo' : '' ?>">COMIDAS</a></li>
                    <li><a href="/views/admin/stockBebida.php" class="link_menu <?= $paginaActual == 'stockBebida.php' ? 'activo' : '' ?>">BEBIDAS</a></li>
                    <li><a href="/views/admin/stockPostre.php" class="link_menu <?= $paginaActual == 'stockPostre.php' ? 'activo' : '' ?>">POSTRES</a></li>
                    <?php if ($nombre_rol === "Administrador") { ?>
                        <li><a href="/views/admin/nuevosProductos.php" class="link_menu <?= $paginaActual == 'nuevosProductos.php' ? 'activo' : '' ?>">PRODUCTOS</a></li>
                    <?php } ?>
                    <li><a href="/views/admin/reservas.php" class="link_menu <?= $paginaActual == 'reservas.php' ? 'activo' : '' ?>">RESERVAS</a></li>
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
    header("Location: /home");
}


?>