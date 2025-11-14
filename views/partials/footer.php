<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <footer class="container_footer">RESTAURANTE XITO, S.L. &copy;. Todos los derechos reservados.</footer>

    <script>
        // Animar icono del carrito
function animarCarrito() {
    const icono = document.querySelector(".carrito i");
    if (!icono) return;

    icono.classList.add("animar");

    setTimeout(() => {
        icono.classList.remove("animar");
    }, 300);
}

    </script>
</body>

</html>