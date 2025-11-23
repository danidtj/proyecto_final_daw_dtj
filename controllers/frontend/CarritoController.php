<?php

namespace ControllerFrontend;

@session_start();

class CarritoController
{
    public function mostrarVistaCarrito()
    {
        require_once dirname(__DIR__, 2) . '/views/frontend/carrito.php';
    }

    
}


?>

