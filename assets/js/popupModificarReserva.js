document.addEventListener("DOMContentLoaded", () => {
    const popup = document.getElementById("popup-modificar-reserva");
    const cerrar = document.getElementById("cerrar-modificar-reserva");
    const aceptar = document.getElementById("aceptar-modificar-reserva");

    if (!popup || !cerrar || !aceptar) return; // Evita errores si no existen en el DOM

    function cerrarPopupReserva() {
        popup.style.display = "none";
        // Redirigir a la página de reservas
        window.location.href = "/proyecto_final_daw_dtj/views/frontend/reserva.php";
    }

    cerrar.addEventListener("click", cerrarPopupReserva);
    aceptar.addEventListener("click", cerrarPopupReserva);
    window.addEventListener("click", (e) => {
        if (e.target === popup) cerrarPopupReserva();
    });
});

