<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars($_POST['nombre']);
    $email = htmlspecialchars($_POST['email']);
    $mensaje = htmlspecialchars($_POST['mensaje']);

    // Dirección de destino
    $destino = "danidtj@gmail.com";
    $asunto = "Nuevo mensaje desde el formulario de contacto";

    // Cuerpo del mensaje
    $cuerpo = "Nombre: $nombre\n";
    $cuerpo .= "Email: $email\n";
    $cuerpo .= "Mensaje:\n$mensaje\n";

    // Cabeceras
    $cabeceras = "From: $email\r\n";
    $cabeceras .= "Reply-To: $email\r\n";

    // Enviar el email
    if (mail($destino, $asunto, $cuerpo, $cabeceras)) {
        echo "<p>Mensaje enviado correctamente. ¡Gracias por contactarnos!</p>";
    } else {
        echo "<p>Error al enviar el mensaje. Inténtalo de nuevo.</p>";
    }
} else {
    // Redirigir si se accede directamente
    header("Location: /proyecto_final_daw_dtj/views/frontend/index.php");
    exit;
}
