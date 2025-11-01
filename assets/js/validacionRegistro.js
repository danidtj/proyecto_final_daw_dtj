document.addEventListener("DOMContentLoaded", function () {

// Variables que se usarán
const  boton_enviar = document.getElementsByClassName("btn")[0];
// Eliminamos el uso de div_errores
const  nombre = document.getElementById("nombre_usuario");
const  apellidos = document.getElementById("apellidos_usuario");
const  email = document.getElementById("email_usuario");
let errores = [];
const  formulario = document.forms[0];

// Spans de error específicos para cada campo
const  spanNombre = document.getElementById("error-nombre");
const  spanApellidos = document.getElementById("error-apellidos");
const  spanEmail = document.getElementById("error-email");

// Expresiones regulares utilizadas para validar los campos
const  regex_nombre = /^\D+[aeiouáéíóú]+[a-z]*$/i;
const  regex_apellidos = /^\D+[aeiouáéíóú]+[a-z]*\s?/i;
const  regex_email = /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/i;


//Como es posible que el usuario rellene los campos correctamente tras haber salido el error, se elimina el mensaje al cumplir con el patrón de validación
nombre.addEventListener("input", function () {
    if (
        nombre.value.length >= 2 &&
        nombre.value.length <= 20 &&
        nombre.value.match(regex_nombre) !== null
    ) {
        spanNombre.textContent = "";
        nombre.classList.remove("error");
    }
});


apellidos.addEventListener("input", function () {
    const  apellidosSinEspacios = apellidos.value.trim();
    const  espacio = " ";
    const  posicionEspacio = apellidosSinEspacios.indexOf(espacio);

    if (posicionEspacio > 0) {
        const  apellido1 = apellidosSinEspacios.substr(0, posicionEspacio).trim();
        const  apellido2 = apellidosSinEspacios.substr(posicionEspacio).trim();

        if (
            apellido1.length >= 2 && apellido1.length <= 20 &&
            apellido2.length >= 2 && apellido2.length <= 20 &&
            apellido1.match(regex_apellidos) !== null &&
            apellido2.match(regex_apellidos) !== null
        ) {
            spanApellidos.textContent = "";
            apellidos.classList.remove("error");
        }
    }
});

email.addEventListener("input", function () {
    if (email.value.match(regex_email) !== null) {
        spanEmail.textContent = "";
        email.classList.remove("error");
    }
});


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
    spanNombre.textContent = "";
    spanApellidos.textContent = "";
    //spanFecha.textContent = "";
    spanEmail.textContent = "";

    // NOMBRE
    // Comprobamos que se introduce un nombre
    if (nombre.value.length == 0) {
        errores.push("- Es necesario introducir un nombre.");
        spanNombre.textContent = "Es necesario introducir un nombre.";
        nombre.classList.add("error");
    }
    // Comprobamos que tiene una longitud mínima de 2 caracteres y máxima de 20
    else if (nombre.value.length < 2 || nombre.value.length > 20) {
        errores.push("- El nombre introducido no puede ser inferior a 2 caracteres ni superior a 20.");
        spanNombre.textContent = "El nombre debe tener entre 2 y 20 caracteres.";
        nombre.classList.add("error");
    }
    // Comprobamos que cumple con el formato exigido
    else if (nombre.value.match(regex_nombre) == null) {
        errores.push("- Formato válido de Nombre: mínimo una vocal y longitud entre 2 y 20 caracteres.");
        spanNombre.textContent = "Formato válido: mínimo una vocal y solo letras.";
        nombre.classList.add("error");
    }

    // APELLIDOS
    // Comprobamos que se introducen los apellidos
    if (apellidos.value.length == 0) {
        errores.push("- Es necesario que introduzca sus apellidos.");
        spanApellidos.textContent = "Es necesario que introduzca sus apellidos.";
        apellidos.classList.add("error");
    }
    // Comprobamos que la longitud sea superior a 2 e inferior a 20
    else if (apellidos.value.length != 0) {
        const apellidosSinEspacios = apellidos.value.trim();
        const espacio = " ";
        const posicionEspacio = apellidosSinEspacios.indexOf(espacio);
        if (posicionEspacio == -1) {
            errores.push("- Es necesario que introduzca sus dos apellidos.");
            spanApellidos.textContent = "Debe introducir dos apellidos separados por espacio.";
            apellidos.classList.add("error");
        } else {
            const apellido1 = apellidosSinEspacios.substr(0, posicionEspacio).trim(); // Extraemos el primer apellido
            const apellido2 = apellidosSinEspacios.substr(posicionEspacio).trim(); // Extraemos el segundo apellido
            if (apellido1.length < 2 || apellido1.length > 20) {
                errores.push("- Su primer apellido no cumple con una longitud mínima de 2 caracteres o máxima de 20.");
                spanApellidos.textContent = "Primer apellido inválido (mín. 2, máx. 20 caracteres).";
                apellidos.classList.add("error");
            } else if (apellido2.length < 2 || apellido2.length > 20) {
                errores.push("- Su segundo apellido no cumple con una longitud mínima de 2 caracteres o máxima de 20.");
                spanApellidos.textContent = "Segundo apellido inválido (mín. 2, máx. 20 caracteres).";
                apellidos.classList.add("error");
            } else {
                if (apellido1.match(regex_apellidos) == null) {
                    errores.push("- Su primer apellido no cumple con el formato: mínimo 1 vocal y longitud >2 y <20.");
                    spanApellidos.textContent = "Formato inválido en el primer apellido.";
                    apellidos.classList.add("error");
                } else if (apellido2.match(regex_apellidos) == null) {
                    errores.push("- Su segundo apellido no cumple con el formato: mínimo 1 vocal y longitud >2 y <20.");
                    spanApellidos.textContent = "Formato inválido en el segundo apellido.";
                    apellidos.classList.add("error");
                }
            }
        }
    }

    // EMAIL
    // Comprobamos que se introduce un email
    if (email.value.length == 0) {
        errores.push("- Es necesario que introduzca su email.");
        spanEmail.textContent = "Debe introducir su email.";
        email.classList.add("error");
    }
    // Comprobamos que cumple con los requerimientos exigidos
    else if (email.value.match(regex_email) == null) {
        errores.push("- Formato válido: dwec2@gmail.com");
        spanEmail.textContent = "Formato de email inválido.";
        email.classList.add("error");
    }

    // Comprobamos que la longitud del array errores sea 0
    // En caso contrario, existen errores, y no se envía el formulario
    if (errores.length == 0) {
        alert("El formulario ha sido enviado correctamente.");
        formulario.submit();
    }
});

});
