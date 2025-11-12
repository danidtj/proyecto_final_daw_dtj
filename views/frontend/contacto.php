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
        <?php include_once __DIR__ . '/../partials/header.php'; ?>
        <main>
        <section class="container_form">
            <h2 class="titulo_form">Formulario de contacto</h2>
                <form action="/controllers/frontend/ContactoController.php" method="post" class="formulario">
                    <div><label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required></div>
                    <div><label for="email">Email de contacto:</label>
                    <input type="text" id="email" name="email" required></div>
                    <div><label for="mensaje">Escribe lo que desees:</label>
                    <textarea name="mensaje" id="mensaje"></textarea></div>
                    <div><input type="submit" class="btn_contacto" value="Enviar"></div>
                </form>            
        </section>
    </main>

        <?php include_once __DIR__ . '/../partials/footer.php'; ?>
    
</body>

</html>