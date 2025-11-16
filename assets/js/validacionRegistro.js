document.addEventListener("DOMContentLoaded", function () {

    const formulario = document.querySelector(".formulario");

    //Identificación de elementos del formulario
    const nombre = document.getElementById("nombre_usuario");
    const apellidos = document.getElementById("apellidos_usuario");
    const email = document.getElementById("email_usuario");
    const password = document.getElementById("password_usuario");
    const telefono = document.getElementById("telefono_usuario");

    //Elementos de errores
    const spanNombre = document.getElementById("error-nombre");
    const spanApellidos = document.getElementById("error-apellidos");
    const spanEmail = document.getElementById("error-email");
    const spanPassword = document.getElementById("error-password");
    const spanTelefono = document.getElementById("error-telefono");

    //Expresiones regulares
    const regex_nombre_apellido = /^[A-Za-zÁÉÍÓÚáéíóúÑñ]{2,20}$/;
    const regex_email = /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    const regex_password = /^.{6,20}$/; // mínimo 6
    const regex_telefono = /^[0-9]{9}$/; // 9 dígitos

    //Función para limpiar errores anteriores
    function limpiarErrores() {
        document.querySelectorAll(".error").forEach(el => el.classList.remove("error"));
        spanNombre.textContent = "";
        spanApellidos.textContent = "";
        spanEmail.textContent = "";
        spanPassword.textContent = "";
        spanTelefono.textContent = "";
    }

    // Evento submit del formulario
    formulario.addEventListener("submit", function (e) {
        e.preventDefault(); // evita envío hasta validar
        limpiarErrores();
        let errores = [];

        //NOMBRE
        if (!regex_nombre_apellido.test(nombre.value.trim())) {
            spanNombre.textContent = "Nombre inválido (solo letras, 2-20 caracteres).";
            nombre.classList.add("error");
            errores.push("nombre");
        }

        //APELLIDOS
        const partes = apellidos.value.trim().split(/\s+/);

        if (partes.length !== 2) {
            spanApellidos.textContent = "Debe introducir exactamente dos apellidos.";
            apellidos.classList.add("error");
            errores.push("apellidos");
        } else {
            const [ap1, ap2] = partes;

            if (!regex_nombre_apellido.test(ap1)) {
                spanApellidos.textContent = "Primer apellido inválido.";
                apellidos.classList.add("error");
                errores.push("apellidos");
            } else if (!regex_nombre_apellido.test(ap2)) {
                spanApellidos.textContent = "Segundo apellido inválido.";
                apellidos.classList.add("error");
                errores.push("apellidos");
            }
        }

        //EMAIL
        if (!regex_email.test(email.value.trim())) {
            spanEmail.textContent = "Formato de email inválido.";
            email.classList.add("error");
            errores.push("email");
        }

        //CONTRASEÑA
        if (!regex_password.test(password.value)) {
            spanPassword.textContent = "La contraseña debe tener entre 6 y 20 caracteres.";
            password.classList.add("error");
            errores.push("password");
        }

        //TELÉFONO
        if (!regex_telefono.test(telefono.value.trim())) {
            spanTelefono.textContent = "Debe introducir un número de 9 dígitos.";
            telefono.classList.add("error");
            errores.push("telefono");
        }

        //FINAL
        if (errores.length === 0) {
            alert("El registro se completó correctamente.");
            formulario.submit();
            window.location.href = "/../views/frontend/index.php";

        }
    });

});
