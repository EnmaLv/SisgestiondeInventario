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
    <div class="row">
        <div class="col-md-5">
            <div class="rd-card p-4"
                style="background:#ffffff;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb;">
                <form id="formCrear" action="{{ route('admin.transporte.maestros.bus_paradas.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="font-weight-bold">Nombre de la Parada</label>
                        <div class="input-group mt-1">
                            <span class="input-group-text"><i class="fas fa-map-pin"></i></span>
                            <input type="text" name="nombre" id="crearNombre" value="{{ old('nombre') }}"
                                class="form-control rd-filter-input @error('nombre') is-invalid @enderror"
                                placeholder="Ej: Hiper Sol Acarigua" maxlength="100" required autofocus>
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
                            <input type="text" name="direccion" value="{{ old('direccion') }}"
                                class="form-control rd-filter-input @error('direccion') is-invalid @enderror"
                                placeholder="Ej: Av. Circunvalación, frente al centro comercial">
                        </div>
                        @error('direccion')
                            <div class="text-danger font-weight-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Latitud</label>
                                <input type="text" id="latInput" name="lat" value="{{ old('lat') }}"
                                    class="form-control rd-filter-input @error('lat') is-invalid @enderror" readonly
                                    placeholder="Haga clic en el mapa" required>
                                @error('lat')
                                    <div class="text-danger font-weight-bold mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Longitud</label>
                                <input type="text" id="lngInput" name="lng" value="{{ old('lng') }}"
                                    class="form-control rd-filter-input @error('lng') is-invalid @enderror" readonly
                                    placeholder="Haga clic en el mapa" required>
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
                        <button type="submit" class="rd-btn rd-btn-primary"><i class="fas fa-check"></i> Registrar
                            Parada</button>
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
        let marker = null;
        const BASE_URL = '/admin/transporte/maestros/bus_paradas';

        function initMap() {
            const centroDefecto = {
                lat: 9.5597,
                lng: -69.2014
            };

            map = new google.maps.Map(document.getElementById("map"), {
                center: centroDefecto,
                zoom: 14,
                mapTypeControl: false,
                streetViewControl: false
            });

            map.addListener("click", (event) => {
                colocarMarcador(event.latLng);
            });
        }

        function colocarMarcador(location) {
            if (marker) {
                marker.setPosition(location);
            } else {
                marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    animation: google.maps.Animation.DROP,
                    draggable: true
                });

                marker.addListener('dragend', function(e) {
                    actualizarInputs(e.latLng);
                });
            }

            actualizarInputs(location);
        }

        function actualizarInputs(location) {
            document.getElementById("latInput").value = location.lat().toFixed(7);
            document.getElementById("lngInput").value = location.lng().toFixed(7);
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
    </script>
@endpush
