<div class="modal fade" id="viewBeneficiarioModal-{{ $beneficiario->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ver Beneficiario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <label>Estudiante</label>
                        <p><strong>{{ $beneficiario->persona?->nombre_persona }} {{ $beneficiario->persona?->apellido_persona }} - {{ $beneficiario->persona?->cedula_persona }}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <label>Área</label>
                        <p>{{ $beneficiario->area }}</p>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label>Horario</label>
                        <p>{{ $beneficiario->horario }}</p>
                    </div>
                    <div class="col-md-6">
                        <label>Tutor</label>
                        <p>{{ $beneficiario->tutor?->nombre_persona }} {{ $beneficiario->tutor?->apellido_persona }}</p>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label>Observaciones</label>
                    <p>{{ $beneficiario->observaciones ?: '—' }}</p>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label>Estado</label>
                        <p>{{ ucfirst($beneficiario->estado) }}</p>
                    </div>
                    <div class="col-md-6">
                        <label>Motivo de suspensión</label>
                        <p>{{ $beneficiario->motivo_suspension ?: '—' }}</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="rd-btn rd-btn-default" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
