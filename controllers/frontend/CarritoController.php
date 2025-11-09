<?php

namespace ControllerFrontend;

@session_start();



 // Clave secreta (reemplazar con la clave real)













/*if (isset($_SESSION['id_usuario'])) {
        //echo "Sesión iniciada como: " . $_SESSION['usuario']['nombre_usuario'];

        require_once dirname(__DIR__) . '/utilidades/SessionStorageController.php';
        //$_SESSION['productosAlmacenados'];

        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['eleccionCarta']) && !empty($_POST['productosCarrito'])) {

            $storage = new SessionStorageController();
            $productos = array();

            $carritoController = new CarritoController();

            if (!empty($storage->getSessionStorage("productosCarrito"))) {
                //$productos[] = $storage->getSessionStorage("productosCarrito");
                //$storage->removeSessionStorage("productosCarrito");
                $storage->setSessionStorage("productosCarrito", $_POST['productosCarrito']);
                var_dump($storage->getSessionStorage("productosCarrito"));
                array_push($_SESSION['productosAlmacenados'], $storage->getSessionStorage("productosCarrito"));
            } else {
                $storage->setSessionStorage("productosCarrito", $_POST['productosCarrito']);
                $storage->setSessionStorage("productosAlmacenados", $storage->getSessionStorage("productosCarrito"));
                //$_SESSION['productosAlmacenados'] = $storage->getSessionStorage("productosCarrito");
            }
        }

        $carritoController = new CarritoController();
        $carritoController->mostrarVistaCarrito();
    } else {
        echo '<script>
        alert("Debes iniciar sesión para acceder a esta página.");
        window.location.href = "../../home";
    </script>';

        // En caso de que JavaScript esté deshabilitado
        header("Refresh: 3; URL=../../home"); // Espera 3 segundos y redirige
        exit();
    }
}*/

class CarritoController
{
    public function mostrarVistaCarrito()
    {
        require_once dirname(__DIR__, 2) . '/views/frontend/carrito.php';
    }

    
}

/*require_once dirname(__DIR__, 2) . '/views/frontend/cesta.php';

// Comprobamos si se quiere añadir un producto a la cesta
if (isset($_POST['comanda'])) {
    $cesta->newArticulo($_POST['cod']);
    $cesta->saveCesta();
}

class CarritoController {
    protected $productos = array();

    // Introduce un nuevo artículo en la cesta de la compra
    public function newArticulo($codigo) {
        $producto = Carrito::getProducto($codigo);
        $this->productos[] = $producto;
    }
    
    // Obtiene los artículos en la cesta
    public function getProductos() { 
        return $this->productos; 
    }
    
    // Obtiene el coste total de los artículos en la cesta
    /*public function getCoste() {
        $coste = 0;
        foreach ($this->productos as $p) {
            $coste += $p->getPVP();
        }
        return $coste;
    }*/
    
    // Devuelve true si la cesta está vacía
    /*public function isVacia() {
        return (count($this->productos) == 0);
    }
    
    // Guarda la cesta de la compra en la sesión del usuario
    public function saveCesta() { 
        $_SESSION['cesta'] = $this; 
    }
    
    // Recupera la cesta de la compra almacenada en la sesión del usuario
    public static function loadCesta() {
        if (!isset($_SESSION['cesta'])) {
            return new CarritoController();
        } else {
            return $_SESSION['cesta'];
        }
    }
}

?>*/


/* --- SCRIPT JS PARA GESTIONAR ELIMINAR SIN RECARGAR Y ACTUALIZAR PRECIO --- */
?>

