<?php

namespace ControllerAdmin;

require_once dirname(__DIR__, 2) . '/models/admin/Producto.php';

use ModelsAdmin\Producto;

$productos = array();

switch (true) {
    case isset($_POST['modificarComida']):
        $productos[] = $_POST['comidas'];
        $producto = new Producto($_POST['comidas']);
        $producto->insertarBaseDatos($producto->productos);
        break;

    case isset($_POST['modificarBebida']):
        $productos[] = $_POST['bebidas'];
        $producto = new Producto($_POST['bebidas']);
        $producto->insertarBaseDatos($producto->productos);
        break;

    case isset($_POST['modificarPostre']):
        $productos[] = $_POST['postres'];
        $producto = new Producto($_POST['postres']);
        $producto->insertarBaseDatos($producto->productos);
        break;
}


$productoController = new ProductoController();
$productoController->mostrarVistaStock();


class ProductoController
{
    public function __construct() {}

    //Método para mostar la vista de Registro
    public function mostrarVistaStock()
    {
        switch (true) {
            case isset($_POST['modificarComida']):
                header("Location: ../../views/admin/stockComida.php");
                exit;

            case isset($_POST['modificarBebida']):
                header("Location: ../../views/admin/stockBebida.php");
                exit;

            case isset($_POST['modificarPostre']):
                header("Location: ../../views/admin/stockPostre.php");
                exit;
        }
    }
}
