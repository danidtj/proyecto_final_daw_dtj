document.addEventListener("DOMContentLoaded", function () {

    // Verificamos si el campo fecha_reserva existe antes de continuar
    const fecha_reserva = document.getElementById("fecha_reserva");
    if (!fecha_reserva) return; // No existe el campo, salimos para evitar errores

    // Limitar fecha a un mes desde hoy
    const hoy = new Date();
    const yyyy = hoy.getFullYear();
    const mm = String(hoy.getMonth() + 1).padStart(2, "0");
    const dd = String(hoy.getDate()).padStart(2, "0");
    const fechaMin = `${yyyy}-${mm}-${dd}`;

    const fechaMaxDate = new Date(hoy);
    fechaMaxDate.setMonth(fechaMaxDate.getMonth() + 1);
    const yyyyMax = fechaMaxDate.getFullYear();
    const mmMax = String(fechaMaxDate.getMonth() + 1).padStart(2, "0");
    const ddMax = String(fechaMaxDate.getDate()).padStart(2, "0");
    const fechaMax = `${yyyyMax}-${mmMax}-${ddMax}`;

    fecha_reserva.min = fechaMin;
    fecha_reserva.max = fechaMax;



    // Variables que se usarán
    const boton_enviar = document.getElementsByClassName("btn-reservar")[0];
    const hora = document.getElementById("hora");
    const comensales = document.getElementById("comensales");
    const comandaRadios = document.getElementsByName("comanda");
    let errores = [];
    const formulario = document.forms[0];

    // Spans de error específicos para cada campo
    const spanFecha = document.getElementById("error-fecha");
    const spanHora = document.getElementById("error-hora");
    const spanComensales = document.getElementById("error-comensales");
    const spanComanda = document.getElementById("error-comanda");

    // Expresiones regulares utilizadas para validar los campos
    const regex_fecha_reserva = /^\d{4}-\d{2}-\d{2}$/;

    // Establecer la fecha actual de manera predeterminada en el input de fecha de la reserva
    (function setFechaHoy() {
        const fechaActual = new Date();
        const anioActual = fechaActual.getFullYear();
        const mesActual = String(fechaActual.getMonth() + 1).padStart(2, '0'); //Si el número del mes es inferior a dos cifras, añade un 0 delante
        const diaActual = String(fechaActual.getDate()).padStart(2, '0');
        fecha_reserva.value = `${anioActual}-${mesActual}-${diaActual}`;
    })();

    //Como es posible que el usuario rellene los campos correctamente tras haber salido el error, se elimina el mensaje al cumplir con el patrón de validación
    fecha_reserva.addEventListener("input", function () {
        if (fecha_reserva.value.match(regex_fecha_reserva) !== null) {
            spanFecha.textContent = "";
            fecha_reserva.classList.remove("error");
        }
        validarCampos();
    });

    hora.addEventListener("change", function () {
        if (hora.value !== "") {
            spanHora.textContent = "";
            hora.classList.remove("error");
        }
        validarCampos();
    });

    comensales.addEventListener("change", function () {
        if (comensales.value !== "") {
            spanComensales.textContent = "";
            comensales.classList.remove("error");
        }
        validarCampos();
    });

    for (let i = 0; i < comandaRadios.length; i++) {
        comandaRadios[i].addEventListener("change", function () {
            spanComanda.textContent = "";
            validarCampos();
        });
    }

    // Validar todos los campos antes de habilitar el botón
    function validarCampos() {
        const fechaValida = fecha_reserva.value.match(regex_fecha_reserva) !== null;
        const horaValida = hora.value !== "";
        const comensalesValidos = comensales.value >= 1 && comensales.value <= 10;

        let comandaSeleccionada = false;
        for (let i = 0; i < comandaRadios.length; i++) {
            if (comandaRadios[i].checked) {
                comandaSeleccionada = true;
                break;
            }
        }

        if (fechaValida && horaValida && comensalesValidos && comandaSeleccionada) {
            boton_enviar.disabled = false;
        } else {
            boton_enviar.disabled = true;
        }
    }

    // Inicialmente desactivamos el botón hasta que los campos sean válidos
    boton_enviar.disabled = true;

    // Acciones que ocurren cuando se hace click en el botón
    // Evento onclick
    boton_enviar.addEventListener("click", function (e) {
        // Evitamos el comportamiento normal del botón de tipo submit
        e.preventDefault();

        // Limpiamos el array de errores
        errores = [];

        // Eliminamos las clases error de todos los campos
        const clases = document.getElementsByClassName("error");
        for (let i = 0; i < clases.length; i++) {
            clases[i].classList.remove("error");
        }

        // Limpiamos todos los mensajes de error anteriores
        spanFecha.textContent = "";
        spanHora.textContent = "";
        spanComensales.textContent = "";
        spanComanda.textContent = "";

        // FECHA DE RESERVA
        // Comprobamos que se introduce una fecha
        if (fecha_reserva.value.length == 0) {
            errores.push("- Es necesario que introduzca la fecha de su reserva.");
            spanFecha.textContent = "Debe introducir una fecha.";
            fecha_reserva.classList.add("error");
        }
        // Comprobamos que cumple el formato establecido
        else if (fecha_reserva.value.match(regex_fecha_reserva) == null) {
            errores.push("- Formato válido de la fecha de reserva: dd/mm/aaaa.");
            spanFecha.textContent = "Formato válido: dd/mm/aaaa.";
            fecha_reserva.classList.add("error");
        }
        // Comprobamos que se haya introducido una fecha y que sea válida
        else if (fecha_reserva.value.length > 0) {
            comprobarFecha(fecha_reserva.value);
        }

        // Función para comprobar la fecha de reserva
        function comprobarFecha(fechaIntroducida) {
            const anioIntroducido = fechaIntroducida.substring(0, 4);
            const mesIntroducido = fechaIntroducida.substring(5, 7);
            const diaIntroducido = fechaIntroducida.substring(8, 10);

            if (diaIntroducido <= 31 && mesIntroducido <= 12 && anioIntroducido.length == 4) {
                // Crear un objeto Date si la fecha introducida cumple con días<=31, meses<=12 y la longitud del año sea igual a 4
                const fechaFinal = new Date(anioIntroducido, mesIntroducido - 1, diaIntroducido);
                const fechaActual = new Date();


                /*if (fechaFinal > fechaActual) {
                    errores.push("- La fecha introducida es posterior a la actual.");
                    spanFecha.textContent = "La fecha no puede ser posterior a hoy.";
                    fecha_reserva.classList.add("error");
                }*/
            }
        }

        // HORA
        if (hora.value === "") {
            errores.push("- Es necesario que seleccione una hora.");
            spanHora.textContent = "Debe seleccionar una hora.";
            hora.classList.add("error");
        }

        // COMENSALES
        if (comensales.value === "") {
            errores.push("- Es necesario que indique el número de comensales.");
            spanComensales.textContent = "Debe seleccionar el número de comensales.";
            comensales.classList.add("error");
        }

        // COMANDA
        let seleccionada = false;
        for (let i = 0; i < comandaRadios.length; i++) {
            if (comandaRadios[i].checked) {
                seleccionada = true;
                break;
            }
        }
        if (!seleccionada) {
            errores.push("- Es necesario que indique si desea realizar la comanda ahora.");
            spanComanda.textContent = "Seleccione una opción.";
        }

        // Si no hay errores, se envía el formulario
        if (errores.length == 0) {
            formulario.submit();
        }
    });

});
