@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center"
        style="
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
            border: 1px solid #e5e7eb;
         ">

        <!-- Texto principal -->
        <div>
            <h1 class="m-0" style="font-size:1.45rem; color:#0f172a; font-weight:700;">
                Registro Diario del Comedor
            </h1>

            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Bienvenido <strong>{{ auth()->user()->name }}</strong>.
            </p>
        </div>

        <!-- Imagen + Fecha -->
        <div class="d-flex align-items-center" style="gap:14px;">
            <div class="text-right d-none d-sm-block">
                <small class="text-muted d-block" style="font-size:0.75rem;">Hoy</small>
                <span style="font-weight:600; font-size:0.95rem;">
                    {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                </span>
            </div>

            <div
                style="
                width:46px;
                height:46px;
                border-radius:12px;
                overflow:hidden;
                box-shadow:0 4px 12px rgba(15,23,42,0.08);
            ">
                <img src="{{ asset('img/usuario-verificado.png') }}" alt="Usuario"
                    style="width:100%; height:100%; object-fit:cover;">
            </div>
        </div>

    </div>
@stop



@section('content')

    <livewire:register-noti />

@stop

@section('js')
    <script>
        const desdeDate = document.getElementById('fecha_desde');
        const hastaDate = document.getElementById('fecha_hasta');

        // Fecha actual (máximo permitido)
        const fechaActual = new Date().toISOString().split('T')[0];

        // Establecer máximo hoy para ambos campos
        if (desdeDate) desdeDate.max = fechaActual;
        if (hastaDate) hastaDate.max = fechaActual;


        // Cuando cambie "desde", ajustar el mínimo de "hasta"
        if (desdeDate && hastaDate) {
            desdeDate.addEventListener('change', function () {
                if (!desdeDate.value) {
                    // Si se borra la fecha desde, quitamos la restricción mínima en hasta
                    hastaDate.min = '';
                    return;
                }

                // "hasta" no puede ser menor que "desde"
                hastaDate.min = desdeDate.value;

                if (hastaDate.value && hastaDate.value < desdeDate.value) {
                    hastaDate.value = desdeDate.value;
                }
            });

            // Cuando cambie "hasta", validar contra "desde"
            hastaDate.addEventListener('change', function () {
                if (!hastaDate.value || !desdeDate.value) {
                    return;
                }

                if (hastaDate.value < desdeDate.value) {
                    // Si el usuario pone una fecha hasta menor, movemos "desde" a esa fecha
                    desdeDate.value = hastaDate.value;
                }
            });
        }

        //Script para mostrar el PdfGeneratorUtil
        const pdfBtn = document.querySelector('#pdfBtn');
        const pdfRoute = `{{ route('admin.movimientos.registro_diario.export_pdf') }}`;
        if (pdfBtn) {
            pdfBtn.addEventListener('click', function () {

                const params = new URLSearchParams(window.location.search);
                const fechaDesde = params.get('fecha_desde')?? "";
                const fechaHasta = params.get('fecha_hasta')?? "";

                const url = `${pdfRoute}?fecha_desde=${fechaDesde}&fecha_hasta=${fechaHasta}`;
                window.open(url, '_blank');     
            });
        }

    </script>
@stop
