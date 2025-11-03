
const nombreSelect = document.getElementById('nombre_categoria');
const tipoSelect = document.getElementById('tipo_categoria');
const modalidadSelect = document.getElementById('modalidad_producto');

function filterOptions(selectElement, dataAttr, value) {
    for (let option of selectElement.options) {
        if (option.dataset[dataAttr] === value || option.dataset[dataAttr] === undefined) {
            option.style.display = '';
        } else {
            option.style.display = 'none';
        }
    }
    // Selecciona el primer visible
    selectElement.value = '';
    for (let option of selectElement.options) {
        if (option.style.display !== 'none') {
            selectElement.value = option.value;
            break;
        }
    }
}

// Cuando cambie el primer select
nombreSelect.addEventListener('change', function() {
    filterOptions(tipoSelect, 'nombre', this.value);
    // Después filtramos la modalidad según el tipo seleccionado
    filterOptions(modalidadSelect, 'nombre', this.value);
    filterOptions(modalidadSelect, 'tipo', tipoSelect.value);
});

// Cuando cambie el segundo select
tipoSelect.addEventListener('change', function() {
    filterOptions(modalidadSelect, 'tipo', this.value);
});

