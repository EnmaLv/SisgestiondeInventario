@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4"
        style="background:#ffffff;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb;">
        <h1 class="m-0" style="font-size:1.45rem;color:#0f172a;font-weight:700;">Crear Nueva Parada</h1>
        <p class="mt-1 mb-0" style="font-size:0.95rem;color:#475569;">Seleccione el punto exacto en el mapa para capturar las
            coordenadas.</p>
    </div>
@stop

@section('content')
    <div class="flex flex-wrap -mx-2">
        <div class="w-full md:w-5/12">
            <div class="rd-card p-4"
                style="background:#ffffff;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb;">
                <form id="formCrear" action="{{ route('admin.transporte.maestros.bus_paradas.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="rd-label">Nombre de la Parada</label>
                        <div class="flex items-stretch w-full mt-1">
                            <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-map-pin"></i></span>
                            <input type="text" name="nombre" id="crearNombre" value="{{ old('nombre') }}"
                                class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('nombre') is-invalid @enderror"
                                placeholder="Ej: Hiper Sol Acarigua" maxlength="100" required autofocus>
                        </div>
                        <div id="errorNombreUnico" class="text-danger mt-1" style="display:none;"></div>
                        @error('nombre')
                            <div class="text-danger font-weight-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="rd-label">Dirección descriptiva</label>
                        <div class="flex items-stretch w-full mt-1">
                            <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-map-marker-alt"></i></span>
                            <input type="text" name="direccion" value="{{ old('direccion') }}"
                                class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('direccion') is-invalid @enderror"
                                placeholder="Ej: Av. Circunvalación, frente al centro comercial">
                        </div>
                        @error('direccion')
                            <div class="text-danger font-weight-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex flex-wrap -mx-2">
                        <div class="w-1/2">
                            <div class="form-group">
                                <label class="rd-label">Latitud</label>
                                <input type="text" id="latInput" name="lat" value="{{ old('lat') }}"
                                    class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('lat') is-invalid @enderror" readonly
                                    placeholder="Haga clic en el mapa" required>
                                @error('lat')
                                    <div class="text-danger font-weight-bold mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="w-1/2">
                            <div class="form-group">
                                <label class="rd-label">Longitud</label>
                                <input type="text" id="lngInput" name="lng" value="{{ old('lng') }}"
                                    class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('lng') is-invalid @enderror" readonly
                                    placeholder="Haga clic en el mapa" required>
                                @error('lng')
                                    <div class="text-danger font-weight-bold mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between mt-4"
                        style="border-top:1px solid #e5e7eb; padding-top:20px;">
                        <a href="{{ route('admin.transporte.maestros.bus_paradas.index') }}"
                            class="rd-btn rd-btn-default">Cancelar</a>
                        <button type="submit" class="rd-btn rd-btn-primary"><i class="fas fa-check"></i> Registrar
                            Parada</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="w-full md:w-7/12 mb-3 mb-md-0">
            <div class="rd-card"
                style="border-radius:14px;overflow:hidden;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb;">
                <div id="map"
                    style="height:440px; border-radius:12px; border:2px solid #cbd5e1; box-shadow: inset 0 2px 4px rgba(0,0,0,0.06);">
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/diseño.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .leaflet-container {
            font-family: inherit;
        }
    </style>
@stop

@push('js')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        let map;
        let marker = null;
        const BASE_URL = '/admin/transporte/maestros/bus_paradas';

        delete L.Icon.Default.prototype._getIconUrl;
        L.Icon.Default.mergeOptions({
            iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
            shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
        });

        function initMap() {
            const centroDefecto = [9.5597, -69.2014];

            map = L.map('map').setView(centroDefecto, 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            map.on("click", (event) => {
                colocarMarcador(event.latlng);
            });

            const oldLat = "{{ old('lat') }}";
            const oldLng = "{{ old('lng') }}";

            if (oldLat && oldLng) {
                const posicionOld = L.latLng(parseFloat(oldLat), parseFloat(oldLng));
                map.setView(posicionOld, 15);
                colocarMarcador(posicionOld);
            }
        }

        function colocarMarcador(location) {
            if (marker) {
                marker.setLatLng(location);
            } else {
                marker = L.marker(location, {
                    draggable: true
                }).addTo(map);

                marker.on('dragend', function(e) {
                    actualizarInputs(marker.getLatLng());
                });
            }

            actualizarInputs(location);
        }

        function actualizarInputs(location) {
            document.getElementById("latInput").value = location.lat.toFixed(7);
            document.getElementById("lngInput").value = location.lng.toFixed(7);
        }

        let timerNombre = null;
        document.getElementById('crearNombre').addEventListener('input', function() {
            const errorDiv = document.getElementById('errorNombreUnico');
            errorDiv.style.display = 'none';
            const val = this.value.trim();
            if (!val) return;

            clearTimeout(timerNombre);
            timerNombre = setTimeout(() => {
                fetch(`${BASE_URL}/verificar-nombre?nombre=${encodeURIComponent(val)}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(res => {
                        if (res.existe) {
                            errorDiv.textContent = 'Ya existe una parada registrada con este nombre.';
                            errorDiv.style.display = 'block';
                        }
                    });
            }, 450);
        });

        document.addEventListener("DOMContentLoaded", function() {
            initMap();
        });
    </script>
@endpush
