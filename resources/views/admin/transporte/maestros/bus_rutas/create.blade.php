@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center"
        style="background:#ffffff;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb;">
        <div>
            <h1 class="m-0 rd-title-sm" style="font-size:1.4rem; color:#0f172a; font-weight:700;">Registrar Nueva Ruta</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem;color:#475569;">Bienvenido
                <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
            </p>
        </div>
        <a href="{{ route('admin.transporte.maestros.bus_rutas.index') }}" class="rd-btn rd-btn-default"><i
                class="fas fa-arrow-left"></i> Volver</a>
    </div>
@stop

@section('content')
    @if ($errors->has('error'))
        <div class="alert alert-danger"><b>{{ $errors->first('error') }}</b></div>
    @endif

    <form action="{{ route('admin.transporte.maestros.bus_rutas.store') }}" method="POST" class="rd-prevent-double-submit">
        @csrf
        <div id="hidden-paradas-inputs"></div>

        <div class="row">
            <div class="col-lg-5">
                <div class="rd-card p-4 mb-4"
                    style="background:#ffffff;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb;">
                    <h3 class="rd-title-sm mb-3" style="font-size:1.1rem;color:#0f172a;font-weight:700;">Datos Base</h3>

                    <div class="form-group mb-3">
                        <label class="rd-label">Nombre de la Ruta</label>
                        <div class="rd-input-group">
                            <span><i class="fas fa-route"></i></span>
                            <input type="text" name="nombre" id="inputNombre"
                                class="form-control rd-input @error('nombre') is-invalid @enderror"
                                placeholder="Ej: Zona Sur - Directo" value="{{ old('nombre') }}" maxlength="100" required>
                        </div>
                        <div id="errorNombreUnico" class="text-danger mt-1" style="display:none;"></div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label class="rd-label">Distancia (km)</label>
                                <div class="rd-input-group">
                                    <span><i class="fas fa-road"></i></span>
                                    <input type="number" name="distancia_km" id="inputDistancia" step="0.01"
                                        class="form-control rd-input" placeholder="Calculando..."
                                        value="{{ old('distancia_km') }}" min="0.1" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label class="rd-label">Sede</label>
                                <div class="rd-input-group">
                                    <span><i class="fas fa-building"></i></span>
                                    <select name="sucursal_id" class="form-control rd-input" required>
                                        <option value="">-- Seleccione --</option>
                                        @foreach ($sucursales as $sucursal)
                                            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rd-card p-4 mb-4"
                    style="background:#ffffff;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="m-0 font-weight-bold" style="font-size:1rem; color:#0f172a;"><i
                                class="fas fa-clock mr-2 text-primary"></i>Planificación Horarios</h4>
                        <button type="button" id="btn-add-horario" class="rd-btn rd-btn-success btn-sm"><i
                                class="fas fa-plus"></i></button>
                    </div>
                    <table class="table table-sm table-bordered">
                        <tbody id="contenedor-horarios"></tbody>
                    </table>
                </div>

                <div class="rd-card p-4 mb-4"
                    style="background:#ffffff;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb;">
                    <div class="form-group mb-0">
                        <label class="rd-label">Descripción</label>
                        <div class="rd-input-group">
                            <span><i class="fas fa-sticky-note"></i></span>
                            <input name="descripcion" rows="1" class="form-control rd-input"
                                placeholder="Horario Matutino">{{ old('descripcion') }}</input>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="rd-card p-4 mb-4"
                    style="background:#ffffff;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb;">
                    <div class="mb-3">
                        <h3 class="rd-title-sm m-0" style="font-size:1.1rem;color:#0f172a;font-weight:700;">Trazado e
                            Itinerario de Paradas</h3>
                        <small class="text-danger font-weight-bold">Haga clic en los marcadores del mapa para agregar
                            paradas en orden.</small>
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3 mb-md-0">
                            <div id="mapa-constructor"
                                style="height:440px; border-radius:12px; border:2px solid #cbd5e1; box-shadow: inset 0 2px 4px rgba(0,0,0,0.06);">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="rd-label" style="font-size: 0.85rem;"><i class="fas fa-list-ol mr-1"></i>
                                Secuencia (Arrastra para reordenar)</label>
                            <div id="lista-secuencia-paradas" class="list-group style-scroll"
                                style="max-height:400px; overflow-y:auto; border:1px solid #cbd5e1; border-radius:8px; background:#f8fafc; padding:6px; min-height: 60px;">
                            </div>
                        </div>
                    </div>
                    @error('paradas')
                        <div class="text-danger mt-2"><b>Debe añadir al menos 2 paradas al trazado en el mapa.</b></div>
                    @enderror
                    <div class="d-flex justify-content-end" style="gap:12px;">
                        <button type="submit" class="rd-btn rd-btn-primary rd-submit-btn"
                            style="color:white; width:200px;"><i class="fas fa-save mr-2"></i> Guardar Ruta</button>
                    </div>
                </div>


            </div>
        </div>
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/diseño.css') }}">
    <style>
        .parada-item {
            cursor: grab;
            padding: 8px 12px;
            margin-bottom: 6px;
            background: #fff;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.85rem;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .parada-item:active {
            cursor: grabbing;
            background: #f1f5f9;
        }

        .badge-orden {
            background: #3b82f6;
            color: #fff;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            margin-right: 6px;
        }
    </style>
