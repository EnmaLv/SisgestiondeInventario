@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4"
        style="background:#ffffff;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb;">
        <h1 class="m-0" style="font-size:1.45rem;color:#0f172a;font-weight:700;">Modificar Parada</h1>
        <p class="mt-1 mb-0" style="font-size:0.95rem;color:#475569;">Puede corregir la información o arrastrar el marcador en
            el mapa para actualizar la geolocalización.</p>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-5">
            <div class="rd-card p-4"
                style="background:#ffffff;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb;">
                <form id="formEditar" action="{{ route('admin.transporte.maestros.bus_paradas.update', $busParada) }}"
                    method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="font-weight-bold">Nombre de la Parada</label>
                        <div class="input-group mt-1">
                            <span class="input-group-text"><i class="fas fa-map-pin"></i></span>
                            <input type="text" name="nombre" id="editNombre"
                                value="{{ old('nombre', $busParada->nombre) }}"
                                class="form-control rd-filter-input @error('nombre') is-invalid @enderror" maxlength="100"
                                required>
                        </div>
                        <div id="errorNombreUnico" class="text-danger mt-1" style="display:none;"></div>
                        @error('nombre')
                            <div class="text-danger font-weight-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Dirección descriptiva</label>
                        <div class="input-group mt-1">
                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            <input type="text" name="direccion"
                                value="{{ old('direccion', $busParada->getRawOriginal('direccion')) }}"
                                class="form-control rd-filter-input @error('direccion') is-invalid @enderror">
                        </div>
                        @error('direccion')
                            <div class="text-danger font-weight-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Latitud</label>
                                <input type="text" id="latInput" name="lat"
                                    value="{{ old('lat', $busParada->lat) }}"
                                    class="form-control rd-filter-input @error('lat') is-invalid @enderror" readonly
                                    required>
                                @error('lat')
                                    <div class="text-danger font-weight-bold mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Longitud</label>
                                <input type="text" id="lngInput" name="lng"
                                    value="{{ old('lng', $busParada->lng) }}"
                                    class="form-control rd-filter-input @error('lng') is-invalid @enderror" readonly
                                    required>
                                @error('lng')
                                    <div class="text-danger font-weight-bold mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4"
                        style="border-top:1px solid #e5e7eb; padding-top:20px;">
                        <a href="{{ route('admin.transporte.maestros.bus_paradas.index') }}"
                            class="rd-btn rd-btn-default">Cancelar</a>
                        <button type="submit" class="rd-btn rd-btn-primary" style="color:white;"><i
                                class="fas fa-save"></i> Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-7">
            <div class="rd-card"
                style="border-radius:14px;overflow:hidden;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb; height: 500px;">
                <div id="map" style="width: 100%; height: 100%;"></div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/diseño.css') }}">
@stop

@push('js')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBe4i9pwERQV0ScgC7Gyto8c2NgqaFrUpM&callback=initMap" async
        defer></script>

    <script>
        let map;
        let marker;
        const BASE_URL = '/admin/transporte/maestros/bus_paradas';
        const paradaId = '{{ $busParada->id }}';

        function initMap() {
            // Coordenadas guardadas de la base de datos
            const coordenadaActual = {
                lat: parseFloat(document.getElementById("latInput").value),
                lng: parseFloat(document.getElementById("lngInput").value)
            };

            map = new google.maps.Map(document.getElementById("map"), {
                center: coordenadaActual,
                zoom: 16, // Zoom un poco más cercano por ser edición
                mapTypeControl: false,
                streetViewControl: false
            });

            // Creamos el marcador directo en la posición actual guardada
            marker = new google.maps.Marker({
                position: coordenadaActual,
                map: map,
                animation: google.maps.Animation.DROP,
                draggable: true
            });

            // Listener para cuando se arrastra el marcador existente
            marker.addListener('dragend', function(e) {
                actualizarInputs(e.latLng);
            });

            // Listener por si quieren hacer clic en un lugar totalmente diferente
            map.addListener("click", (event) => {
                marker.setPosition(event.latLng);
                actualizarInputs(event.latLng);
            });
        }

        function actualizarInputs(location) {
            document.getElementById("latInput").value = location.lat().toFixed(7);
            document.getElementById("lngInput").value = location.lng().toFixed(7);
        }

        // Validación de Nombre duplicado asíncrona excluyendo el ID actual
        let timerNombre = null;
        document.getElementById('editNombre').addEventListener('input', function() {
            const errorDiv = document.getElementById('errorNombreUnico');
            errorDiv.style.display = 'none';
            const val = this.value.trim();
            if (!val) return;

            clearTimeout(timerNombre);
            timerNombre = setTimeout(() => {
                fetch(`${BASE_URL}/verificar-nombre?nombre=${encodeURIComponent(val)}&exclude=${paradaId}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(res => {
                        if (res.existe) {
                            errorDiv.textContent = 'Ya existe otra parada registrada con este nombre.';
                            errorDiv.style.display = 'block';
                        }
                    });
            }, 450);
        });
    </script>
@endpush
