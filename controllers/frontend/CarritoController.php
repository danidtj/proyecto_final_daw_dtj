<?php

namespace ControllerUtilidades;

@session_start();

//Mediante este bloque se detecta la acción GET, accion=eliminar y el código del producto a eliminar por AJAX
if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && isset($_GET['codigo'])) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    //Almacena el código del producto del que se ha seleccionado el botón de eliminar
    $codigoEliminar = $_GET['codigo'];

    if (isset($_SESSION['carrito'])) {
        foreach ($_SESSION['carrito'] as $index => $producto) {
            if ($producto['codigo_producto'] == $codigoEliminar) {
                //Elimina de la superglobal el producto mediante el índice
                unset($_SESSION['carrito'][$index]);
                // Con array_values recuperamos los índices consecutivos del array ya que con unset se quedan los índices originales
                $_SESSION['carrito'] = array_values($_SESSION['carrito']);
                break;
            }
        }
    }

    //Una vez se ha eliminado el producto, recalculamos el nuevo precio total del carrito
    $nuevoPrecioTotal = 0;
    if (!empty($_SESSION['carrito'])) {
        foreach ($_SESSION['carrito'] as $producto) {
            $nuevoPrecioTotal += $producto['precio_producto'];
        }
    }

    // Devolvemos JSON con estado y total
    header('Content-Type: application/json');
    echo json_encode([
        "status" => "ok",
        "total" => $nuevoPrecioTotal
    ]);
    exit;
}
/* --- FIN BLOQUE AJAX --- */


if (session_status() === PHP_SESSION_ACTIVE) {

    if (isset($_SESSION['id_usuario']) && isset($_SESSION['carrito'])) {

        $productosCarrito = $_SESSION['carrito'];
        $precioTotalCarrito = 0;

        //array_count_values para contar las cantidades de cada producto en la cesta y array_column para obtener una columna específica del ID
        $resultado = array_count_values(array_column($productosCarrito, 'codigo_producto'));

        foreach ($resultado as $id => $cantidad) {
            foreach ($productosCarrito as $producto) {
                if ($producto['codigo_producto'] == $id) {
                    $precioTotalCarrito += ($producto['precio_producto'] * $cantidad);
                    
                    // Sustituimos el formulario tradicional por un botón AJAX
                    echo "<div id='producto-{$producto['codigo_producto']}'>";
                    echo $producto['nombre_producto'] . " .... x" . $cantidad . " ........ Subtotal: $" . ($producto['precio_producto'] * $cantidad) . " ";
                    echo "<button type='button' onclick='eliminar(\"" . $producto['codigo_producto'] . "\")'>X</button>";
                    echo "</div>";

                    break;
                }
            }
        }

        if ($precioTotalCarrito > 0) {
            echo "--------------------------\n";
            echo "<div id='precioTotal'>Precio total del carrito: $" . $precioTotalCarrito . "</div>\n";
        } else {
            echo "<div id='carritoVacio'>El carrito está vacío.</div>";
        }

    } else {
        /*echo '<script>
        alert("Debes iniciar sesión para acceder a esta página.");
        window.location.href = "../../home";
    </script>';*/

        // En caso de que JavaScript esté deshabilitado
        /*header("Refresh: 3; URL=../../home"); // Espera 3 segundos y redirige
        exit();*/

        $carritoController = new CarritoController();
        $carritoController->mostrarVistaCarrito();
    }
}



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
<script>
function eliminar(codigo) {
    //Objeto para enviar y recibir datos del servidor sin recargar la página
    var xhttp = new XMLHttpRequest();
    //Define una función que se ejecutará automáticamente cada vez que haya una petición AJAX con diferente estado
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            try {
                let respuesta = JSON.parse(this.responseText);
                //Confirmación de la eliminación del producto
                if (respuesta.status === "ok") {
                    // Eliminamos el producto del DOM
                    let prod = document.getElementById('producto-' + codigo);
                    //Elimina el elemento visualmente
                    if (prod) prod.remove();

                    // Actualizamos el total del carrito en pantalla
                    let totalElem = document.getElementById('precioTotal');
                    if (totalElem) {
                        //Accede al nuevo valor del precio total del carrito dado por JSON en "total"
                        if (respuesta.total > 0) {
                            totalElem.textContent = "Precio total del carrito: $" + respuesta.total;
                        } else {
                            totalElem.textContent = "";
                        }
                    }

                    // Si ya no hay productos, mostramos el mensaje de carrito vacío
                    if (document.querySelectorAll("[id^='producto-']").length === 0) {
                        document.getElementById('carritoVacio').style.display = "block";
                    }
                }
            } catch (e) {
                console.error("Error al procesar respuesta:", e, this.responseText);
            }
        }
    };
    //Solicitud AJAX con método GET con envío a sí mismo
    xhttp.open("GET", "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?accion=eliminar&codigo=" + codigo, true);
    //Envía la solicitud al servidor y cuando responde se pone en marcha de nuevo el bloque onreadystatechange
    xhttp.send();
}
</script>
