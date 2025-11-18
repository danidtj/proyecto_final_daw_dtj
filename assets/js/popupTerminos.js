// Mostrar popup solo si no se ha aceptado antes
window.onload = function() {
    if (!sessionStorage.getItem('terminosAceptados')) {
        document.getElementById('popupTerminos').style.display = 'flex';
    }
}

// Cuando el usuario acepta
document.getElementById('aceptarTerminos').addEventListener('click', function() {
    sessionStorage.setItem('terminosAceptados', 'true');
    document.getElementById('popupTerminos').style.display = 'none';
});