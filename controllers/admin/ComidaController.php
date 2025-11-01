<?php

namespace ControllerAdmin;

require_once dirname(__DIR__, 2) . '/models/admin/Comida.php';

use ModelsAdmin\Comida;

$comidas = array();

if (isset($_POST['modificarComida'])) {

    $comidas[] = $_POST['comidas'];
    $comida = new Comida($_POST['comidas']);

    $comida->insertarBaseDatos($comida->comidas);
}


$comidaController = new ComidaController();
$comidaController->mostrarVistaStockComida();


class ComidaController
{
    public function __construct() {}

    //Método para mostar la vista de Registro
    public function mostrarVistaStockComida()
    {
        //Comprobamos que no se hayan enviado previamente los encabezados por HTTP. En caso afirmativo, redirecciona mediante código javascript
        if (headers_sent()) {
            echo "<script> window.location.href = '../../views/admin/stockComida.php'; </script>";
        } else {
            header("Location: ../../views/admin/stockComida.php");
            exit;
        }
    }
}
