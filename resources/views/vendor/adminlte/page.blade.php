@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')
@inject('preloaderHelper', 'JeroenNoten\LaravelAdminLte\Helpers\PreloaderHelper')

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())

@section('body')
    <div class="wrapper">

        {{-- Preloader Animation (fullscreen mode) --}}
        @if ($preloaderHelper->isPreloaderEnabled())
            @include('adminlte::partials.common.preloader')
        @endif

        <form id="form-actualizar-tasa"
            action="{{ route('productos.actualizar.tasa') }}"
            method="POST"
            style="display:none;">
            @csrf
        </form>

        @if(session()->has('tasa_pendiente'))
            <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    title: '🔄 Actualización obligatoria',
                    html: `
                        <p style="font-size:15px">
                            Para garantizar precios correctos es <b>obligatorio</b>
                            actualizar la tasa del dólar BCV.
                        </p>
                    `,
                    icon: 'warning',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonText: 'Actualizar ahora',
                    confirmButtonColor: '#16a34a'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-actualizar-tasa').submit();
                    }
                });
            });
            </script>
        @endif




        {{-- Top Navbar --}}
        @if ($layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.navbar.navbar-layout-topnav')
        @else
            @include('adminlte::partials.navbar.navbar')
        @endif

        {{-- Left Main Sidebar --}}
        @if (!$layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.sidebar.left-sidebar')
        @endif

        {{-- Content Wrapper --}}
        @empty($iFrameEnabled)
            @include('adminlte::partials.cwrapper.cwrapper-default')
        @else
            @include('adminlte::partials.cwrapper.cwrapper-iframe')
        @endempty

        {{-- Footer --}}
        @hasSection('footer')
            @include('adminlte::partials.footer.footer')
        @endif

        {{-- Right Control Sidebar --}}
        @if ($layoutHelper->isRightSidebarEnabled())
            @include('adminlte::partials.sidebar.right-sidebar')
        @endif

    </div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')

    <script>
        @if (session('mensaje'))
            Swal.fire({
                icon: '{{ session('icono') }}',
                title: '{{ session('mensaje') }}',
                @if(session('texto'))
                    text: '{{ session('texto') }}',
                @endif
                showConfirmButton: false,
                timer: 3000
            });
        @endif
    </script>
    <script src="{{ asset('js/validation_global.js') }}"></script>
@stop
