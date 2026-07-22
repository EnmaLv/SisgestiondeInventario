@extends('adminlte::page')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center rd-card p-4 mb-3"
        style="background:#ffffff;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb;gap:15px;">
        <div>
            <div class="d-flex align-items-center" style="gap:10px;">
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
        <div class="d-flex" style="gap:10px;">
            <a href="{{ route('admin.transporte.maestros.bus_viajes.index') }}" class="rd-btn rd-btn-default">
                <i class="fas fa-arrow-left mr-1"></i> Volver a la Lista
            </a>
        </div>
    </div>
@stop

@section('content')
    @include('components.alert')

    {{-- Métricas Telemáticas Rápidas --}}
    <div class="row mb-3">
        <div class="col-6 col-md-3">
            <div class="rd-card p-3" style="background:#fff; border-radius:12px; border:1px solid #e5e7eb;">
                <span class="text-muted" style="font-size:0.8rem; font-weight:600;">PASAJEROS A BORDOS</span>
                <div class="d-flex align-items-center justify-content-between mt-1">
                    <h3 class="m-0 font-weight-bold" style="color:#0f172a;" id="metricPasajeros">
                        {{ $busViaje->pasajeros ?? 0 }}
                    </h3>
                    <i class="fas fa-users text-primary" style="font-size:1.5rem; opacity:0.7;"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="rd-card p-3" style="background:#fff; border-radius:12px; border:1px solid #e5e7eb;">
                <span class="text-muted" style="font-size:0.8rem; font-weight:600;">RECORRIDO ACTUAL</span>
                <div class="d-flex align-items-center justify-content-between mt-1">
                    <h3 class="m-0 font-weight-bold" style="color:#0f172a;" id="metricDistancia">
                        {{ number_format($busViaje->distancia_km ?? 0, 1) }} <small style="font-size:0.9rem;">km</small>
                    </h3>
                    <i class="fas fa-route text-info" style="font-size:1.5rem; opacity:0.7;"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mt-2 mt-md-0">
            <div class="rd-card p-3" style="background:#fff; border-radius:12px; border:1px solid #e5e7eb;">
                <span class="text-muted" style="font-size:0.8rem; font-weight:600;">ESTIMADO COMBUSTIBLE</span>
                <div class="d-flex align-items-center justify-content-between mt-1">
                    <h3 class="m-0 font-weight-bold" style="color:#0f172a;" id="metricLitros">
                        {{ number_format($busViaje->litros_gastados ?? 0, 2) }} <small style="font-size:0.9rem;">L</small>
                    </h3>
                    <i class="fas fa-gas-pump text-warning" style="font-size:1.5rem; opacity:0.7;"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mt-2 mt-md-0">
            <div class="rd-card p-3" style="background:#fff; border-radius:12px; border:1px solid #e5e7eb;">
                <span class="text-muted" style="font-size:0.8rem; font-weight:600;">ALERTA DESVÍO</span>
                <div class="d-flex align-items-center justify-content-between mt-1">
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

    <div class="row">
        {{-- Mapa de Rastreamento en Vivo (8 Cols) --}}
        <div class="col-lg-8">
            <div class="rd-card mb-4"
                style="background:#fff; border-radius:14px; border:1px solid #e5e7eb; overflow:hidden;">
                <div class="p-3 d-flex justify-content-between align-items-center"
                    style="border-bottom:1px solid #f1f5f9; background:#f8fafc;">
                    <div class="d-flex align-items-center" style="gap:8px;">
                        <i class="fas fa-map-marked-alt text-primary"></i>
                        <span class="font-weight-bold" style="color:#1e293b; font-size:0.95rem;">Geolocalización GPS en
                            Tiempo Real</span>
                    </div>
                    <small id="lastUpdated" class="text-muted"><i class="fas fa-sync-alt fa-spin mr-1"></i> Esperando señal
                        GPS...</small>
                </div>

                {{-- Contenedor del Mapa --}}
                <div id="mapaGPS" style="height: 520px; width: 100%; z-index:1;"></div>
            </div>
        </div>

        {{-- Información Detallada del Chofer y Paradas (4 Cols) --}}
        <div class="col-lg-4">
            {{-- Tarjeta Conductor & Autobús --}}
            <div class="rd-card p-4 mb-3" style="background:#fff; border-radius:14px; border:1px solid #e5e7eb;">
                <h3 style="font-size:1rem; color:#0f172a; font-weight:700;" class="mb-3">
                    <i class="fas fa-user-shield text-primary mr-1"></i> Asignación de Unidad
                </h3>

                <div class="d-flex align-items-center mb-3 p-3"
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
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted" style="font-size:0.85rem;">Autobús:</span>
                        <strong
                            style="color:#0f172a; font-size:0.85rem;">{{ $busViaje->vehiculo->unidad ?? 'Unidad N/A' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted" style="font-size:0.85rem;">Placa:</span>
                        <span class="badge badge-light border"
                            style="font-size:0.85rem;">{{ $busViaje->vehiculo->placa ?? 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted" style="font-size:0.85rem;">Combustible Base:</span>
                        <span
                            style="color:#0f172a; font-size:0.85rem; font-weight:600;">{{ $busViaje->vehiculo->tipoCombustible->nombre ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            {{-- Tarjeta de Ruta y Paradas --}}
            <div class="rd-card p-4" style="background:#fff; border-radius:14px; border:1px solid #e5e7eb;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 style="font-size:1rem; color:#0f172a; font-weight:700;" class="m-0">
                        <i class="fas fa-map-pin text-primary mr-1"></i> Paradas de la Ruta
                    </h3>
                    <span class="badge badge-secondary" style="font-size:0.75rem;">
                        {{ $busViaje->ruta->paradas->count() }} Paradas
                    </span>
                </div>

                {{-- Timeline de Paradas --}}
                <div class="paradas-timeline" style="max-height: 280px; overflow-y: auto; padding-left: 5px;">
                    @forelse($busViaje->ruta->paradas as $index => $parada)
                        <div class="d-flex align-items-start mb-3" style="gap:12px; position:relative;">
                            <div
                                style="width:24px; height:24px; border-radius:50%; background:#eff6ff; border:2px solid #2563eb; color:#2563eb; font-size:0.75rem; font-weight:bold; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                {{ $index + 1 }}
                            </div>
                            <div style="flex-grow:1;">
                                <div class="font-weight-bold" style="font-size:0.88rem; color:#1e293b;">
                                    {{ $parada->nombre }}
                                </div>
                                @if ($parada->latitud && $parada->longitud)
                                    <small class="text-muted" style="font-size:0.75rem;">
                                        Lat: {{ number_format($parada->latitud, 4) }}, Lng:
                                        {{ number_format($parada->longitud, 4) }}
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
    {{-- Leaflet CSS --}}
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
    </style>
@stop

@push('js')
    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viajeId = "{{ $busViaje->id }}";

            // Coordenadas iniciales por defecto
            let defaultLat = 9.546987;
            let defaultLng = -69.192543;

            // 1. Inicializar Mapa con Leaflet
            const map = L.map('mapaGPS').setView([defaultLat, defaultLng], 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Iconos
            const busIcon = L.icon({
                iconUrl: 'https://cdn-icons-png.flaticon.com/512/3448/3448339.png',
                iconSize: [38, 38],
                iconAnchor: [19, 19],
                popupAnchor: [0, -19]
            });

            const stopIcon = L.icon({
                iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            });

            // Marcador del autobús
            let busMarker = L.marker([defaultLat, defaultLng], {
                    icon: busIcon
                })
                .addTo(map)
                .bindPopup(`<b>${"{{ $busViaje->vehiculo->placa ?? 'Unidad' }}"}</b><br>Esperando señal GPS...`);

            // 2. Renderizar Paradas de la Ruta
            const paradasData = @json($busViaje->ruta->paradas ?? []);
            const routePoints = [];

            if (paradasData.length > 0) {
                paradasData.forEach((parada, idx) => {
                    if (parada.latitud && parada.longitud) {
                        const point = [parseFloat(parada.latitud), parseFloat(parada.longitud)];
                        routePoints.push(point);

                        L.marker(point, {
                                icon: stopIcon
                            })
                            .addTo(map)
                            .bindPopup(`<b>Parada ${idx + 1}: ${parada.nombre}</b>`);
                    }
                });

                if (routePoints.length > 1) {
                    const polyline = L.polyline(routePoints, {
                        color: '#2563eb',
                        weight: 4,
                        opacity: 0.7,
                        dashArray: '8, 8'
                    }).addTo(map);
                    map.fitBounds(polyline.getBounds(), {
                        padding: [40, 40]
                    });
                }
            }

            // 3. Función para actualizar la UI con los datos recibidos por HTTP
            function actualizarPosicionGPS(lat, lng, velocidad = 0) {
                const newLatLng = new L.LatLng(lat, lng);
                busMarker.setLatLng(newLatLng);
                map.panTo(newLatLng);

                busMarker.getPopup().setContent(`
                    <div style="text-align:center;">
                        <strong style="color:#2563eb;">${"{{ $busViaje->vehiculo->placa ?? 'Autobús' }}"}</strong><br>
                        <span>Velocidad: <b>${parseFloat(velocidad).toFixed(1)} km/h</b></span>
                    </div>
                `);

                document.getElementById('lastUpdated').innerHTML =
                    `<i class="fas fa-check-circle text-success mr-1"></i> Transmitiendo en vivo (${new Date().toLocaleTimeString()})`;
            }

            // 4. Consulta Periódica por HTTP (Polling cada 3 segundos)
            function consultarGPS() {
                fetch(`/api/viajes/${viajeId}/posicion`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => {
                        console.log("Código HTTP recibido:", res.status); // <--- LOG AQUÍ
                        return res.json();
                    })
                    .then(data => {
                        console.log("Respuesta del servidor:", data);
                        if (data.success && data.latitud && data.longitud) {
                            actualizarPosicionGPS(data.latitud, data.longitud, data.velocidad);

                            // Actualizar métricas dinámicas
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

            // Iniciar consulta iterativa cada 3 segundos
            setInterval(consultarGPS, 3000);
            consultarGPS(); // Primera llamada inmediata
        });
    </script>
@endpush
