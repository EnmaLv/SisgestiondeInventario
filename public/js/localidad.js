/**
 * Validaciones en tiempo real para el módulo de Localidades
 */

console.log('Cargando validaciones para localidad.js');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM completamente cargado, inicializando validaciones...');
    // Verificar si el modal de crear está presente en la página
    const modalCrear = document.getElementById('modalCrear');
    console.log('Modal de crear:', modalCrear);
    let validacionesInicializadas = false;

    // Función para limpiar el formulario y errores
    function limpiarFormulario() {
        const form = document.getElementById('formCrearLocalidad');
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
            console.log('Modal mostrado, inicializando validaciones...');
            limpiarFormulario();
            if (!validacionesInicializadas) {
                inicializarValidacionesCrear();
                validacionesInicializadas = true;
            }
        });

        // Limpiar el formulario cuando se oculte el modal
        modalCrear.addEventListener('hidden.bs.modal', function() {
            console.log('Modal oculto, limpiando formulario...');
            limpiarFormulario();
        });
    }

    // Inicializar validaciones si el modal ya está abierto (en caso de recarga de página)
    if (modalCrear && modalCrear.classList.contains('show')) {
        console.log('Modal ya está abierto, inicializando validaciones...');
        inicializarValidacionesCrear();
        validacionesInicializadas = true;
    }
});

/**
 * Inicializa las validaciones para el formulario de creación
 */
