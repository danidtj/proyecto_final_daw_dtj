document.addEventListener("DOMContentLoaded", () => {
    const banner = document.getElementById("banner-terminos");
    const aceptarBtn = document.getElementById("aceptar-banner");

    // Mostrar banner solo si no se ha aceptado antes
    if (!localStorage.getItem("terminosAceptados")) {
        banner.style.display = "block";
    }

    // Al aceptar, ocultar y guardar aceptación
    aceptarBtn.addEventListener("click", () => {
        localStorage.setItem("terminosAceptados", "true");
        banner.style.display = "none";
    });
});
