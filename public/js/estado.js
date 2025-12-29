/**
 * Validaciones en tiempo real para el módulo de Estados
 */

document.addEventListener('DOMContentLoaded', function() {
    // Verificar si el modal de crear está presente en la página
    const modalCrear = document.getElementById('modalCrear');
    let validacionesInicializadas = false;

    // Función para limpiar el formulario y errores
    function limpiarFormulario() {
        const form = document.getElementById('formCrearEstado');
        if (form) {
            // Limpiar errores
            const errores = form.querySelectorAll('.error-validacion, .is-invalid');
            errores.forEach(error => {
                if (error.classList.contains('error-validacion')) {
                    error.remove();
                } else {
                    error.classList.remove('is-invalid');
                }
            });
            // Limpiar alertas
            const contenedorAlerta = document.getElementById('contenedorAlertaCrear');
            if (contenedorAlerta) {
                contenedorAlerta.innerHTML = '';
            }
        }
    }

    if (modalCrear) {
        // Inicializar validaciones cuando el modal se muestra
        modalCrear.addEventListener('shown.bs.modal', function() {
            limpiarFormulario();
            if (!validacionesInicializadas) {
                inicializarValidacionesCrear();
                validacionesInicializadas = true;
            }
        });

        // Limpiar el formulario cuando se oculte el modal
        modalCrear.addEventListener('hidden.bs.modal', limpiarFormulario);
    }

    // Inicializar validaciones si el modal ya está abierto (en caso de recarga de página)
    if (modalCrear && modalCrear.classList.contains('show')) {
        inicializarValidacionesCrear();
        validacionesInicializadas = true;
    }
});

/**
 * Inicializa las validaciones para el formulario de creación
 */
function inicializarValidacionesCrear() {
    const form = document.getElementById('formCrearEstado');
    if (!form) return;

    // Elementos del formulario
    const nombreInput = document.getElementById('nombre_estado_crear');
    const contenedorAlerta = document.getElementById('contenedorAlertaCrear');

    // Función para mostrar mensajes de error
    function mostrarError(elemento, mensaje) {
        // Eliminar mensajes de error existentes
        const errorExistente = elemento.parentElement.querySelector('.error-validacion');
        if (errorExistente) {
            errorExistente.remove();
        }

        if (mensaje) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-validacion text-danger mt-1 small';
            errorDiv.textContent = mensaje;
            elemento.parentElement.appendChild(errorDiv);
            elemento.classList.add('is-invalid');
        } else {
            elemento.classList.remove('is-invalid');
        }
    }

    // Función para mostrar alerta general
    function mostrarAlerta(mensaje, tipo = 'danger') {
        if (!contenedorAlerta) return;
        
        contenedorAlerta.innerHTML = `
            <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        `;
    }

    // Función para validar el formato del nombre del estado
    function validarFormatoNombre(valor) {
        return valor.trim().length > 0 && valor.trim().length <= 255;
    }

    // Función para verificar si el estado ya existe
    async function verificarEstadoExistente(nombre) {
        try {
            const url = new URL('/admin/estado/verificar', window.location.origin);
            url.searchParams.append('nombre', nombre);
            
            console.log('Solicitando URL:', url.toString()); // Para depuración
            
            const response = await fetch(url.toString());
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                console.error('Error en la respuesta:', response.status, errorData);
                throw new Error(`Error HTTP: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Respuesta del servidor:', data); // Para depuración
            
            // Verificar si la respuesta es exitosa
            if (data.success === false) {
                console.error('Error del servidor:', data.message);
                return false; // En caso de error, asumimos que no existe para no bloquear al usuario
            }
            
            return data.existe;
        } catch (error) {
            console.error('Error al verificar el estado:', error);
            return false; // En caso de error, asumimos que no existe para no bloquear al usuario
        }
    }

    // Validación en tiempo real para el campo de nombre
    if (nombreInput) {
        let timeoutId;
        let haHechoBlur = false;

        // Validar formato mientras se escribe
        nombreInput.addEventListener('input', function() {
            clearTimeout(timeoutId);
            const valor = this.value.trim();
            
            // Solo validar formato mientras se escribe, no si está vacío
            if (valor) {
                if (!validarFormatoNombre(valor)) {
                    mostrarError(this, 'El nombre no puede tener más de 255 caracteres');
                } else {
                    mostrarError(this, '');
                }
            } else {
                mostrarError(this, '');
            }
            
            // Verificar duplicados después de un tiempo
            if (valor && validarFormatoNombre(valor)) {
                timeoutId = setTimeout(async () => {
                    const existe = await verificarEstadoExistente(valor);
                    if (existe) {
                        mostrarError(this, 'Ya existe un estado con este nombre');
                    } else if (haHechoBlur) {
                        mostrarError(this, '');
                    }
                }, 500);
            }
        });

        // Validar campo obligatorio solo cuando se hace blur
        nombreInput.addEventListener('blur', function() {
            haHechoBlur = true;
            const valor = this.value.trim();
            
            if (!valor) {
                mostrarError(this, 'El nombre del estado es obligatorio');
            } else if (!validarFormatoNombre(valor)) {
                mostrarError(this, 'El nombre no puede tener más de 255 caracteres');
            } else {
                // Verificar duplicados al salir del campo
                verificarEstadoExistente(valor).then(existe => {
                    if (existe) {
                        mostrarError(this, 'Ya existe un estado con este nombre');
                    } else {
                        mostrarError(this, '');
                    }
                });
            }
        });
    }

    // Validación al enviar el formulario
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        let esValido = true;
        
        // Validar nombre
        if (nombreInput) {
            const valor = nombreInput.value.trim();
            if (!valor) {
                mostrarError(nombreInput, 'El nombre del estado es obligatorio');
                esValido = false;
            } else if (!validarFormatoNombre(valor)) {
                mostrarError(nombreInput, 'El nombre no puede tener más de 255 caracteres');
                esValido = false;
            } else {
                // Verificar si el estado ya existe
                const existe = await verificarEstadoExistente(valor);
                if (existe) {
                    mostrarError(nombreInput, 'Ya existe un estado con este nombre');
                    esValido = false;
                }
            }
        }
        
        // Si hay errores, mostrar mensaje y prevenir el envío
        if (!esValido) {
            mostrarAlerta('Por favor, complete el formulario correctamente.');
            return false;
        }
        
        // Si todo está bien, enviar el formulario
        this.submit();
        return true;
    });
}
