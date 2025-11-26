document.addEventListener("DOMContentLoaded", function () {

    const formulario = document.querySelector(".formulario");

    // Elementos del formulario
    const nombre = document.getElementById("nombre_usuario");
    const apellidos = document.getElementById("apellidos_usuario");
    const email = document.getElementById("email_usuario");
    const password = document.getElementById("password_usuario");
    const telefono = document.getElementById("telefono_usuario");

    // Elementos de errores
    const spanNombre = document.getElementById("error-nombre");
    const spanApellidos = document.getElementById("error-apellidos");
    const spanEmail = document.getElementById("error-email");
    const spanPassword = document.getElementById("error-password");
    const spanTelefono = document.getElementById("error-telefono");

    // Expresiones regulares
    const regex_nombre_apellido = /^[A-Za-zÁÉÍÓÚáéíóúÑñ]{2,20}$/;
    const regex_email = /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    const regex_password = /^.{6,20}$/;
    const regex_telefono = /^[0-9]{9}$/;

    // Función de validación individual
    function validarCampo(campo) {
        switch(campo) {
            case nombre:
                if (!regex_nombre_apellido.test(nombre.value.trim())) {
                    spanNombre.textContent = "Nombre inválido (solo letras, 2-20 caracteres).";
                    nombre.classList.add("error");
                    return false;
                } else {
                    spanNombre.textContent = "";
                    nombre.classList.remove("error");
                    return true;
                }
            case apellidos:
                const partes = apellidos.value.trim().split(/\s+/);
                if (partes.length !== 2) {
                    spanApellidos.textContent = "Debe introducir exactamente dos apellidos.";
                    apellidos.classList.add("error");
                    return false;
                } else {
                    const [ap1, ap2] = partes;
                    if (!regex_nombre_apellido.test(ap1)) {
                        spanApellidos.textContent = "Primer apellido inválido.";
                        apellidos.classList.add("error");
                        return false;
                    } else if (!regex_nombre_apellido.test(ap2)) {
                        spanApellidos.textContent = "Segundo apellido inválido.";
                        apellidos.classList.add("error");
                        return false;
                    } else {
                        spanApellidos.textContent = "";
                        apellidos.classList.remove("error");
                        return true;
                    }
                }
            case email:
                if (!regex_email.test(email.value.trim())) {
                    spanEmail.textContent = "Formato de email inválido.";
                    email.classList.add("error");
                    return false;
                } else {
                    spanEmail.textContent = "";
                    email.classList.remove("error");
                    return true;
                }
            case password:
                if (!regex_password.test(password.value)) {
                    spanPassword.textContent = "La contraseña debe tener entre 6 y 20 caracteres.";
                    password.classList.add("error");
                    return false;
                } else {
                    spanPassword.textContent = "";
                    password.classList.remove("error");
                    return true;
                }
            case telefono:
                if (!regex_telefono.test(telefono.value.trim())) {
                    spanTelefono.textContent = "Debe introducir un número de 9 dígitos.";
                    telefono.classList.add("error");
                    return false;
                } else {
                    spanTelefono.textContent = "";
                    telefono.classList.remove("error");
                    return true;
                }
        }
    }

    // Validación en tiempo real
    [nombre, apellidos, email, password, telefono].forEach(campo => {
        campo.addEventListener("input", () => validarCampo(campo));
        campo.addEventListener("blur", () => validarCampo(campo));
    });

});
