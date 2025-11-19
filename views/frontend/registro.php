<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/assets/css_pages/carta.css">
    <link rel="stylesheet" href="/assets/css_pages/contacto.css">
    <link rel="stylesheet" href="/assets/css_pages/footer.css">
    <link rel="stylesheet" href="/assets/css_pages/general.css">
    <link rel="stylesheet" href="/assets/css_pages/header.css">
    <link rel="stylesheet" href="/assets/css_pages/images.css">
    <link rel="stylesheet" href="/assets/css_pages/index.css">
    <link rel="stylesheet" href="/assets/css_pages/miPerfil.css">
    <link rel="stylesheet" href="/assets/css_pages/nuevosProductos.css">
    <link rel="stylesheet" href="/assets/css_pages/popup.css">
    <link rel="stylesheet" href="/assets/css_pages/productosAdmin.css">
    <link rel="stylesheet" href="/assets/css_pages/registro.css">
    <link rel="stylesheet" href="/assets/css_pages/reserva.css">
    <link rel="stylesheet" href="/assets/css_pages/terminos.css">
    <link rel="stylesheet" href="/assets/css_mediaqueries/mediaqueries_index.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&family=Lato&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <title>Restaurante XITO</title>
</head>

<body>
    <main>
        <section class="container_form registro_container" aria-labelledby="titulo-form">
            <h2 id="titulo-form" class="titulo_form">Formulario de registro</h2>
            <form action="/controllers/frontend/RegistroController.php" method="post" class="formulario registro_formulario">
                <div>
                    <label for="nombre_usuario">Nombre:</label><br>
                    <input type="text" name="nombre_usuario" id="nombre_usuario" placeholder="Nombre" title="Introduzca su nombre" minlength="2" maxlength="20"
                        pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ]{2,20}" required aria-required="true" />
                    <p class="mensaje-error" id="error-nombre" role="alert" aria-live="assertive"></p><!-- Espacio para imprimir por pantalla el mensaje de error en validación -->
                </div>
                <div>
                    <label for="apellidos_usuario">Apellidos:</label><br>
                    <input type="text" name="apellidos_usuario" id="apellidos_usuario" placeholder="Apellidos" title="Introduzca sus apellidos" minlength="2"
                        maxlength="20" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{2,40}" required aria-required="true" />
                    <p class="mensaje-error" id="error-apellidos" role="alert" aria-live="assertive"></p><!-- Espacio para imprimir por pantalla el mensaje de error en validación -->
                </div>
                <div>
                    <label for="email_usuario">Email:</label><br>
                    <input type="email" name="email_usuario" id="email_usuario" title="Introduzca su email" placeholder="email@email.com"
                        required aria-required="true" />
                    <p class="mensaje-error" id="error-email" role="alert" aria-live="assertive"></p><!-- Espacio para imprimir por pantalla el mensaje de error en validación -->
                </div>
                <div class="registro_container_pssojo">
                    <label for="password_usuario">Contraseña:</label><br>
                    <input type="password" name="password_usuario" id="password_usuario" placeholder="Escribe tu contraseña" title="Introduzca una contraseña"
                        pattern=".{6,20}" required aria-required="true" />
                    <div class="registro_ojo"><button type="button" id="togglePassword">👁️</button></div>
                    <p class="mensaje-error" id="error-password" role="alert" aria-live="assertive"></p><!-- Espacio para imprimir por pantalla el mensaje de error en validación -->
                </div>
                <div>
                    <label for="telefono_usuario">Teléfono:</label><br>
                    <input type="number" name="telefono_usuario" id="telefono_usuario" placeholder="Escribe tu teléfono" title="Introduzca su teléfono"
                        pattern="[0-9]{9}" maxlength="9" required aria-required="true" />
                    <p class="mensaje-error" id="error-telefono" role="alert" aria-live="assertive"></p><!-- Espacio para imprimir por pantalla el mensaje de error en validación -->
                </div>
                <div>
                    <button type="submit" name="registro" class="btn_login">Regístrate</button>
                </div>
            </form>
        </section>
    </main>



    <!-- Se da la opción al usuario de poder visualizar la contraseña por si tuviera dudas de lo escrito -->
    <script>
        document.querySelector("#togglePassword").onclick = () => {
            const p = document.querySelector("#password_usuario");
            p.type = p.type === "password" ? "text" : "password";
        };
    </script>
     <script src="/assets/js/validacionRegistro.js"></script>
</body>

</html>