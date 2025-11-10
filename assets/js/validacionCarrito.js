


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

                    // Actualizamos el total del carrito en pantalla


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

                    // Si ya no hay productos, mostramos el mensaje de carrito vacío
                    if (document.querySelectorAll("[id^='producto-']").length === 0) {
                        document.getElementById('carritoVacio').style.display = "block";
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


