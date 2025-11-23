<?php
// Recuperamos la información de la sesión
@session_start();

//Eliminamos las variables de sesión con información del usuario
unset($_SESSION['carrito']);
unset($_SESSION['id_usuario']);

//Limpiamos la sesión
$_SESSION = array();
session_destroy();


//Redirigimos al usuario a la página de login
header("Location: /proyecto_final_daw_dtj/views/frontend/index.php");
exit();
