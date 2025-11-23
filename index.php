<?php
session_start();
require 'vendor/autoload.php';
use Bootstrap\Router;
require_once __DIR__ . '/bootstrap/bootstrap.php';


/* Almacena en path a través de la comprobación ternaria.
 Si la condición es verdadera, se ejecuta lo que está después del interrogante
 En caso contrario, se ejecuta lo que está después de los dos puntos.*/
 // El método trim() elimina el slash tanto al inicio como al final de lo enviado por GET['path'].
$path = isset($_GET['path']) ? trim($_GET['path'], '/') : 'home';


// Handle logout separately
/*if ($path === 'logout') {
    session_destroy();
    header("Location: /login");
    exit();
}*/
//holi moxitiiiiiiiiiiiiiii
// Route the request
Router::handleRequest($path);
?>

<!-- Cerrar sesión si el usuario cierra la pestaña o, en su defecto, el navegador -->
<!--<script>
window.addEventListener("beforeunload", function() {
  navigator.sendBeacon("cerrarPestania.php");
});
</script>-->

