@extends('adminlte::page')

@section('content_header')
    <div class="flex flex-col md:flex-row justify-between md:items-center rd-card p-4 mb-3"
        style="background:#ffffff;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb;gap:15px;">
        <div>
            <div class="flex items-center" style="gap:10px;">
                <h1 class="m-0" style="font-size:1.45rem;color:#0f172a;font-weight:700;">
                    Monitoreo en Vivo: {{ $busViaje->ruta->nombre ?? 'Ruta N/A' }}
                </h1>
                @if ($busViaje->estado === 'programado')
                    <span class="rd-badge rd-badge-warning"><i class="fas fa-clock mr-1"></i> Programado</span>
                @elseif($busViaje->estado === 'en_curso')
                    <span class="rd-badge rd-badge-success pulse-animation"><i class="fas fa-broadcast-tower mr-1"></i> En
                        Ruta</span>
                @elseif($busViaje->estado === 'finalizado')
                    <span class="rd-badge rd-badge-secondary"><i class="fas fa-flag-checkered mr-1"></i> Finalizado</span>
                @else
                    <span class="rd-badge rd-badge-danger">{{ ucfirst($busViaje->estado) }}</span>
                @endif
            </div>
            <p class="mt-1 mb-0" style="font-size:0.9rem;color:#64748b;">
                ID Firebase: <code style="font-weight:600;color:#2563eb;">{{ $busViaje->firebase_id }}</code> |
                Turno: <strong class="text-capitalize">{{ $busViaje->turno }}</strong>
            </p>
        </div>
        <div class="flex" style="gap:10px;">
            <a href="{{ route('admin.transporte.maestros.bus_viajes.index') }}" class="rd-btn rd-btn-default">
                <i class="fas fa-arrow-left mr-1"></i> Volver a la Lista
            </a>
        </div>
    </div>
@stop

