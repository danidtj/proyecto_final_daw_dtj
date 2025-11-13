function eliminar(codigo) {
    // Objeto para enviar y recibir datos del servidor sin recargar la página
    var xhttp = new XMLHttpRequest();

    // Define una función que se ejecutará automáticamente cada vez que haya una petición AJAX con diferente estado
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            try {

                //Almacena la respuesta del servidor que viene a través de JSON
                let respuesta = JSON.parse(this.responseText);

                // Confirmación de la eliminación del producto
                if (respuesta.status === "ok") {
                    let prod = document.getElementById('producto-' + codigo);

                    if (prod) {
                        // Si aún quedan unidades del producto
                        if (respuesta.cantidadRestante > 0) {
                            // Actualizamos la cantidad y subtotal 
                            let subtotalFormateado = parseFloat(respuesta.subtotal).toLocaleString('es-ES', { minimumFractionDigits: 2 });
                            prod.querySelector('.cantidad').textContent = respuesta.cantidadRestante;
                        } else {
                            // Si no quedan unidades, eliminamos todo el producto
                            prod.remove();
                        }
                    }

                    // Actualizamos el total del carrito y el pago por adelantado
                    let totalCarritoElem = document.getElementById('precioTotal');
                    if (totalCarritoElem) {
                        if (respuesta.total > 0) {
                            let totalFormateado = parseFloat(respuesta.total).toLocaleString('es-ES', { minimumFractionDigits: 2 });
                            totalCarritoElem.textContent = "Precio total del carrito: " + totalFormateado + " €";
                        } else {
                            totalCarritoElem.textContent = "";
                        }
                    }

                    let pagoAdelantadoElem = document.getElementById('pagoAdelantado');
                    if (pagoAdelantadoElem) {
                        if (respuesta.nuevoPagoAdelantado > 0) {
                            let pagoAdelantadoFormateado = parseFloat(respuesta.nuevoPagoAdelantado).toLocaleString('es-ES', { minimumFractionDigits: 2 });
                            pagoAdelantadoElem.textContent = "Precio a pagar por adelantado (10% del carrito): " + pagoAdelantadoFormateado + " €";
                        } else {
                            pagoAdelantadoElem.textContent = "";
                        }
                    }

                    // Ocultar sección de pago si el carrito queda vacío
                    let pagoSection = document.getElementById('pago-stripe');
                    if (respuesta.total <= 0) {
                        if (pagoSection) pagoSection.style.display = 'none';
                    } else {
                        if (pagoSection) pagoSection.style.display = 'block';
                    }

                    // Si ya no hay productos, mostramos el mensaje de carrito vacío
                    if (document.querySelectorAll("[id^='producto-']").length === 0) {
                        let carritoVacio = document.getElementById('carritoVacio');
                        if (carritoVacio) {
                            carritoVacio.style.display = "block";
                        } else {
                            // Crear mensaje si no existe
                            let cont = document.querySelector(".container_form");
                            if (cont) cont.innerHTML = `
                                <h2 class="titulo_form">CARRITO</h2>
                                <div id="carritoVacio">El carrito está vacío.</div>
                            `;
                        }
                    }

                }
            } catch (e) {
                console.error("Error al procesar respuesta:", e, this.responseText);
            }
        }
    };

    // Solicitud AJAX con método GET al endpoint específico
    xhttp.open("GET", "/views/frontend/ajaxCarrito.php?accion=eliminar&codigo=" + codigo, true);

    // Envía la solicitud al servidor
    xhttp.send();
}

//Vaciar carrito
var botonVaciar = document.getElementById("botonVaciarCarrito");

if (botonVaciar) {
    botonVaciar.addEventListener("click", function (e) {
        e.preventDefault(); // Evita que el formulario se envíe y recargue la página

        // Creamos el pop-up dinámicamente
        var popup = document.createElement("div");
        popup.classList.add("popup");

        popup.innerHTML = `
            <div class="popup-contenido">
                <span id="cerrar">&times;</span>
                <p>¿Seguro que quieres vaciar todo el carrito?</p>
                <button id="aceptar">Aceptar</button>
                <button id="cancelar">Cancelar</button>
            </div>
        `;

        document.body.appendChild(popup);

        // Función para cerrar el pop-up
        function cerrarPopup() {
            document.body.removeChild(popup);
        }

        // Evento para cerrar con la X
        popup.querySelector("#cerrar").addEventListener("click", cerrarPopup);

        // Evento para cancelar
        popup.querySelector("#cancelar").addEventListener("click", cerrarPopup);

        // Evento para aceptar (vaciar carrito)
        popup.querySelector("#aceptar").addEventListener("click", function () {
            cerrarPopup(); // primero cerramos el pop-up

            // Solicitud AJAX para vaciar el carrito
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    try {
                        let respuesta = JSON.parse(this.responseText);

                        if (respuesta.status === "ok") {
                            // Limpiamos visualmente el carrito
                            let cont = document.querySelector(".container_form");
                            if (cont) cont.innerHTML = `
                                <h2 class="titulo_form">CARRITO</h2>
                                <div id="carritoVacio">El carrito está vacío.</div>
                            `;

                            // Ocultamos la sección de pago
                            let pagoSection = document.getElementById('pago-stripe');
                            if (pagoSection) pagoSection.style.display = 'none';
                        }
                    } catch (e) {
                        console.error("Error al procesar la respuesta de vaciar carrito:", e, this.responseText);
                    }
                }
            };

            xhttp.open("GET", "/views/frontend/ajaxCarrito.php?accion=vaciar", true);
            xhttp.send();
        });
    });
}
