/**
 * Validaciones en tiempo real para el módulo de Municipios
 */

document.addEventListener('DOMContentLoaded', function() {
    // Verificar si el modal de crear está presente en la página
    const modalCrear = document.getElementById('modalCrear');
    let validacionesInicializadas = false;

    // Función para limpiar el formulario y errores
    function limpiarFormulario() {
        const form = document.getElementById('formCrearMunicipio');
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
    const form = document.getElementById('formCrearMunicipio');
    if (!form) return;

    // Elementos del formulario
    const estadoSelect = document.getElementById('estado_id');
    const nombreInput = document.getElementById('nombre_municipio_crear');
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

    // Función para validar el formato del nombre del municipio
    function validarFormatoNombre(valor) {
        return valor.trim().length > 0 && valor.trim().length <= 255;
    }

    // Función para verificar si el municipio ya existe
    async function verificarMunicipioExistente(nombre, estadoId) {
        try {
            const url = new URL('/admin/municipio/verificar', window.location.origin);
            url.searchParams.append('nombre', nombre);
            url.searchParams.append('estado_id', estadoId);
            
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
            console.error('Error al verificar el municipio:', error);
            return false; // En caso de error, asumimos que no existe para no bloquear al usuario
        }
    }

    // Validación en tiempo real para el campo de nombre
    if (nombreInput && estadoSelect) {
        let timeoutId;
        let haHechoBlur = false;
        let ultimoEstadoId = estadoSelect.value;

        // Validar formato mientras se escribe
        nombreInput.addEventListener('input', function() {
            clearTimeout(timeoutId);
            const valor = this.value.trim();
            const estadoId = estadoSelect.value;
            
            // Solo validar formato mientras se escribe, no si está vacío
            if (valor) {
                if (!validarFormatoNombre(valor)) {
                    mostrarError(this, 'El nombre no puede tener más de 255 caracteres');
                } else {
                    mostrarError(this, '');
                    
                    // Verificar duplicados después de un tiempo si hay un estado seleccionado
                    if (estadoId) {
                        timeoutId = setTimeout(async () => {
                            const existe = await verificarMunicipioExistente(valor, estadoId);
                            if (existe) {
                                mostrarError(this, 'Ya existe un municipio con este nombre en el estado seleccionado');
                            } else if (haHechoBlur) {
                                mostrarError(this, '');
                            }
                        }, 500);
                    }
                }
            } else {
                mostrarError(this, '');
            }
        });

        // Validar cuando cambia el estado
        estadoSelect.addEventListener('change', function() {
            const nombreValor = nombreInput.value.trim();
            const estadoId = this.value;
            
            if (nombreValor && estadoId) {
                // Verificar duplicados cuando cambia el estado
                verificarMunicipioExistente(nombreValor, estadoId).then(existe => {
                    if (existe) {
                        mostrarError(nombreInput, 'Ya existe un municipio con este nombre en el estado seleccionado');
                    } else if (haHechoBlur) {
                        mostrarError(nombreInput, '');
                    }
                });
            }
            
            // Validar campo obligatorio
            if (!estadoId) {
                mostrarError(this, 'Debe seleccionar un estado');
            } else {
                mostrarError(this, '');
            }
            
            ultimoEstadoId = estadoId;
        });

        // Validar campo obligatorio solo cuando se hace blur
        nombreInput.addEventListener('blur', function() {
            haHechoBlur = true;
            const valor = this.value.trim();
            const estadoId = estadoSelect.value;
            
            if (!valor) {
                mostrarError(this, 'El nombre del municipio es obligatorio');
            } else if (!validarFormatoNombre(valor)) {
                mostrarError(this, 'El nombre no puede tener más de 255 caracteres');
            } else if (estadoId) {
                // Verificar duplicados al salir del campo si hay un estado seleccionado
                verificarMunicipioExistente(valor, estadoId).then(existe => {
                    if (existe) {
                        mostrarError(this, 'Ya existe un municipio con este nombre en el estado seleccionado');
                    } else {
                        mostrarError(this, '');
                    }
                });
            }
        });
        
        // Validar campo de estado al hacer blur
        estadoSelect.addEventListener('blur', function() {
            if (!this.value) {
                mostrarError(this, 'Debe seleccionar un estado');
            } else {
                mostrarError(this, '');
            }
        });
    }

    // Validación al enviar el formulario
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        let esValido = true;
        
        // Validar estado
        if (estadoSelect) {
            if (!estadoSelect.value) {
                mostrarError(estadoSelect, 'Debe seleccionar un estado');
                esValido = false;
            } else {
                mostrarError(estadoSelect, '');
            }
        }
        
        // Validar nombre
        if (nombreInput) {
            const valor = nombreInput.value.trim();
            if (!valor) {
                mostrarError(nombreInput, 'El nombre del municipio es obligatorio');
                esValido = false;
            } else if (!validarFormatoNombre(valor)) {
                mostrarError(nombreInput, 'El nombre no puede tener más de 255 caracteres');
                esValido = false;
            } else if (estadoSelect && estadoSelect.value) {
                // Verificar si el municipio ya existe
                const existe = await verificarMunicipioExistente(valor, estadoSelect.value);
                if (existe) {
                    mostrarError(nombreInput, 'Ya existe un municipio con este nombre en el estado seleccionado');
                    esValido = false;
                }
            } else {
                mostrarError(nombreInput, '');
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
