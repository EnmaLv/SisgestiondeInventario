@extends('adminlte::page')

@section('content_header')
    <h1>Panel de Control</h1>
@stop

@section('content')
    <p>Bienvenido {{ auth()->user()->name }}.</p>
    <hr>

    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            .<div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-envelope"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Mensaje</span>
                    <span class="info-box-number">
                        1.410
                    </span>
                </div>
            </div>
        </div>
    @stop

    @section('css')
        {{-- Add here extra stylesheets --}}
        {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    @stop

    @section('js')
        <script>
            console.log("Hi, I'm using the Laravel-AdminLTE package!");
        </script>
    @stop
