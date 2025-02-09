// Validación de cédula ecuatoriana
function validarCedula(cedula) {
    if (!/^\d{10}$/.test(cedula)) return false;

    const provincia = parseInt(cedula.substring(0, 2));
    if (provincia < 1 || provincia > 24) return false;

    const coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
    let suma = 0;

    for (let i = 0; i < 9; i++) {
        let valor = parseInt(cedula[i]) * coeficientes[i];
        suma += valor > 9 ? valor - 9 : valor;
    }

    const digitoVerificador = suma % 10 ? 10 - (suma % 10) : 0;
    return parseInt(cedula[9]) === digitoVerificador;
}

// Función para mostrar errores de validación
function mostrarErrorCedula(input, mensaje) {
    const errorDiv = document.getElementById('cedulaError');
    input.classList.add('is-invalid');
    errorDiv.textContent = mensaje;
}

// Función para limpiar errores
function limpiarErrorCedula(input) {
    const errorDiv = document.getElementById('cedulaError');
    input.classList.remove('is-invalid');
    errorDiv.textContent = '';
} 