<?php

namespace ControllerAdmin;

require_once dirname(__DIR__, 2) . '/models/admin/Producto.php';

use ModelsAdmin\Producto;

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
            default:
                header("Location: ../../views/admin/nuevosProductos.php");
                exit;
        }
    }
}

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
        print_r($_POST['postres']);
        $producto = new Producto($_POST['postres']);
        $producto->insertarBaseDatos($producto->productos);
        break;
}

if(isset($_POST['crearNuevoProducto'])){
    $nuevoProducto = [
        'uds_stock' => $_POST['uds_stock'],
        'nombre_corto' => $_POST['nombre_corto'],
        'nombre_largo' => $_POST['nombre_largo'],
        'descripcion' => $_POST['descripcion'],
        'precio_unitario' => $_POST['precio_unitario']
    ];
    

    $categoria = [
        'nombre_categoria' => $_POST['nombre_categoria'],
        'tipo_categoria' => $_POST['tipo_categoria'],
        'modalidad_producto' => $_POST['modalidad_producto']
    ];

    $producto = new Producto($nuevoProducto);
    if(isset($_SESSION['producto_creado'])){
        unset($_SESSION['producto_creado']);
        $_SESSION['producto_creado'] = $producto->crearProductoNuevo($nuevoProducto, $categoria);
    } else {
        $_SESSION['producto_creado'] = $producto->crearProductoNuevo($nuevoProducto, $categoria);
    }
}


$productoController = new ProductoController();
$productoController->mostrarVistaStock();



