document.addEventListener("DOMContentLoaded", function () {

    const celdas = document.querySelectorAll(".celda.mesa-seleccionada");
    const inputMesaId = document.getElementById("mesa_id");
    const botonReservar = document.getElementById("boton-confirmar-reserva");

    // Deshabilitar botón al cargar
    botonReservar.disabled = true;

    // Añadimos evento click a cada celda
    celdas.forEach(function (celda) {
        celda.addEventListener("click", function () {
            const yaSeleccionada = this.classList.contains("selected");

            if (yaSeleccionada) {
                // Deseleccionamos la mesa
                this.classList.remove("selected");
                this.querySelectorAll("span").forEach(function (span) {
                    span.style.backgroundColor = "";
                });
                inputMesaId.value = "";
                botonReservar.disabled = true;
            } else {
                // Seleccionamos mesa y deseleccionamos las demás
                celdas.forEach(function (c) {
                    c.classList.remove("selected");
                    c.querySelectorAll("span").forEach(function (span) {
                        span.style.backgroundColor = "";
                    });
                });

                this.classList.add("selected");
                this.querySelectorAll("span").forEach(function (span) {
                    span.style.backgroundColor = "yellow";
                });

                inputMesaId.value = this.id;
                botonReservar.disabled = false;
            }
        });
    });

    // Validación antes de enviar el formulario
    /*formulario.addEventListener("submit", function (e) {
        if (inputMesaId.value.length === 0) {
            e.preventDefault();
            alert("Por favor, selecciona una mesa antes de continuar.");
        }
    });*/

});
