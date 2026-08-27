<div class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4" id="viewBeneficiarioModal-{{ $beneficiario->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="relative w-full w-full max-w-4xl" role="document">
        <div class="relative w-full">
            <div class="modal-header">
                <h5 class="modal-title">Ver Beneficiario</h5>
                <button type="button" class="btn-close" data-modal-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="flex flex-wrap -mx-2">
                    <div class="w-full md:w-1/2">
                        <label>Estudiante</label>
                        <p><strong>{{ $beneficiario->persona?->nombre_persona }} {{ $beneficiario->persona?->apellido_persona }} - {{ $beneficiario->persona?->cedula_persona }}</strong></p>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label>Área</label>
                        <p>{{ $beneficiario->area }}</p>
                    </div>
                </div>

                <div class="flex flex-wrap -mx-2 mt-3">
                    <div class="w-full md:w-1/2">
                        <label>Horario</label>
                        <p>{{ $beneficiario->horario }}</p>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label>Tutor</label>
                        <p>{{ $beneficiario->tutor?->nombre_persona }} {{ $beneficiario->tutor?->apellido_persona }}</p>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label>Observaciones</label>
                    <p>{{ $beneficiario->observaciones ?: '—' }}</p>
                </div>

                <div class="flex flex-wrap -mx-2 mt-3">
                    <div class="w-full md:w-1/2">
                        <label>Estado</label>
                        <p>{{ ucfirst($beneficiario->estado) }}</p>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label>Motivo de suspensión</label>
                        <p>{{ $beneficiario->motivo_suspension ?: '—' }}</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="rd-btn rd-btn-default" data-modal-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
