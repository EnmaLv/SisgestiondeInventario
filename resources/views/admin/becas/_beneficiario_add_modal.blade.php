<div class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4" id="addBeneficiarioModal" tabindex="-1" role="dialog" aria-hidden="true">
    <style>
        .beneficiario-form-input,
        .beneficiario-form-textarea,
        .beneficiario-form-select,
        .beneficiario-edit-select {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
        }
        .beneficiario-form-textarea {
            min-height: 120px;
        }
        #addBeneficiarioModal .select2-container--default .select2-selection--single,
        #editBeneficiarioModal .select2-container--default .select2-selection--single {
            border: 1px solid #d1d5db !important;
            border-radius: 8px !important;
            min-height: calc(1.5em + 20px);
            padding: 10px !important;
            background-color: transparent !important;
            box-shadow: none !important;
        }
        #addBeneficiarioModal .select2-container--default .select2-selection__rendered,
        #editBeneficiarioModal .select2-container--default .select2-selection__rendered {
            line-height: 1.5;
            padding-left: 0;
            color: #374151;
        }
        #addBeneficiarioModal .select2-container--default .select2-selection__arrow,
        #editBeneficiarioModal .select2-container--default .select2-selection__arrow {
            top: 50%;
            transform: translateY(-50%);
        }
        #addBeneficiarioModal .select2-container--default .select2-dropdown,
        #editBeneficiarioModal .select2-container--default .select2-dropdown {
            border: 1px solid #d1d5db !important;
            border-radius: 8px;
        }
        #addBeneficiarioModal .select2-container--default .select2-selection--single .select2-selection__rendered,
        #editBeneficiarioModal .select2-container--default .select2-selection--single .select2-selection__rendered {
            background-color: transparent !important;
        }
    </style>
    <div class="relative w-full w-full max-w-4xl" role="document">
        <div class="relative w-full">
            <form action="{{ route('admin.becas.beneficiarios.store', $beca) }}" method="POST" class="rd-prevent-double-submit">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Beneficiario</h5>
                    <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100" data-modal-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="flex flex-wrap -mx-2">
                        <div class="w-full md:w-1/2">
                            <label>Estudiante</label>
                            <select name="persona_id" class="block w-full rounded-lg border px-3 py-2 text-sm beneficiario-form-select">
                                <option value="">Seleccione estudiante</option>
                                @foreach($estudiantes as $e)
                                    <option value="{{ $e->id_persona }}">{{ trim($e->nombre_persona . ' ' . $e->apellido_persona) }} - {{ $e->cedula_persona }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full md:w-1/2">
                            <label>Área</label>
                            <input type="text" name="area" class="block w-full rounded-lg border px-3 py-2 text-sm beneficiario-form-input" placeholder="Biblioteca, Laboratorio...">
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-2 mt-3">
                        <div class="w-full md:w-1/2">
                            <label>Horario</label>
                            <input type="text" name="horario" class="block w-full rounded-lg border px-3 py-2 text-sm beneficiario-form-input"
                                placeholder="Ej: Lunes a Viernes 08:00-12:00. Si no asiste miércoles, indique Lunes, Martes, Jueves y Viernes...">
                            <small class="form-text text-muted">Formato sugerido: de lunes a viernes con horas. Use días concretos si hay exclusiones.</small>
                        </div>
                        <div class="w-full md:w-1/2">
                            <label>Tutor</label>
                            <select name="tutor_id" class="block w-full rounded-lg border px-3 py-2 text-sm beneficiario-form-select">
                                <option value="">Seleccione tutor</option>
                                @foreach($tutores as $t)
                                    <option value="{{ $t->id_persona }}">{{ trim($t->nombre_persona . ' ' . $t->apellido_persona) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label>Observaciones</label>
                        <textarea name="observaciones" class="block w-full rounded-lg border px-3 py-2 text-sm beneficiario-form-textarea" rows="3"></textarea>
                    </div>

                    <input type="hidden" name="activo" value="1">
                    <input type="hidden" name="estado" value="activo">
                </div>
                <div class="modal-footer">
                    <button type="button" class="rd-btn rd-btn-default" data-modal-dismiss="modal">Cancelar</button>
                    <button type="submit" class="rd-btn rd-btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
