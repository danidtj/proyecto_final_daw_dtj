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
                    <li><a class="link_menu" href="/views/admin/stockComida.php" >COMIDAS</a></li>
                    <li><a class="link_menu" href="/views/admin/stockBebida.php">BEBIDAS</a></li>
                    <!-- <li><a class="link_menu" href="#carrito">CARRITO</a></li> -->
                    <li><a class="link_menu" href="/views/admin/stockPostre.php">POSTRES</a></li>
                    <li><a class="link_menu" href="/views/admin/reservas.php">RESERVAS</a></li>
                </ul>
            </nav>
        </div>
        
        <div class="cotainer_form">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);  ?>" method="post">
                <input type="submit" value="Cerrar Sesión" name="cerrarSesion">
            </form>
        </div>
    </header>
</body>
</html>

<?php
 if(isset($_POST['cerrarSesion'])){
    @ session_start();
    
    // Y la eliminamos
    unset($_SESSION['id_usuario']);
    header("Location: /home");
 }


?>