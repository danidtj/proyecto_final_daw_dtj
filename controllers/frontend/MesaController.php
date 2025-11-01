<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? null;
    if ($id) {
        // guardar en sesión o base de datos
        echo "Mesa $id seleccionada correctamente.";
    } else {
        echo "No se recibió ningún ID.";
    }
}
?>