function inicializarValidacionesCrear() {
    console.log('Inicializando validaciones de creación...');
    const form = document.getElementById('formCrearLocalidad');
    console.log('Formulario encontrado:', form);
    if (!form) {
        console.error('No se encontró el formulario con ID formCrearLocalidad');
        return;
    }

    // Elementos del formulario
    const estadoSelect = document.getElementById('estado_id');
    const municipioSelect = document.getElementById('municipio_id');
    const nombreInput = document.getElementById('nombre_localidad_crear');
    const contenedorAlerta = document.getElementById('contenedorAlertaCrear');

    console.log('Elementos del formulario:', {
        estadoSelect,
        municipioSelect,
        nombreInput,
        contenedorAlerta
    });

    // Función para mostrar mensajes de error
    function mostrarError(elemento, mensaje) {
        console.log(`Mostrando error para ${elemento.id}:`, mensaje);
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
        console.log(`Mostrando alerta (${tipo}):`, mensaje);
        if (!contenedorAlerta) {
            console.error('No se encontró el contenedor de alertas');
            return;
        }
        
        contenedorAlerta.innerHTML = `
            <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        `;
    }

    // Función para validar el formato del nombre de la localidad
    function validarFormatoNombre(valor) {
        return valor.trim().length > 0 && valor.trim().length <= 255;
    }

    // Función para verificar si la localidad ya existe
    async function verificarLocalidadExistente(nombre, municipioId) {
        try {
            const url = new URL('/admin/localidad/verificar', window.location.origin);
            url.searchParams.append('nombre', nombre);
            url.searchParams.append('municipio_id', municipioId);
            
            console.log('Solicitando URL:', url.toString());
            
            const response = await fetch(url.toString());
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                console.error('Error en la respuesta:', response.status, errorData);
                throw new Error(`Error HTTP: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Respuesta del servidor:', data);
            
            // Verificar si la respuesta es exitosa
            if (data.success === false) {
                console.error('Error del servidor:', data.message);
                return false; // En caso de error, asumimos que no existe para no bloquear al usuario
            }
            
            return data.existe;
        } catch (error) {
            console.error('Error al verificar la localidad:', error);
            return false; // En caso de error, asumimos que no existe para no bloquear al usuario
        }
    }

    // Validación en tiempo real para el campo de nombre
    if (nombreInput && municipioSelect) {
        let timeoutId;
        let haHechoBlur = false;
        let ultimoMunicipioId = municipioSelect.value;

        // Validar formato mientras se escribe
        nombreInput.addEventListener('input', function() {
            console.log('Evento input en nombre:', this.value);
            clearTimeout(timeoutId);
            const valor = this.value.trim();
            const municipioId = municipioSelect.value;
            
            // Solo validar formato mientras se escribe, no si está vacío
            if (valor) {
                if (!validarFormatoNombre(valor)) {
                    mostrarError(this, 'El nombre no puede tener más de 255 caracteres');
                } else {
                    mostrarError(this, '');
                    
                    // Verificar duplicados después de un tiempo si hay un municipio seleccionado
                    if (municipioId) {
                        timeoutId = setTimeout(async () => {
                            console.log('Verificando duplicados para:', valor, 'en municipio:', municipioId);
                            const existe = await verificarLocalidadExistente(valor, municipioId);
                            console.log('Resultado de verificación:', existe);
                            if (existe) {
                                mostrarError(this, 'Ya existe una localidad con este nombre en el municipio seleccionado');
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

        // Validar cuando cambia el municipio
        municipioSelect.addEventListener('change', function() {
            console.log('Cambio en municipio:', this.value);
            const nombreValor = nombreInput.value.trim();
            const municipioId = this.value;
            
            if (nombreValor && municipioId) {
                // Verificar duplicados cuando cambia el municipio
                verificarLocalidadExistente(nombreValor, municipioId).then(existe => {
                    console.log('Verificación después de cambiar municipio:', existe);
                    if (existe) {
                        mostrarError(nombreInput, 'Ya existe una localidad con este nombre en el municipio seleccionado');
                    } else if (haHechoBlur) {
                        mostrarError(nombreInput, '');
                    }
                });
            }
            
            // Validar campo obligatorio
            if (!municipioId) {
                mostrarError(this, 'Debe seleccionar un municipio');
            } else {
                mostrarError(this, '');
            }
            
            ultimoMunicipioId = municipioId;
        });

        // Validar campo obligatorio solo cuando se hace blur
        nombreInput.addEventListener('blur', function() {
            console.log('Evento blur en nombre:', this.value);
            haHechoBlur = true;
            const valor = this.value.trim();
            const municipioId = municipioSelect.value;
            
            if (!valor) {
                mostrarError(this, 'El nombre de la localidad es obligatorio');
            } else if (!validarFormatoNombre(valor)) {
                mostrarError(this, 'El nombre no puede tener más de 255 caracteres');
            } else if (municipioId) {
                // Verificar duplicados al salir del campo si hay un municipio seleccionado
                verificarLocalidadExistente(valor, municipioId).then(existe => {
                    console.log('Verificación después de blur:', existe);
                    if (existe) {
                        mostrarError(this, 'Ya existe una localidad con este nombre en el municipio seleccionado');
                    } else {
                        mostrarError(this, '');
                    }
                });
            }
        });
        
        // Validar campo de municipio al hacer blur
        municipioSelect.addEventListener('blur', function() {
            console.log('Evento blur en municipio:', this.value);
            if (!this.value) {
                mostrarError(this, 'Debe seleccionar un municipio');
            } else {
                mostrarError(this, '');
            }
        });
    }
    
    // Validar campo de estado al cambiar
    if (estadoSelect) {
        estadoSelect.addEventListener('change', function() {
            console.log('Cambio en estado:', this.value);
            if (!this.value) {
                mostrarError(this, 'Debe seleccionar un estado');
            } else {
                mostrarError(this, '');
            }
        });
        
        // Validar campo de estado al hacer blur
        estadoSelect.addEventListener('blur', function() {
            console.log('Evento blur en estado:', this.value);
            if (!this.value) {
                mostrarError(this, 'Debe seleccionar un estado');
            } else {
                mostrarError(this, '');
            }
        });
    }

    // Validación al enviar el formulario
    form.addEventListener('submit', async function(event) {
        console.log('Enviando formulario...');
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
        
        // Validar municipio
        if (municipioSelect) {
            if (!municipioSelect.value) {
                mostrarError(municipioSelect, 'Debe seleccionar un municipio');
                esValido = false;
            } else {
                mostrarError(municipioSelect, '');
            }
        }
        
        // Validar nombre
        if (nombreInput) {
            const valor = nombreInput.value.trim();
            if (!valor) {
                mostrarError(nombreInput, 'El nombre de la localidad es obligatorio');
                esValido = false;
            } else if (!validarFormatoNombre(valor)) {
                mostrarError(nombreInput, 'El nombre no puede tener más de 255 caracteres');
                esValido = false;
            } else if (municipioSelect && municipioSelect.value) {
                // Verificar si la localidad ya existe
                console.log('Verificando duplicados antes de enviar...');
                const existe = await verificarLocalidadExistente(valor, municipioSelect.value);
                console.log('Resultado de verificación antes de enviar:', existe);
                if (existe) {
                    mostrarError(nombreInput, 'Ya existe una localidad con este nombre en el municipio seleccionado');
                    esValido = false;
                }
            } else {
                mostrarError(nombreInput, '');
            }
        }
        
        // Si hay errores, mostrar mensaje y prevenir el envío
        if (!esValido) {
            console.log('Formulario no válido, mostrando alerta...');
            mostrarAlerta('Por favor, complete el formulario correctamente.');
            return false;
        }
        
        console.log('Formulario válido, enviando...');
        // Si todo está bien, enviar el formulario
        this.submit();
        return true;
    });

    console.log('Validaciones inicializadas correctamente');
}