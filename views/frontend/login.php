<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Restaurante XITO</title>
</head>


<body>
    <hr id="hr1">
    <!--<hr id="hr2">
        <hr id="hr3">-->
    <hr id="hr4">
    <main>
        <section class="container_form">
            <h2 class="titulo_form">Inicio de sesión</h2>
            <!-- Envío del formulario a sí mismo para comprobar si los datos introducidos son correctos o no-->
            <form action="/controllers/frontend/LoginController.php" method="post">
                <?php

                if (!empty($_POST['user'])) {
                    if (isset($_SESSION['id_usuario'])) {
                        unset($_SESSION['id_usuario']);
                    }
                    $_SESSION['usuario'] = $_POST['user'];

                    echo "<input type='text' name='user' id='user' value=" . $_SESSION['usuario'] . " >";
                } else {
                    echo "<input type='text' name='user' id='user' placeholder='Dirección de email'>";
                }
                ?>


                <input type="password" name="password" id="password" placeholder="Contraseña">
                <button type="button" id="togglePassword">👁️</button>
                <input type="submit" class="btn_login" value="Iniciar sesión" name="submit"><br><br>
            </form>
            <p>¿Aún no estás registrado? <a href="/controllers/frontend/RegistroController.php" class="btn_login">¡Hazlo!</a></p>
        </section>
    </main>

    <!-- Se da la opción al usuario de poder visualizar la contraseña por si tuviera dudas de lo escrito -->
    <script>
        document.querySelector("#togglePassword").onclick = () => {
            const p = document.querySelector("#password");
            p.type = p.type === "password" ? "text" : "password";
        };
    </script>

</body>

</html>