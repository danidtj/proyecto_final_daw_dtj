document.addEventListener("DOMContentLoaded", () => {
    const popup = document.getElementById("popup");
    const cerrar = document.getElementById("cerrar");
    const aceptar = document.getElementById("aceptar");

    if (!popup || !cerrar || !aceptar) return;

    function cerrarPopup() {
        popup.style.display = "none";
        window.location.href = "/proyecto_final_daw_dtj/views/frontend/reserva.php";
    }

    cerrar.addEventListener("click", cerrarPopup);
    aceptar.addEventListener("click", cerrarPopup);
    window.addEventListener("click", (e) => {
        if (e.target === popup) cerrarPopup();
    });
});
