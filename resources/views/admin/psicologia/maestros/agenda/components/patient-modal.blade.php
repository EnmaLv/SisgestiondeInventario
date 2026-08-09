<!-- MODAL DE PERFIL DE PACIENTE -->
<div id="patientModal" class="hidden fixed inset-0 z-[60] bg-slate-950/60 backdrop-blur-sm p-4 items-center justify-center transition-opacity">
    <div class="w-full max-w-3xl overflow-hidden rounded-3xl bg-white dark:bg-slate-900 shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all">
        
        <!-- Header con degradado sutil -->
        <div class="flex items-start justify-between gap-4 border-b border-slate-100 dark:border-slate-800/80 px-6 py-5 bg-slate-50/50 dark:bg-slate-900/50">
            <div class="space-y-1">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold tracking-wider uppercase bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 border border-sky-100 dark:border-sky-900/50">
                    Perfil del paciente
                </span>
                <h2 id="patientModalName" class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight"></h2>
                <p id="patientModalSubtitle" class="text-xs font-medium text-slate-500 dark:text-slate-400 flex items-center gap-1.5"></p>
            </div>
            <button id="closePatientModal" type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95" aria-label="Cerrar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Contenido principal -->
        <div id="patientModalContent" class="max-h-[70vh] overflow-y-auto space-y-8 p-6 md:p-8 custom-scrollbar">
            
            <!-- Sección: Información Personal -->
            <section>
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 bg-sky-50 dark:bg-sky-950/60 border border-sky-200/60 dark:border-sky-900/50 rounded-xl flex items-center justify-center text-sky-600 dark:text-sky-400 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-200">Información Personal</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5">
                    <div class="rounded-2xl border border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-800/40 p-3.5 transition-all hover:bg-white dark:hover:bg-slate-800 hover:border-slate-200 dark:hover:border-slate-700">
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Cédula</p>
                        <p id="patientModalCedula" class="text-sm text-slate-900 dark:text-slate-100 font-semibold"></p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-800/40 p-3.5 transition-all hover:bg-white dark:hover:bg-slate-800 hover:border-slate-200 dark:hover:border-slate-700">
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Género</p>
                        <p id="patientModalGenero" class="text-sm text-slate-900 dark:text-slate-100 font-semibold"></p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-800/40 p-3.5 transition-all hover:bg-white dark:hover:bg-slate-800 hover:border-slate-200 dark:hover:border-slate-700">
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Edad</p>
                        <p id="patientModalEdad" class="text-sm text-slate-900 dark:text-slate-100 font-semibold"></p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-800/40 p-3.5 transition-all hover:bg-white dark:hover:bg-slate-800 hover:border-slate-200 dark:hover:border-slate-700">
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Nacimiento</p>
                        <p id="patientModalNacimiento" class="text-sm text-slate-900 dark:text-slate-100 font-semibold"></p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-800/40 p-3.5 transition-all hover:bg-white dark:hover:bg-slate-800 hover:border-slate-200 dark:hover:border-slate-700">
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Estado Civil</p>
                        <p id="patientModalCivil" class="text-sm text-slate-900 dark:text-slate-100 font-semibold"></p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-800/40 p-3.5 transition-all hover:bg-white dark:hover:bg-slate-800 hover:border-slate-200 dark:hover:border-slate-700">
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Hijos</p>
                        <p id="patientModalHijos" class="text-sm text-slate-900 dark:text-slate-100 font-semibold"></p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-800/40 p-3.5 transition-all hover:bg-white dark:hover:bg-slate-800 hover:border-slate-200 dark:hover:border-slate-700">
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Discapacidad</p>
                        <p id="patientModalDiscapacidad" class="text-sm text-slate-900 dark:text-slate-100 font-semibold"></p>
                    </div>
                    <div class="md:col-span-2 rounded-2xl border border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-800/40 p-3.5 transition-all hover:bg-white dark:hover:bg-slate-800 hover:border-slate-200 dark:hover:border-slate-700">
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Ubicación</p>
                        <p id="patientModalUbicacion" class="text-sm text-slate-900 dark:text-slate-100 font-semibold truncate"></p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-800/40 p-3.5 transition-all hover:bg-white dark:hover:bg-slate-800 hover:border-slate-200 dark:hover:border-slate-700">
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Email</p>
                        <p id="patientModalEmail" class="text-sm text-slate-900 dark:text-slate-100 font-semibold break-all"></p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-800/40 p-3.5 transition-all hover:bg-white dark:hover:bg-slate-800 hover:border-slate-200 dark:hover:border-slate-700">
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Teléfono</p>
                        <p id="patientModalPhone" class="text-sm text-slate-900 dark:text-slate-100 font-semibold"></p>
                    </div>
                </div>
            </section>

            <!-- Sección: Información Académica -->
            <section id="patientModalAcademicSection" class="pt-6 border-t border-slate-100 dark:border-slate-800/80 hidden">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 bg-amber-50 dark:bg-amber-950/60 border border-amber-200/60 dark:border-amber-900/50 rounded-xl flex items-center justify-center text-amber-600 dark:text-amber-400 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-200">Información Académica</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5">
                    <div class="rounded-2xl border border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-800/40 p-3.5">
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Perfil</p>
                        <p id="patientModalAcademicProfile" class="text-sm text-slate-900 dark:text-slate-100 font-semibold"></p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-800/40 p-3.5">
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">PNF</p>
                        <p id="patientModalPNF" class="text-sm text-slate-900 dark:text-slate-100 font-semibold"></p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-800/40 p-3.5">
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Semestre</p>
                        <p id="patientModalSemestre" class="text-sm text-slate-900 dark:text-slate-100 font-semibold"></p>
                    </div>
                    
                    <div id="patientModalHorarioContainer" class="md:col-span-3 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/30 p-5 text-center hidden flex flex-col items-center justify-center space-y-2.5">
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Documento de Horario Disponible</p>
                        <a id="patientModalHorarioLink" href="#" target="_blank" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 dark:bg-slate-100 dark:hover:bg-white text-white dark:text-slate-900 font-semibold text-xs py-2.5 px-5 rounded-xl transition-all active:scale-95 shadow-sm">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                             Ver Horario
                        </a>
                    </div>
                </div>
            </section>
        </div>

        <!-- Footer -->
        <div class="border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/80 dark:bg-slate-900/80 px-6 py-3.5 flex justify-between items-center text-xs text-slate-500 dark:text-slate-400">
            <span>Primera cita: <span id="patientModalRegistered" class="font-medium text-slate-700 dark:text-slate-300"></span></span>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/50">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Verificado
            </span>
        </div>
    </div>
