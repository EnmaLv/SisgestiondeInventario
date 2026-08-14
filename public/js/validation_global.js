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

    //Si no existen los campos, no hacer nada
    if (!desdeDate || !hastaDate) {
        return;
    }

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

document.addEventListener('DOMContentLoaded', function() {
    const archivoInput = document.getElementById('archivo');
    const nombreDisplay = document.getElementById('nombre-display');
    
    //Si no existen los campos, no hacer nada
    if (!archivoInput || !nombreDisplay) {
        return;
    }

    archivoInput.addEventListener('change', function(e) {
        let fileName = e.target.files[0].name;
        nombreDisplay.innerText = fileName;
    });
});

document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.rd-prevent-double-submit').forEach(form => {

        form.addEventListener('submit', function (event) {

            const hasEmptyQuestionFields = Array.from(form.querySelectorAll('.question-field')).some(input => !input.value || !input.value.trim());

            if (!form.checkValidity() || hasEmptyQuestionFields) {
                return;
            }

            const btn = form.querySelector('.rd-submit-btn');

            if (btn) {
                btn.disabled = true;
                btn.classList.add('disabled');
                btn.dataset.originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
            }

        }, { once: true });

    });

});