@section('content')
    @include('components.alert')

    <div class="flex flex-wrap -mx-2 mb-3">
        <div class="w-1/2 w-full md:w-1/4">
            <div class="rd-card p-3" style="background:#fff; border-radius:12px; border:1px solid #e5e7eb;">
                <span class="text-muted" style="font-size:0.8rem; font-weight:600;">PASAJEROS A BORDO</span>
                <div class="flex items-center justify-between mt-1">
                    <h3 class="m-0 font-weight-bold" style="color:#0f172a;" id="metricPasajeros">
                        {{ $busViaje->pasajeros ?? 0 }}
                    </h3>
                    <i class="fas fa-users text-primary" style="font-size:1.5rem; opacity:0.7;"></i>
                </div>
            </div>
        </div>
        <div class="w-1/2 w-full md:w-1/4">
            <div class="rd-card p-3" style="background:#fff; border-radius:12px; border:1px solid #e5e7eb;">
                <span class="text-muted" style="font-size:0.8rem; font-weight:600;">RECORRIDO ACTUAL</span>
                <div class="flex items-center justify-between mt-1">
                    <h3 class="m-0 font-weight-bold" style="color:#0f172a;" id="metricDistancia">
                        {{ number_format($busViaje->distancia_km ?? 0, 1) }} <small style="font-size:0.9rem;">km</small>
                    </h3>
                    <i class="fas fa-route text-info" style="font-size:1.5rem; opacity:0.7;"></i>
                </div>
            </div>
        </div>
        <div class="w-1/2 w-full md:w-1/4 mt-2 mt-md-0">
            <div class="rd-card p-3" style="background:#fff; border-radius:12px; border:1px solid #e5e7eb;">
                <span class="text-muted" style="font-size:0.8rem; font-weight:600;">ESTIMADO COMBUSTIBLE</span>
                <div class="flex items-center justify-between mt-1">
                    <h3 class="m-0 font-weight-bold" style="color:#0f172a;" id="metricLitros">
                        {{ number_format($busViaje->litros_gastados ?? 0, 2) }} <small style="font-size:0.9rem;">L</small>
                    </h3>
                    <i class="fas fa-gas-pump text-warning" style="font-size:1.5rem; opacity:0.7;"></i>
                </div>
            </div>
        </div>
        <div class="w-1/2 w-full md:w-1/4 mt-2 mt-md-0">
            <div class="rd-card p-3" style="background:#fff; border-radius:12px; border:1px solid #e5e7eb;">
                <span class="text-muted" style="font-size:0.8rem; font-weight:600;">ALERTA DESVÍO</span>
                <div class="flex items-center justify-between mt-1">
                    <h3 class="m-0 font-weight-bold" id="metricDesvio">
                        @if ($busViaje->hubo_desvio)
                            <span class="text-danger" style="font-size:1.1rem;"><i
                                    class="fas fa-exclamation-triangle mr-1"></i> Sí</span>
                        @else
                            <span class="text-success" style="font-size:1.1rem;"><i class="fas fa-check-circle mr-1"></i>
                                Normal</span>
                        @endif
                    </h3>
                    <i class="fas fa-map-signs text-secondary" style="font-size:1.5rem; opacity:0.7;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap -mx-2">
        <div class="w-full lg:w-2/3">
            <div class="rd-card mb-4"
                style="background:#fff; border-radius:14px; border:1px solid #e5e7eb; overflow:hidden;">
                <div class="p-3 flex justify-between items-center"
                    style="border-bottom:1px solid #f1f5f9; background:#f8fafc;">
                    <div class="flex items-center" style="gap:8px;">
                        <i class="fas fa-map-marked-alt text-primary"></i>
                        <span class="font-weight-bold" style="color:#1e293b; font-size:0.95rem;">Geolocalización GPS en
                            Tiempo Real</span>
                    </div>
                    <small id="lastUpdated" class="text-muted"><i class="fas fa-sync-alt fa-spin mr-1"></i> Esperando señal
                        GPS...</small>
                </div>

                <div id="mapaGPS" style="height: 520px; width: 100%; z-index:1;"></div>
            </div>
        </div>

        <div class="w-full lg:w-1/3">
            <div class="rd-card p-4 mb-3" style="background:#fff; border-radius:14px; border:1px solid #e5e7eb;">
                <h3 style="font-size:1rem; color:#0f172a; font-weight:700;" class="mb-3">
                    <i class="fas fa-user-shield text-primary mr-1"></i> Asignación de Unidad
                </h3>

                <div class="flex items-center mb-3 p-3"
                    style="background:#f8fafc; border-radius:10px; border:1px solid #e2e8f0; gap:12px;">
                    <div
                        style="width:48px; height:48px; border-radius:50%; background:#e2e8f0; display:flex; align-items:center; justify-content:center; color:#475569; font-weight:bold; font-size:1.2rem;">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold" style="color:#0f172a; font-size:0.95rem;">
                            {{ $busViaje->conductor->persona->nombre_persona ?? 'Sin Asignar' }}
                            {{ $busViaje->conductor->persona->apellido_persona ?? '' }}
                        </div>
                        <small class="text-muted">Chofer C.I: {{ $busViaje->conductor->persona->cedula ?? 'N/A' }}</small>
                    </div>
                </div>

                <div class="p-3" style="background:#f8fafc; border-radius:10px; border:1px solid #e2e8f0;">
                    <div class="flex justify-between mb-2">
                        <span class="text-muted" style="font-size:0.85rem;">Autobús:</span>
                        <strong
                            style="color:#0f172a; font-size:0.85rem;">{{ $busViaje->vehiculo->unidad ?? 'Unidad N/A' }}</strong>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-muted" style="font-size:0.85rem;">Placa:</span>
                        <span class="badge badge-light border"
                            style="font-size:0.85rem;">{{ $busViaje->vehiculo->placa ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted" style="font-size:0.85rem;">Combustible Base:</span>
                        <span
                            style="color:#0f172a; font-size:0.85rem; font-weight:600;">{{ $busViaje->vehiculo->tipoCombustible->nombre ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div class="rd-card p-4" style="background:#fff; border-radius:14px; border:1px solid #e5e7eb;">
                <div class="flex justify-between items-center mb-3">
                    <h3 style="font-size:1rem; color:#0f172a; font-weight:700;" class="m-0">
                        <i class="fas fa-map-pin text-primary mr-1"></i> Paradas de la Ruta
                    </h3>
                    <span class="badge badge-secondary" style="font-size:0.75rem;">
                        {{ $busViaje->ruta->paradas->count() }} Paradas
                    </span>
                </div>
                <div class="paradas-timeline" style="max-height: 280px; overflow-y: auto; padding-left: 5px;">
                    @forelse($busViaje->ruta->paradas as $index => $parada)
                        <div class="flex items-start mb-3" style="gap:12px; position:relative;">
                            <div
                                style="width:24px; height:24px; border-radius:50%; background:#eff6ff; border:2px solid #2563eb; color:#2563eb; font-size:0.75rem; font-weight:bold; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                {{ $index + 1 }}
                            </div>
                            <div style="flex-grow:1;">
                                <div class="font-weight-bold" style="font-size:0.88rem; color:#1e293b;">
                                    {{ $parada->nombre }}
                                </div>
                                @if ($parada->lat && $parada->lng)
                                    <small class="text-muted" style="font-size:0.75rem;">
                                        Lat: {{ number_format((float) $parada->lat, 4) }}, Lng:
                                        {{ number_format((float) $parada->lng, 4) }}
                                    </small>
                                @else
                                    <small class="text-danger font-weight-bold" style="font-size:0.72rem;">
                                        <i class="fas fa-exclamation-circle mr-1"></i> Sin Coordenadas
                                    </small>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center my-3" style="font-size:0.85rem;">No se registraron paradas
                            intermedias para esta ruta.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/diseño.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        .pulse-animation {
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            animation: pulse-green 1.8s infinite;
        }

        @keyframes pulse-green {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            }

            70% {
                transform: scale(1.03);
                box-shadow: 0 0 0 8px rgba(34, 197, 94, 0);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
            }
        }

        /* Estilo para los marcadores numerados de las paradas */
        .leaflet-stop-number {
            background-color: #2563eb;
            color: #ffffff;
            font-weight: bold;
            font-size: 11px;
            border-radius: 50%;
            width: 26px;
            height: 26px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        }
    </style>
@stop

@push('js')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viajeId = "{{ $busViaje->id }}";

            // Coordenadas iniciales por defecto
            let currentBusLat = 9.5468743;
            let currentBusLng = -69.1926348;

            const map = L.map('mapaGPS').setView([currentBusLat, currentBusLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Icono del autobús
            const busIcon = L.icon({
                iconUrl: 'https://cdn-icons-png.flaticon.com/512/3448/3448339.png',
                iconSize: [38, 38],
                iconAnchor: [19, 19],
                popupAnchor: [0, -19]
            });

            let busMarker = L.marker([currentBusLat, currentBusLng], { icon: busIcon })
                .addTo(map)
                .bindPopup(`<b>${"{{ $busViaje->vehiculo->placa ?? 'Unidad' }}"}</b><br>Esperando señal GPS...`);

            // Capa para la línea de aproximación por OSRM (Bus -> Parada 1)
            let approachPolyline = null;

            // Cargar paradas
            const paradasData = @json($busViaje->ruta->paradas ?? []);
            const routePoints = [];

            if (Array.isArray(paradasData) && paradasData.length > 0) {
                paradasData.forEach((parada, idx) => {
                    const lat = parseFloat(parada.lat);
                    const lng = parseFloat(parada.lng);

                    if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
                        const point = [lat, lng];
                        routePoints.push(point);

                        const stopNumberIcon = L.divIcon({
                            className: 'leaflet-stop-number-container',
                            html: `<div class="leaflet-stop-number">${idx + 1}</div>`,
                            iconSize: [26, 26],
                            iconAnchor: [13, 13]
                        });

                        L.marker(point, { icon: stopNumberIcon })
                            .addTo(map)
                            .bindPopup(`<b>Parada ${idx + 1}: ${parada.nombre}</b>`);
                    }
                });

                // Trazar ruta oficial entre paradas por calles (OSRM)
                if (routePoints.length >= 2) {
                    const osrmCoords = routePoints.map(p => `${p[1]},${p[0]}`).join(';');
                    const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${osrmCoords}?overview=full&geometries=geojson`;

                    fetch(osrmUrl)
                        .then(response => response.json())
                        .then(data => {
                            if (data.routes && data.routes.length > 0) {
                                const geometry = data.routes[0].geometry;
                                const latLngs = geometry.coordinates.map(c => [c[1], c[0]]);

                                const plannedPolyline = L.polyline(latLngs, {
                                    color: '#b91c1c', // Rojo de la ruta
                                    weight: 5,
                                    opacity: 0.8,
                                    lineJoin: 'round'
                                }).addTo(map);

                                map.fitBounds(plannedPolyline.getBounds(), { padding: [50, 50] });
                            }
                        })
                        .catch(err => console.error("Error al trazar ruta OSRM:", err));
                }

                // Trazar aproximación inicial por calles desde el bus a la primera parada
                trazarAproximacionParada(currentBusLat, currentBusLng);
            }

            // OSRM calcula el camino exacto por la calle desde el bus a Parada 1
            function trazarAproximacionParada(busLat, busLng) {
                if (routePoints.length === 0) return;

                const primeraParada = routePoints[0];
                const coordsOSRM = `${busLng},${busLat};${primeraParada[1]},${primeraParada[0]}`;
                const url = `https://router.project-osrm.org/route/v1/driving/${coordsOSRM}?overview=full&geometries=geojson`;

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        if (data.routes && data.routes.length > 0) {
                            const latLngs = data.routes[0].geometry.coordinates.map(c => [c[1], c[0]]);

                            if (approachPolyline) {
                                map.removeLayer(approachPolyline);
                            }

                            approachPolyline = L.polyline(latLngs, {
                                color: '#ef4444', // Rojo de aproximación
                                weight: 4,
                                opacity: 0.6,
                                lineJoin: 'round'
                            }).addTo(map);
                        }
                    })
                    .catch(err => console.error("Error trazando aproximación:", err));
            }

            function actualizarPosicionGPS(lat, lng, velocidad = 0, fechaRegistro = null) {
                const newLatLng = new L.LatLng(lat, lng);
                busMarker.setLatLng(newLatLng);

                // Re-calculamos el camino de aproximación OSRM solo si el bus cambia de lugar
                if (currentBusLat !== lat || currentBusLng !== lng) {
                    currentBusLat = lat;
                    currentBusLng = lng;
                    trazarAproximacionParada(lat, lng);
                }

                busMarker.getPopup().setContent(`
                    <div style="text-align:center;">
                        <strong style="color:#2563eb;">${"{{ $busViaje->vehiculo->placa ?? 'Autobús' }}"}</strong><br>
                        <span>Velocidad: <b>${parseFloat(velocidad).toFixed(1)} km/h</b></span>
                    </div>
                `);

                const statusElement = document.getElementById('lastUpdated');

                if (fechaRegistro) {
                    const ultimaTransmision = new Date(fechaRegistro);
                    const ahora = new Date();
                    const segundosDiferencia = Math.floor((ahora - ultimaTransmision) / 1000);

                    if (segundosDiferencia > 60) {
                        statusElement.innerHTML = `
                            <span class="text-danger font-weight-bold">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Sin señal GPS (Última: ${ultimaTransmision.toLocaleTimeString()})
                            </span>`;
                        return;
                    }
                }

                statusElement.innerHTML = `
                    <span class="text-success font-weight-bold">
                        <i class="fas fa-check-circle mr-1"></i> Transmitiendo en vivo (${new Date().toLocaleTimeString()})
                    </span>`;
            }

            function consultarGPS() {
                fetch(`/api/viajes/${viajeId}/posicion`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success && data.latitud && data.longitud) {
                            actualizarPosicionGPS(data.latitud, data.longitud, data.velocidad, data.fecha_registro);

                            if (data.pasajeros !== undefined) {
                                document.getElementById('metricPasajeros').innerText = data.pasajeros;
                            }
                            if (data.distancia_km !== undefined) {
                                document.getElementById('metricDistancia').innerHTML =
                                    `${data.distancia_km} <small style="font-size:0.9rem;">km</small>`;
                            }
                            if (data.litros_gastados !== undefined) {
                                document.getElementById('metricLitros').innerHTML =
                                    `${data.litros_gastados} <small style="font-size:0.9rem;">L</small>`;
                            }
                        }
                    })
                    .catch(err => console.error("Error al consultar GPS:", err));
            }

            setInterval(consultarGPS, 3000);
            consultarGPS();
        });
    </script>
@endpush