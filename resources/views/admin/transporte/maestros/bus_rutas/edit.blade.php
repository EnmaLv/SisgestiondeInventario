@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center"
        style="background:#ffffff;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb;">
        <div>
            <h1 class="m-0 rd-title-sm" style="font-size:1.4rem; color:#0f172a; font-weight:700;">Modificar Ruta</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem;color:#475569;">Editando la ruta:
                <strong>{{ $busRuta->nombre }}</strong>
            </p>
        </div>
        <a href="{{ route('admin.transporte.maestros.bus_rutas.index') }}" class="rd-btn rd-btn-default"><i
                class="fas fa-arrow-left"></i> Volver</a>
    </div>
@stop

@section('content')
    {{-- Captura y visualización de errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm" style="border-radius: 8px;">
            <b class="d-block mb-1"><i class="fas fa-exclamation-triangle mr-2"></i> Por favor verifique los siguientes
                errores:</b>
            <ul class="mb-0 pl-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($errors->has('error'))
        <div class="alert alert-danger"><b>{{ $errors->first('error') }}</b></div>
    @endif

    <form action="{{ route('admin.transporte.maestros.bus_rutas.update', $busRuta->id) }}" method="POST"
        class="rd-prevent-double-submit">
        @csrf
        @method('PUT')

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
                                placeholder="Ej: Zona Sur - Directo" value="{{ old('nombre', $busRuta->nombre) }}"
                                maxlength="100" required>
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
                                        value="{{ old('distancia_km', $busRuta->distancia_km) }}" min="0.1" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label class="rd-label">Sucursal Base</label>
                                <div class="rd-input-group">
                                    <span><i class="fas fa-building"></i></span>
                                    <select name="sucursal_id" class="form-control rd-input" required>
                                        <option value="">-- Seleccione --</option>
                                        @foreach ($sucursales as $sucursal)
                                            <option value="{{ $sucursal->id }}"
                                                {{ old('sucursal_id', $busRuta->sucursal_id) == $sucursal->id ? 'selected' : '' }}>
                                                {{ $sucursal->nombre }}
                                            </option>
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
                        <label class="rd-label">Descripción / Observaciones</label>
                        <div class="rd-input-group">
                            <span><i class="fas fa-sticky-note"></i></span>
                            <textarea name="descripcion" rows="2" class="form-control rd-input" style="resize:none; height: auto;">{{ old('descripcion', $busRuta->getRawOriginal('descripcion')) }}</textarea>
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
                        <small class="text-danger font-weight-bold">Haga clic en los marcadores del mapa para agregar o
                            alterar el orden.</small>
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
                </div>

                <div class="d-flex justify-content-end" style="gap:12px;">
                    <button type="submit" class="rd-btn rd-btn-primary rd-submit-btn"
                        style="color:white; width:200px;"><i class="fas fa-save mr-2"></i> Actualizar Ruta</button>
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

            const oldParadas = @json(old('paradas'));
            const paradasGuardadas = @json($busRuta->paradas);

            if (oldParadas && oldParadas.length > 0) {
                oldParadas.forEach(id => {
                    let pEncontrada = paradasDisponibles.find(x => x.id == id);
                    if (pEncontrada) secuenciaRuta.push(pEncontrada);
                });
            } else if (paradasGuardadas && paradasGuardadas.length > 0) {
                paradasGuardadas.forEach(p => {
                    let pEncontrada = paradasDisponibles.find(x => x.id == p.id);
                    if (pEncontrada) secuenciaRuta.push(pEncontrada);
                });
            }

            actualizarInterfazYPolilinea();
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
                if (marcadoresMap[p.id]) marcadoresMap[p.id].setIcon(createMarkerIcon('#64748b'));
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
                const response = await fetch(url);
                if (!response.ok) throw new Error('Respuesta errónea OSRM');

                const data = await response.json();
                if (data.code === 'Ok' && data.routes.length > 0) {
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
                }
            } catch (error) {
                console.error('OSRM Fallback en Edit:', error);
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

        const oldHorarios = @json(old('horarios'));
        const horariosGuardados = @json($busRuta->horarios);

        if (oldHorarios && Object.keys(oldHorarios).length > 0) {
            Object.values(oldHorarios).forEach(h => {
                agregarFilaHorario(h.hora_salida, h.tipo_viaje);
            });
        } else if (horariosGuardados && horariosGuardados.length > 0) {
            horariosGuardados.forEach(h => {
                let horaFormateada = h.hora_salida ? h.hora_salida.substring(0, 5) : '';
                agregarFilaHorario(horaFormateada, h.tipo_viaje);
            });
        } else {
            agregarFilaHorario();
        }
    </script>
@endpush
