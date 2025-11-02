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
                            prod.querySelector('.cantidad').textContent = respuesta.cantidadRestante;
                            prod.querySelector('.subtotal').textContent = " ........ Subtotal: $" + respuesta.subtotal;
                        } else {
                            // Si no quedan unidades, eliminamos todo el producto
                            prod.remove();
                        }
                    }

                    // Actualizamos el total del carrito en pantalla
                    let totalElem = document.getElementById('precioTotal');
                    if (totalElem) {
                        if (respuesta.total > 0) {
                            totalElem.textContent = "Precio total del carrito: $" + respuesta.total;
                        } else {
                            totalElem.textContent = "";
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
