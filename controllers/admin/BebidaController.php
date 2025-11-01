<?php

namespace ControllerAdmin;

require_once dirname(__DIR__, 2) . '/models/admin/Bebida.php';

use ModelsAdmin\Bebida;

$bebidas = array();

if (isset($_POST['modificarBebida'])) {

    $bebidas[] = $_POST['bebidas'];
    $bebida = new Bebida($_POST['bebidas']);

    $bebida->insertarBaseDatos($bebida->bebidas);
}

$bebidaController = new BebidaController();
$bebidaController->mostrarVistaStockBebida();



class BebidaController
{
    public function __construct() {}

    //Método para mostar la vista de Registro
    public function mostrarVistaStockBebida()
    {
        //Comprobamos que no se hayan enviado previamente los encabezados por HTTP. En caso afirmativo, redirecciona mediante código javascript
        if (headers_sent()) {
            echo "<script> window.location.href = '../../views/admin/stockBebida.php'; </script>";
        } else {
            header("Location: ../../views/admin/stockBebida.php");
            exit;
        }
    }
}
