document.addEventListener("DOMContentLoaded", () => {
    const popup = document.getElementById("popup");
    const cerrar = document.getElementById("cerrar");
    const aceptar = document.getElementById("aceptar");

    if (!popup || !cerrar || !aceptar) return; // Evita errores si no existen en el DOM

    function cerrarPopup() {
        popup.style.display = "none";
        // Redirigir a la página de reservas
        window.location.href = "/views/frontend/reserva.php";
    }

    cerrar.addEventListener("click", cerrarPopup);
    aceptar.addEventListener("click", cerrarPopup);
    window.addEventListener("click", (e) => {
        if (e.target === popup) cerrarPopup();
    });
});

