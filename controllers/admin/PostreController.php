<?php

namespace ControllerAdmin;

require_once dirname(__DIR__, 2) . '/models/admin/Postre.php';

use ModelsAdmin\Postre;

$postres = array();

if (isset($_POST['modificarPostre'])) {

    $postres[] = $_POST['postres'];
    $postre = new Postre($_POST['postres']);

    $postre->insertarBaseDatos($postre->postres);
}

$postreController = new PostreController();
$postreController->mostrarVistaStockPostre();



class PostreController
{
    public function __construct() {}

    //Método para mostar la vista de Registro
    public function mostrarVistaStockPostre()
    {
        //Comprobamos que no se hayan enviado previamente los encabezados por HTTP. En caso afirmativo, redirecciona mediante código javascript
        if (headers_sent()) {
            echo "<script> window.location.href = '../../views/admin/stockPostre.php'; </script>";
        } else {
            header("Location: ../../views/admin/stockPostre.php");
            exit;
        }
    }
}
