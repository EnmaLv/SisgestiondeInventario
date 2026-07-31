<div class="row">
    @if ($visibleModules['envases_primarios'] ?? false)
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <a href="{{ url('/admin/salud/maestros/envases_primarios') }}" class="module-link">
                <div class="module-card-light">
                    <div class="module-icon">
                        <img src="{{ url('/img/edificio.webp') }}" alt="Envases">
                    </div>
                    <h5>Envases Primarios</h5>
                    <p>{{ $total_envases_primarios }} registrados</p>
                </div>
            </a>
        </div>
    @endif
</div>