</div>

<script>
    (function() {
        var modal = document.getElementById('patientModal');
        var nameEl = document.getElementById('patientModalName');
        var subtitleEl = document.getElementById('patientModalSubtitle');
        var emailEl = document.getElementById('patientModalEmail');
        var phoneEl = document.getElementById('patientModalPhone');
        var registeredEl = document.getElementById('patientModalRegistered');

        var cedulaEl = document.getElementById('patientModalCedula');
        var edadEl = document.getElementById('patientModalEdad');
        var generoEl = document.getElementById('patientModalGenero');
        var nacimientoEl = document.getElementById('patientModalNacimiento');
        var civilEl = document.getElementById('patientModalCivil');
        var hijosEl = document.getElementById('patientModalHijos');
        var discapacidadEl = document.getElementById('patientModalDiscapacidad');
        var ubicacionEl = document.getElementById('patientModalUbicacion');

        var academicSection = document.getElementById('patientModalAcademicSection');
        var academicProfileEl = document.getElementById('patientModalAcademicProfile');
        var pnfEl = document.getElementById('patientModalPNF');
        var semestreEl = document.getElementById('patientModalSemestre');
        var horarioContainer = document.getElementById('patientModalHorarioContainer');
        var horarioLink = document.getElementById('patientModalHorarioLink');

        var closeBtn = document.getElementById('closePatientModal');

        function openModal() {
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeModal() {
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function setPatientData(data) {
            if (!data) return;
            nameEl.textContent = data.name || data.nombre || 'Paciente';
            subtitleEl.textContent = data.typeLabel || 'Paciente registrado';
            emailEl.textContent = data.email || 'No disponible';
            phoneEl.textContent = data.phone || data.telefono || 'No disponible';
            registeredEl.textContent = data.created_at || data.registrado_en || 'No disponible';

            cedulaEl.textContent = data.cedula || 'No disponible';
            edadEl.textContent = data.edad ? data.edad + ' años' : 'No disponible';
            generoEl.textContent = data.genero || 'No disponible';
            nacimientoEl.textContent = data.nacimiento || 'No disponible';
            civilEl.textContent = data.civil || 'No disponible';
            hijosEl.textContent = data.hijos || 'No disponible';
            discapacidadEl.textContent = data.discapacidad || 'Ninguna';
            ubicacionEl.textContent = data.ubicacion || 'No disponible';

            if (data.perfil_academico && data.perfil_academico !== 'Sin definir') {
                academicSection.classList.remove('hidden');
                academicProfileEl.textContent = data.perfil_academico;
                pnfEl.textContent = data.pnf || 'No aplica';
                semestreEl.textContent = data.semestre || 'No aplica';

                if (data.horario) {
                    horarioContainer.classList.remove('hidden');
                    horarioLink.href = data.horario;
                } else {
                    horarioContainer.classList.add('hidden');
                }
            } else {
                academicSection.classList.add('hidden');
            }

            openModal();
        }

        document.addEventListener('click', function(event) {
            var button = event.target.closest('.open-patient-modal');
            if (!button) return;
            event.preventDefault();

            var type = button.dataset.patientType;
            if (type === 'manual') {
                var url = button.dataset.patientJsonUrl;
                if (!url) return;
                fetch(url, { headers: { 'Accept': 'application/json' } })
                    .then(r => r.json())
                    .then(payload => {
                        setPatientData({
                            nombre: payload.paciente,
                            email: payload.email,
                            telefono: payload.telefono,
                            registrado_en: payload.registrado_en,
                            cedula: payload.cedula,
                            edad: payload.edad,
                            genero: payload.genero,
                            nacimiento: payload.nacimiento,
                            ubicacion: payload.ubicacion,
                            discapacidad: payload.discapacidad,
                            hijos: payload.hijos,
                            civil: payload.civil,
                            perfil_academico: payload.perfil_academico,
                            pnf: payload.pnf,
                            semestre: payload.semestre,
                            horario: payload.paciente_horario,
                            typeLabel: 'Paciente (Agenda)'
                        });
                    });
                return;
            }

            setPatientData({
                name: button.dataset.patientName,
                email: button.dataset.patientEmail,
                phone: button.dataset.patientPhone,
                created_at: button.dataset.patientCreated,
                cedula: button.dataset.patientCedula,
                edad: button.dataset.patientEdad,
                genero: button.dataset.patientGenero,
                nacimiento: button.dataset.patientNacimiento,
                ubicacion: button.dataset.patientUbicacion,
                discapacidad: button.dataset.patientDiscapacidad,
                hijos: button.dataset.patientHijos,
                civil: button.dataset.patientCivil,
                perfil_academico: button.dataset.patientPerfilAcademico,
                pnf: button.dataset.patientPnf,
                semestre: button.dataset.patientSemestre,
                horario: button.dataset.patientHorario,
                typeLabel: 'Paciente de cita'
            });
        });

        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        document.addEventListener('click', event => { if (event.target === modal) closeModal(); });
        document.addEventListener('keydown', event => { if (event.key === 'Escape') closeModal(); });
    })();
</script>


