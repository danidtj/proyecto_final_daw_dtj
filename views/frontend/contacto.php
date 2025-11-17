<?php
session_start();

require_once dirname(__DIR__, 2) . '/controllers/utilidades/enviarEmail.php';

if (isset($_POST['formularioContacto'])) {

    $emailDestinatario = "xitorestaurante@gmail.com";
    $nombreDestinatario = "Restaurante XITO";

    $contenidoCorreo = "<h2>Formulario de contacto en Restaurante XITO</h2>";
    $contenidoCorreo .= "<p>Estimado/a " . htmlspecialchars($nombreDestinatario) . ",</p>";
    $contenidoCorreo .= "<p>Desde el formulario de contacto de su restaurante, el usuario <strong>" . htmlspecialchars($_POST['nombre']) .
        "</strong>, con email " . htmlspecialchars($_POST['email']) . " ha enviado el siguiente mensaje:</p>";
    $contenidoCorreo .= "<blockquote>" . nl2br(htmlspecialchars($_POST['mensaje'])) . "</blockquote>";
    
    $asuntoCorreo = "Formulario de contacto en Restaurante XITO";

    enviarEmail($emailDestinatario, $nombreDestinatario, $asuntoCorreo, $contenidoCorreo);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Restaurante XITO</title>
</head>

<?php


?>

<body>
    <?php include_once __DIR__ . '/../partials/header.php'; ?>
    <main>
        <section class="container_form formulario_contacto">
            <h2 class="titulo_form">Formulario de contacto</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="formulario formulario_contacto">
                <div><label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Nombre" title="Introduzca su nombre" minlength="2" maxlength="20"
                        pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ]{2,20}" required aria-required="true" required>
                </div>

                <div><label for="email">Email de contacto:</label>
                    <input type="email" id="email" name="email" placeholder="Email" title="Introduzca su email" aria-placeholder="email@email.com"
                        pattern="[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*" required aria-required="true" required>
                </div>

                <div><label for="mensaje">Escribe lo que desees:</label>
                    <textarea name="mensaje" id="mensaje" placeholder="Escribe aquí tu mensaje" minlength="5" maxlength="500"
                        title="El mensaje debe tener un máximo de 500 caracteres." required aria-required="true"></textarea>
                </div>
                <div><input type="submit" class="btn_contacto" value="Enviar" name="formularioContacto"></div>
            </form>
        </section>
    </main>

    <?php include_once __DIR__ . '/../partials/footer.php'; ?>

</body>

</html>