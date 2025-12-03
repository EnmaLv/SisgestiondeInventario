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



});