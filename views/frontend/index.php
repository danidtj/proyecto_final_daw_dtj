<?php include_once __DIR__ . '/../partials/header.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante XITO</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>


<body>


    <!-- Popup de términos y condiciones -->
    <div id="popupTerminos">
        <div class="popup-content">
            <h2>Términos y Condiciones</h2>
            <p>
                Usamos tus datos personales según la LOPDGDD y el RGPD.
                Al continuar, aceptas nuestra Política de Privacidad.
                Puedes ejercer tus derechos de acceso, rectificación y supresión en cualquier momento.
            </p>
            <button id="aceptarTerminos">Aceptar</button>
        </div>
    </div>

    <main>
        <article class="container_info index_info">
            <h2>Restaurante XITO</h2>
            <p class="info_rest index_historia">Se abrió en el año 2011 en el humilde barrio de Antonio Domínguez.
                Este negocio familiar, fundado por el padre de
                familia, Diego, abrió sus puertas el 13 de julio del año 2011. Desde entonces, los vecinos se volcaron
                con el proyecto y el boca a boca hizo el resto.

                El negocio creció tanto en facturación en tan poco tiempo que Diego pudo meter en plantilla a parte de
                su familia: mujer e hijos.

                Toda la familia estaba involucrada en este restaurante familiar y con el esfuerzo y la unión de todos,
                consiguieron abrir un segundo local, esta vez en
                el barrio de Valdepasillas. Los vecinos de esta nueva ubicación también lo acogieron con los brazos
                abiertos y el negocio ha seguido prosperando.

                <span>Restaurante XITO</span> es un referente de la restauración en la ciudad pacense.
            </p>
        </article>
        <section class="container_video index_video">
            <video width="1000px" height="360px" autoplay controls muted>
                <source src="/../proyecto_final_def/assets/DJI_20250601173853_0111_D.MP4" type="video/mp4">
                Tu navegador no sorporta la etiqueta vídeo.
            </video>
        </section>
        <section class="container_plano index_container_plano">
            <h1 class="header_reserva">RESERVA CON NOSOTROS</h1>
            <section class="header_reserva index_querer_reservar">
                <p>¿Quieres reservar una mesa en nuestro restaurante? ¡Inicia sesión o regístrate si aún no lo has hecho!</p>
            </section>
            <table class="tabla">
                <tr class="fila">
                    <td class="celda">
                        <span class="silla1"></span>
                        <span class="silla2"></span>
                        <span class="mesa"></span>
                        <span class="silla3"></span>
                        <span class="silla4"></span>
                    </td>
                    <td class="celda"></td>
                    <td class="celda">
                        <span class="silla1"></span>
                        <span class="silla2"></span>
                        <span class="mesa"></span>
                        <span class="silla3"></span>
                        <span class="silla4"></span>
                    </td>
                    <td class="celda"></td>
                    <td class="celda">
                        <span class="silla1"></span>
                        <span class="silla2"></span>
                        <span class="mesa"></span>
                        <span class="silla3"></span>
                        <span class="silla4"></span>
                    </td>
                    <td class="celda"></td>
                </tr>
                <tr class="fila">
                    <td class="celda"></td>
                    <td class="celda">
                        <span class="silla1"></span>
                        <span class="silla2"></span>
                        <span class="mesa"></span>
                        <span class="silla3"></span>
                        <span class="silla4"></span>
                    </td>
                    <td class="celda"></td>
                    <td class="celda">
                        <span class="silla1"></span>
                        <span class="silla2"></span>
                        <span class="mesa"></span>
                        <span class="silla3"></span>
                        <span class="silla4"></span>
                    </td>
                    <td class="celda"></td>
                    <td class="celda">
                        <span class="silla1"></span>
                        <span class="silla2"></span>
                        <span class="mesa"></span>
                        <span class="silla3"></span>
                        <span class="silla4"></span>
                    </td>
                </tr>
                <tr class="fila">
                    <td class="celda">
                        <span class="silla1"></span>
                        <span class="silla2"></span>
                        <span class="mesa"></span>
                        <span class="silla3"></span>
                        <span class="silla4"></span>
                    </td>
                    <td class="celda"></td>
                    <td class="celda">
                        <span class="silla1"></span>
                        <span class="silla2"></span>
                        <span class="mesa"></span>
                        <span class="silla3"></span>
                        <span class="silla4"></span>
                    </td>
                    <td class="celda"></td>
                    <td class="celda">
                        <span class="silla1"></span>
                        <span class="silla2"></span>
                        <span class="mesa"></span>
                        <span class="silla3"></span>
                        <span class="silla4"></span>
                    </td>
                    <td class="celda"></td>
                </tr>
            </table>

        </section>
    </main>
    <?php include_once __DIR__ . '/../partials/footer.php' ?>

    <script src="/assets/js/popupTerminos.js"></script>
</body>

</html>