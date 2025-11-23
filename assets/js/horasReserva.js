const fechaInput = document.getElementById('fecha');
const horaSelect = document.getElementById('hora_inicio');

// Guardamos todas las opciones por optgroup
const gruposOriginales = Array.from(horaSelect.querySelectorAll('optgroup')).map(optgroup => {
    return {
        label: optgroup.label,
        options: Array.from(optgroup.querySelectorAll('option'))
    };
});

function actualizarHoras() {
    const fechaSeleccionada = new Date(fechaInput.value);
    const fechaHoy = new Date();
    fechaSeleccionada.setHours(0,0,0,0);
    fechaHoy.setHours(0,0,0,0);

    // Limpiamos select
    horaSelect.innerHTML = '';

    gruposOriginales.forEach(grupo => {
        const nuevoOptgroup = document.createElement('optgroup');
        nuevoOptgroup.label = grupo.label;

        grupo.options.forEach(option => {
            let mostrar = true;

            if (fechaSeleccionada.getTime() === fechaHoy.getTime()) {
                const [h, m] = option.value.split(':').map(Number);
                const horaActual = new Date();
                if (h < horaActual.getHours() || (h === horaActual.getHours() && m <= horaActual.getMinutes())) {
                    mostrar = false;
                }
            }

            if (mostrar) {
                nuevoOptgroup.appendChild(option.cloneNode(true));
            }
        });

        // Solo agregamos el grupo si tiene opciones
        if (nuevoOptgroup.children.length > 0) {
            horaSelect.appendChild(nuevoOptgroup);
        }
    });
}

// Ejecutamos al cargar
actualizarHoras();

// Ejecutamos cada vez que cambia la fecha
fechaInput.addEventListener('change', actualizarHoras);
