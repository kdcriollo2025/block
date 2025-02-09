function validarCedula(cedula) {
    // Verificar longitud
    if (cedula.length !== 10) {
        return false;
    }

    // Verificar que todos sean números
    if (!/^\d+$/.test(cedula)) {
        return false;
    }

    // Algoritmo de validación
    const coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
    const provincia = parseInt(cedula.substring(0, 2));

    // Verificar código de provincia
    if (provincia < 1 || provincia > 24) {
        return false;
    }

    let suma = 0;
    let resultado;

    // Multiplicar dígitos por coeficientes
    for (let i = 0; i < 9; i++) {
        resultado = parseInt(cedula[i]) * coeficientes[i];
        if (resultado > 9) {
            resultado -= 9;
        }
        suma += resultado;
    }

    // Obtener decena superior y restar
    const decenaSuperior = Math.ceil(suma / 10) * 10;
    const digitoVerificador = decenaSuperior - suma;

    // Verificar dígito verificador
    if (digitoVerificador === 10) {
        return parseInt(cedula[9]) === 0;
    }
    
    return parseInt(cedula[9]) === digitoVerificador;
} 