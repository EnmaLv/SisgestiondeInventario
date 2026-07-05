<div class="row">
    {{-- MÓDULO TRANSPORTE --}}
    {{-- marcas --}}
    @if ($visibleModules['bus_marcas'] ?? false)
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <a href="{{ url('/admin/transporte/maestros/bus_marcas') }}" class="module-link">
                <div class="module-card-light">
                    <div class="module-icon">
                        <i class="fas fa-tag" style="font-size: 2.2rem; color: var(--color-primary);"></i>
                    </div>
                    <h5>Marcas</h5>
                    <p>{{ $total_bus_marcas }} registradas</p>
                </div>
            </a>
        </div>
    @endif
    {{-- modelos --}}
    @if ($visibleModules['bus_modelos'] ?? false)
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <a href="{{ url('/admin/transporte/maestros/bus_modelos') }}" class="module-link">
                <div class="module-card-light">
                    <div class="module-icon">
                        <i class="fas fa-car" style="font-size:2.2rem;color:var(--color-primary);"></i>
                    </div>
                    <h5>Modelos</h5>
                    <p>{{ $total_bus_modelos }} registrados</p>
                </div>
            </a>
        </div>
    @endif
    {{-- tipo_combustible --}}
    @if ($visibleModules['bus_tipo_combustibles'] ?? false)
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <a href="{{ url('/admin/transporte/maestros/bus_tipo_combustibles') }}" class="module-link">
                <div class="module-card-light">
                    <div class="module-icon">
                        <i class="fas fa-gas-pump" style="font-size:2.2rem;color:var(--color-primary);"></i>
                    </div>
                    <h5>Tipos de Combustible</h5>
                    <p>{{ $total_bus_tipo_combustibles }} registrados</p>
                </div>
            </a>
        </div>
    {{-- vehiculos --}}    
    @endif
        @if ($visibleModules['bus_vehiculos'] ?? false)
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <a href="{{ url('/admin/transporte/maestros/bus_vehiculos') }}" class="module-link">
                <div class="module-card-light">
                    <div class="module-icon">
                        <i class="fas fa-bus" style="font-size:2.2rem;color:var(--color-primary);"></i>
                    </div>
                    <h5>Vehículos</h5>
                    <p>{{ $total_bus_vehiculos }} registrados</p>
                </div>
            </a>
        </div>
    @endif
    {{-- rutas --}}
    @if ($visibleModules['bus_rutas'] ?? false)
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <a href="{{ url('/admin/transporte/maestros/bus_rutas') }}" class="module-link">
                <div class="module-card-light">
                    <div class="module-icon">
                        <i class="fas fa-route" style="font-size:2.2rem;color:var(--color-primary);"></i>
                    </div>
                    <h5>Rutas</h5>
                    <p>{{ $total_bus_rutas }} registradas</p>
                </div>
            </a>
        </div>
    @endif
    {{-- paradas --}}
    @if ($visibleModules['bus_paradas'] ?? false)
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <a href="{{ url('/admin/transporte/maestros/bus_paradas') }}" class="module-link">
                <div class="module-card-light">
                    <div class="module-icon">
                        <i class="fas fa-map-pin" style="font-size:2.2rem;color:var(--color-primary);"></i>
                    </div>
                    <h5>Paradas</h5>
                    <p>{{ $total_bus_paradas }} registradas</p>
                </div>
            </a>
        </div>
    @endif

</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="stats-summary"
            style="
            background: var(--color-bg-card);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid var(--color-border-soft);
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
        ">
            <h5 style="color: var(--color-text-main); font-weight: 700;">
                📊 Resumen de Transporte
            </h5>

            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="text-center">
                        <div style="font-size: 2rem; font-weight: 800; color: var(--color-primary);">
                            {{ $total_bus_marcas }}
                        </div>
                        <div style="color: var(--color-text-main); font-size: 0.9rem; opacity: 0.8;">
                            Marcas Registradas
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="text-center">
                        <div style="font-size: 2rem; font-weight: 800; color: var(--color-primary);">
                            {{ $total_bus_modelos }}
                        </div>
                        <div style="color: var(--color-text-main); font-size: 0.9rem; opacity: 0.8;">
                            Modelos Registrados
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="text-center">
                        <div style="font-size:2rem;font-weight:800;color:var(--color-primary);">
                            {{ $total_bus_vehiculos }}
                        </div>
                        <div style="color:var(--color-text-main);font-size:0.9rem;opacity:0.8;">
                            Vehículos Registrados
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>