<div class="form-group mb-3">
    <label class="font-weight-bold">Nombre del beneficio</label>
    <div class="flex items-stretch w-full">
        <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-gift"></i></span>
        <input type="text" name="nombre_beneficio" class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input"
            value="{{ old('nombre_beneficio', $beneficio->nombre_beneficio ?? '') }}"
            placeholder="Ej: Ayuda economica">
    </div>
    @error('nombre_beneficio')
        <div class="text-danger mt-1"><b>{{ $message }}</b></div>
    @enderror
</div>

<div class="form-group mb-3">
    <label class="font-weight-bold">Descripcion</label>
    <textarea name="descripcion" rows="3" class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input" placeholder="Descripcion del beneficio"
        style="resize:none;">{{ old('descripcion', $beneficio->descripcion ?? '') }}</textarea>
</div>

<div class="form-group mb-3">
    <label class="font-weight-bold block">Estado</label>
    <div class="flex items-center mt-2">
        <div class="toggle-container">
            <input type="checkbox" id="status" name="status" value="1" class="toggle-checkbox"
                {{ old('status', $beneficio->status ?? true) ? 'checked' : '' }}>
            <label for="status" class="toggle-label">
                <span class="toggle-inner"></span>
                <span class="toggle-switch"></span>
            </label>
        </div>
        <span class="ml-2 text-muted">Activo</span>
    </div>
</div>
