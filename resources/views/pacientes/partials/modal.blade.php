<div id="patientModal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm p-4 items-center justify-center">
    <div style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
        class="w-full max-w-3xl overflow-hidden rounded-2xl border shadow-xl flex flex-col">
        <div class="flex items-start justify-between gap-4 border-b border-gray-100 dark:border-gray-800 px-6 sm:px-8 py-5">
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-indigo-600 dark:text-indigo-400 mb-1">
                    Perfil del paciente
                </label>
                <h2 id="patientModalName" class="text-2xl sm:text-3xl font-extrabold tracking-tight" style="color: var(--text-main);"></h2>
                <p id="patientModalSubtitle" class="mt-1 text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400"></p>
            </div>
            <button id="closePatientModal" type="button"
                class="w-9 h-9 inline-flex items-center justify-center rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400 transition-all active:scale-95"
                aria-label="Cerrar">
                <i class="fas fa-xmark text-sm"></i>
            </button>
        </div>
        <div id="patientModalContent" class="max-h-[70vh] overflow-y-auto space-y-8 p-6 sm:p-8">
            <section>
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-user-circle text-indigo-600 dark:text-indigo-400 text-sm"></i>
                    <h3 class="text-[10px] font-black uppercase tracking-wider text-gray-400">Información Personal</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);" class="rounded-xl border p-4 transition-all">
                        <p class="text-[10px] font-black uppercase tracking-wider text-gray-400 mb-1">Cédula</p>
                        <p id="patientModalCedula" class="text-sm font-medium" style="color: var(--text-main);"></p>
                    </div>
                    <div style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);" class="rounded-xl border p-4 transition-all">
                        <p class="text-[10px] font-black uppercase tracking-wider text-gray-400 mb-1">Género</p>
                        <p id="patientModalGenero" class="text-sm font-medium" style="color: var(--text-main);"></p>
                    </div>
                    <div style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);" class="rounded-xl border p-4 transition-all">
                        <p class="text-[10px] font-black uppercase tracking-wider text-gray-400 mb-1">Edad</p>
                        <p id="patientModalEdad" class="text-sm font-medium" style="color: var(--text-main);"></p>
                    </div>
                    <div style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);" class="rounded-xl border p-4 transition-all">
                        <p class="text-[10px] font-black uppercase tracking-wider text-gray-400 mb-1">Nacimiento</p>
                        <p id="patientModalNacimiento" class="text-sm font-medium" style="color: var(--text-main);"></p>
                    </div>
                    <div style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);" class="rounded-xl border p-4 transition-all">
                        <p class="text-[10px] font-black uppercase tracking-wider text-gray-400 mb-1">Email</p>
                        <p id="patientModalEmail" class="text-sm font-medium break-all" style="color: var(--text-main);"></p>
                    </div>
                    <div style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);" class="rounded-xl border p-4 transition-all">
                        <p class="text-[10px] font-black uppercase tracking-wider text-gray-400 mb-1">Teléfono</p>
                        <p id="patientModalPhone" class="text-sm font-medium" style="color: var(--text-main);"></p>
                    </div>
                </div>
            </section>

            <section id="patientModalAcademicSection" class="pt-6 border-t border-gray-100 dark:border-gray-800 hidden">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-graduation-cap text-amber-600 dark:text-amber-400 text-sm"></i>
                    <h3 class="text-[10px] font-black uppercase tracking-wider text-gray-400">Información Académica</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);" class="rounded-xl border p-4">
                        <p class="text-[10px] font-black uppercase tracking-wider text-gray-400 mb-1">Perfil</p>
                        <p id="patientModalAcademicProfile" class="text-sm font-medium" style="color: var(--text-main);"></p>
                    </div>
                    <div style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);" class="rounded-xl border p-4">
                        <p class="text-[10px] font-black uppercase tracking-wider text-gray-400 mb-1">PNF</p>
                        <p id="patientModalPNF" class="text-sm font-medium" style="color: var(--text-main);"></p>
                    </div>
                    <div style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);" class="rounded-xl border p-4">
                        <p class="text-[10px] font-black uppercase tracking-wider text-gray-400 mb-1">Semestre</p>
                        <p id="patientModalSemestre" class="text-sm font-medium" style="color: var(--text-main);"></p>
                    </div>
                    <div id="patientModalHorarioContainer" style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);" class="md:col-span-3 rounded-xl border p-6 text-center hidden flex flex-col items-center justify-center">
                        <p class="text-[10px] font-black uppercase tracking-wider text-gray-400 mb-3">Documento de Horario Disponible</p>
                        <a id="patientModalHorarioLink" href="#" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 text-xs font-bold text-gray-600 dark:text-gray-300 transition-all active:scale-95 shadow-sm">
                            <i class="fas fa-file-pdf text-red-500 text-xs"></i>
                            <span>Ver Horario</span>
                        </a>
                    </div>
                </div>
            </section>
        </div>
        <div class="border-t border-gray-100 dark:border-gray-800 px-6 sm:px-8 py-4 flex justify-between items-center text-xs text-gray-500 dark:text-gray-400">
            <span>Primera cita realizada: <span id="patientModalRegistered" class="font-bold" style="color: var(--text-main);"></span></span>
            <div class="flex gap-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold border border-emerald-500/20 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                    <i class="fas fa-circle-check text-[10px]"></i>
                    <span>Verificado</span>
                </span>
            </div>
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
                            nombre: payload.nombre,
                            email: payload.email,
                            telefono: payload.telefono,
                            registrado_en: payload.registrado_en,
                            typeLabel: 'Paciente registrado manualmente'
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