@stop

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBe4i9pwERQV0ScgC7Gyto8c2NgqaFrUpM&callback=initMap" async
        defer></script>

    <script>
        const paradasDisponibles = @json($paradas);
        let secuenciaRuta = [];
        let map;
        let polyline;
        let marcadoresMap = {};

        function initMap() {
            map = new google.maps.Map(document.getElementById('mapa-constructor'), {
                center: {
                    lat: 9.56,
                    lng: -69.20
                },
                zoom: 13,
                mapTypeControl: false,
                streetViewControl: false,
                gestureHandling: 'greedy'
            });

            polyline = new google.maps.Polyline({
                strokeColor: '#B71C1C',
                strokeOpacity: 0.85,
                strokeWeight: 5,
                map: map
            });

            paradasDisponibles.forEach(parada => {
                if (!parada.lat || !parada.lng) return;

                let marker = new google.maps.Marker({
                    position: {
                        lat: parseFloat(parada.lat),
                        lng: parseFloat(parada.lng)
                    },
                    map: map,
                    title: parada.nombre,
                    icon: createMarkerIcon('#64748b')
                });

                let infowindow = new google.maps.InfoWindow({
                    content: `<strong>${parada.nombre}</strong>`
                });

                marker.addListener('mouseover', () => infowindow.open(map, marker));
                marker.addListener('mouseout', () => infowindow.close());

                marcadoresMap[parada.id] = marker;
                marker.addListener('click', () => {
                    agregarParadaASecuencia(parada);
                });
            });
        }

        function createMarkerIcon(color) {
            return {
                path: google.maps.SymbolPath.CIRCLE,
                fillColor: color,
                fillOpacity: 0.9,
                scale: 7,
                strokeColor: '#ffffff',
                strokeWeight: 2
            };
        }

        function agregarParadaASecuencia(parada) {
            secuenciaRuta.push(parada);
            actualizarInterfazYPolilinea();
        }

        async function actualizarInterfazYPolilinea() {
            const listaHTML = document.getElementById('lista-secuencia-paradas');
            const inputsHidden = document.getElementById('hidden-paradas-inputs');
            const inputDistancia = document.getElementById('inputDistancia');

            listaHTML.innerHTML = '';
            inputsHidden.innerHTML = '';

            paradasDisponibles.forEach(p => {
                if (marcadoresMap[p.id]) {
                    marcadoresMap[p.id].setIcon(createMarkerIcon('#64748b'));
                }
            });

            secuenciaRuta.forEach((parada, index) => {
                if (marcadoresMap[parada.id]) {
                    marcadoresMap[parada.id].setIcon(createMarkerIcon('#3b82f6'));
                }

                listaHTML.innerHTML += `
                    <div class="parada-item" data-id="${parada.id}">
                        <div>
                            <span class="badge-orden">${index + 1}</span>
                            <span>${parada.nombre}</span>
                        </div>
                        <button type="button" class="btn btn-xs text-danger" onclick="eliminarPuntoSecuencia(${index})"><i class="fas fa-times"></i></button>
                    </div>
                `;

                inputsHidden.innerHTML += `<input type="hidden" name="paradas[]" value="${parada.id}">`;
            });

            if (secuenciaRuta.length < 2) {
                polyline.setPath([]);
                if (inputDistancia) inputDistancia.value = '';
                return;
            }

            const coordenadasOSRM = secuenciaRuta.map(p => `${p.lng},${p.lat}`).join(';');
            const url =
                `https://router.project-osrm.org/route/v1/driving/${coordenadasOSRM}?overview=full&geometries=geojson`;

            try {
                if (inputDistancia) inputDistancia.placeholder = "Calculando...";

                const response = await fetch(url);
                if (!response.ok) throw new Error('Respuesta errónea del servidor OSRM');

                const data = await response.json();
                if (data.code !== 'Ok' || !data.routes || data.routes.length === 0) {
                    throw new Error('No se encontró una ruta viable por calles');
                }

                const rutaEncontrada = data.routes[0];

                const coordenadasLinea = rutaEncontrada.geometry.coordinates.map(coord => ({
                    lat: coord[1],
                    lng: coord[0]
                }));
                polyline.setPath(coordenadasLinea);

                const kilometrajeReal = (rutaEncontrada.distance / 1000).toFixed(2);
                if (inputDistancia) {
                    inputDistancia.value = kilometrajeReal;
                }

            } catch (error) {
                console.error('OSRM error en Web:', error);
                const coordenadasLineaFallback = secuenciaRuta.map(p => ({
                    lat: parseFloat(p.lat),
                    lng: parseFloat(p.lng)
                }));
                polyline.setPath(coordenadasLineaFallback);
            }
        }

        function eliminarPuntoSecuencia(index) {
            secuenciaRuta.splice(index, 1);
            actualizarInterfazYPolilinea();
        }

        const elLista = document.getElementById('lista-secuencia-paradas');
        Sortable.create(elLista, {
            animation: 150,
            onEnd: function() {
                let nuevoOrdenIds = Array.from(elLista.querySelectorAll('.parada-item')).map(item => item
                    .getAttribute('data-id'));

                let mapaTemporal = [];
                nuevoOrdenIds.forEach(id => {
                    let pEncontrada = secuenciaRuta.find(x => x.id == id);
                    if (pEncontrada) mapaTemporal.push(pEncontrada);
                });

                secuenciaRuta = mapaTemporal;
                actualizarInterfazYPolilinea();
            }
        });

        let indiceHorario = 0;

        function agregarFilaHorario(hora = '', tipo = 'entrada') {
            const fila = document.createElement('tr');
            fila.setAttribute('id', `fila-horario-${indiceHorario}`);
            fila.innerHTML = `
                <td><input type="time" name="horarios[${indiceHorario}][hora_salida]" value="${hora}" class="form-control form-control-sm" required></td>
                <td>
                    <select name="horarios[${indiceHorario}][tipo_viaje]" class="form-control form-control-sm" required>
                        <option value="entrada" ${tipo === 'entrada' ? 'selected' : ''}>Entrada ☀️</option>
                        <option value="salida" ${tipo === 'salida' ? 'selected' : ''}>Salida 🏠</option>
                    </select>
                </td>
                <td class="text-center"><button type="button" class="btn btn-xs btn-danger" onclick="document.getElementById('fila-horario-${indiceHorario}').remove()"><i class="fas fa-trash"></i></button></td>
            `;
            document.getElementById('contenedor-horarios').appendChild(fila);
            indiceHorario++;
        }
        document.getElementById('btn-add-horario').addEventListener('click', () => agregarFilaHorario());
        agregarFilaHorario();
    </script>
@endpush
