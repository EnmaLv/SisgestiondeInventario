//ARCHIVOS SCRIPT PARA VALIDAR INPUT DE MANERA GLOBAL


document.addEventListener('DOMContentLoaded', function() {


    //VALIDACION DE INPUT DE NUMERO(NO ACEPTA NUMEROS NEGATIVOS)
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value < 0) {
                this.value = 0;
            }
        });
    });

    //Validacion para los input de fechas Para que no se elijan fechas antes del "desde"
    const desdeDate = document.getElementById('fecha_desde');
    const hastaDate = document.getElementById('fecha_hasta');

    // Fecha actual (máximo permitido)
    const fechaActual = new Date().toISOString().split('T')[0];

    // Establecer máximo hoy para ambos campos
    if (desdeDate) desdeDate.max = fechaActual;
    if (hastaDate) hastaDate.max = fechaActual;


    // Cuando cambie "desde", ajustar el mínimo de "hasta"
    if (desdeDate && hastaDate) {
        desdeDate.addEventListener('change', function() {
            if (!desdeDate.value) {
                // Si se borra la fecha desde, quitamos la restricción mínima en hasta
                hastaDate.min = '';
                return;
            }

            // "hasta" no puede ser menor que "desde"
            hastaDate.min = desdeDate.value;

            if (hastaDate.value && hastaDate.value < desdeDate.value) {
                hastaDate.value = desdeDate.value;
            }
        });

        // Cuando cambie "hasta", validar contra "desde"
        hastaDate.addEventListener('change', function() {
            if (!hastaDate.value || !desdeDate.value) {
                return;
            }

            if (hastaDate.value < desdeDate.value) {
                // Si el usuario pone una fecha hasta menor, movemos "desde" a esa fecha
                desdeDate.value = hastaDate.value;
            }
        });
    }


});