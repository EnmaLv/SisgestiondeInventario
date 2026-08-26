@php
    $beneficiosSeleccionados = isset($beca)
        ? $beca->beneficios->keyBy('id')
        : collect();
@endphp

<div class="row">
    <div class="col-md-8">
        <div class="form-group mb-3">
            <label class="font-weight-bold">Nombre de la beca</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
                <input type="text" name="nombre" class="form-control rd-filter-input"
                    value="{{ old('nombre', $beca->nombre ?? '') }}" placeholder="Ej: Beca comedor integral">
            </div>
            @error('nombre')
                <div class="text-danger mt-1"><b>{{ $message }}</b></div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <label class="font-weight-bold d-block">Estado</label>
        <div class="d-flex align-items-center mt-2">
            <div class="toggle-container">
                <input type="checkbox" id="activo" name="activo" value="1" class="toggle-checkbox"
                    {{ old('activo', $beca->activo ?? true) ? 'checked' : '' }}>
                <label for="activo" class="toggle-label">
                    <span class="toggle-inner"></span>
                    <span class="toggle-switch"></span>
                </label>
            </div>
            <span class="ml-2 text-muted">Activa</span>
        </div>
    </div>
</div>

<div class="form-group mb-3">
    <label class="font-weight-bold">Descripcion</label>
    <textarea name="descripcion" rows="3" class="form-control rd-filter-input" placeholder="Descripcion general"
        style="resize:none;">{{ old('descripcion', $beca->descripcion ?? '') }}</textarea>
</div>

<hr>

<div class="rd-card-header mb-3">
    <h3 class="rd-title-sm">Beneficios asignados</h3>
    <a href="{{ route('admin.becas.beneficios.create') }}" class="rd-btn rd-btn-default">

    </a>
</div>
<div class="table-responsive mb-4">
    <table class="rd-table">
        <thead>
            <tr>
                <th style="width:80px" class="text-center">Usar</th>
                <th>Beneficio</th>
                <th>Disponibilidad</th>
                <th>Observacion</th>
            </tr>
        </thead>
        <tbody>
            @forelse($beneficios as $index => $beneficio)
                @php
                    $pivot = $beneficiosSeleccionados->get($beneficio->id)?->pivot;
                    $checked = old("beneficios.$index.id", $pivot ? $beneficio->id : null);
                @endphp
                <tr>
                    <td class="text-center">
                        <input type="checkbox" name="beneficios[{{ $index }}][id]" value="{{ $beneficio->id }}"
                            class="beneficio-check" {{ $checked ? 'checked' : '' }}>
                        <input type="hidden" name="beneficios[{{ $index }}][activo]" value="1">
                    </td>
                    <td>
                        <strong>{{ $beneficio->nombre_beneficio }}</strong>
                        <div class="text-muted small">{{ $beneficio->descripcion }}</div>
                    </td>
                    <td>
                        <span class="rd-badge rd-badge-success">
                            {{ max(($beneficio->cupones_disponibles ?? 0) - ($beneficio->cupones_ocupados ?? 0), 0) }}
                            disponibles
                        </span>
                    </td>
                    <td>
                        <input type="text" name="beneficios[{{ $index }}][observacion]"
                            class="form-control rd-filter-input"
                            value="{{ old("beneficios.$index.observacion", $pivot->observacion ?? '') }}"
                            placeholder="Detalle opcional">
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-4">No hay beneficios activos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.beneficio-check').forEach(function(input) {
                input.addEventListener('change', function() {
                    Swal.fire({
                        icon: 'info',
                        title: 'Beneficios actualizados',
                        text: 'Recuerde guardar la beca para confirmar este cambio.',
                        timer: 2200,
                        showConfirmButton: false
                    });
                });
            });
        });
    </script>
@endpush
