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
                Vista detallada del Registro
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

@php
    //Formatear la edad para que se actualize dinamicamente
    $edad = \Carbon\Carbon::parse($registro->fecha_nacimiento_persona)->age;
@endphp

@section('content')
    <div class="registro-layout">
        <section class="profile-hero rd-card">
            <div class="profile-hero__content">
                <p class="hero-eyebrow">Registro diario · {{ $registro->nombre_pnf }}</p>
                <h2 class="hero-title">Ficha del estudiante</h2>
                <p class="hero-text">
                    <strong>
                        {{ $registro->nombre_persona . ' ' . $registro->segundo_nombre_persona . ' ' . $registro->apellido_persona . ' ' . $registro->segundo_apellido_persona }}
                    </strong>
                    fue registrado en el sistema con la información detallada a continuación.
                </p>

            </div>

            <div class="profile-hero__actions">
                <a href="{{ url()->previous() }}" class="rd-btn rd-btn-default">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </section>

        <section class="info-grid">
            <article class="info-card rd-card">
                <header>
                    <h3><i class="fas fa-user-circle"></i> Datos personales</h3>
                    <span class="section-hint">Identidad del estudiante</span>
                </header>
                <div class="info-list">
                    <div class="info-item">
                        <span class="info-label">Nombre completo</span>
                        <p class="info-value">{{ $registro->nombre_persona . ' ' . $registro->segundo_nombre_persona . ' ' . $registro->apellido_persona . ' ' . $registro->segundo_apellido_persona }}</p>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Cédula</span>
                        <p class="info-value">{{ $registro->cedula_persona }}</p>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Género</span>
                        <p class="info-value">{{ $registro->genero_persona }}</p>
                    </div>
                    <div class="info-item info-item-inline">
                        <div>
                            <span class="info-label">Fecha de nacimiento</span>
                            <p class="info-value">{{ \Carbon\Carbon::parse($registro->fecha_nacimiento_persona)->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <span class="info-label">Edad</span>
                            <p class="info-value">{{ $edad }} años</p>
                        </div>
                    </div>
                </div>
            </article>

            <article class="info-card rd-card">
                <header>
                    <h3><i class="fas fa-address-book"></i> Contacto y PNF</h3>
                    <span class="section-hint">Medios para ubicar al estudiante</span>
                </header>
                <div class="info-list">
                    <div class="info-item">
                        <span class="info-label">Teléfono</span>
                        <p class="info-value">{{ $registro->telefono_persona }}</p>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Correo electrónico</span>
                        <p class="info-value">{{ $registro->email_persona }}</p>
                    </div>
                    <div class="info-item">
                        <span class="info-label">PNF asociado</span>
                        <p class="info-value">{{ $registro->nombre_pnf }}</p>
                    </div>
                </div>
            </article>

            <article class="info-card rd-card registro-card">
                <header>
                    <h3><i class="fas fa-clock"></i> Registro del sistema</h3>
                    <span class="section-hint">Fecha y hora oficial</span>
                </header>
                <div class="info-list">
                    <div class="info-item">
                        <span class="info-label">Fecha de registro</span>
                        <p class="info-value">{{ \Carbon\Carbon::parse($registro->fecha_regis_diario_c)->format('d/m/Y') }}</p>
                        <small class="info-helper">Información tomada del formulario enviado.</small>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Hora registrada</span>
                        <p class="info-value">{{ $registro->hora }}</p>
                        <small class="info-helper">Corresponde a la hora exacta de creación.</small>
                    </div>
                </div>
            </article>
        </section>
    </div>
@endsection

@section('css')
    {{-- Nuevo diseño para la vista de detalle del registro diario --}}
    <style>
        :root {
            --rd-border: #e2e8f0;
            --rd-text: #0f172a;
            --rd-muted: #64748b;
            --rd-primary: #2563eb;
            --rd-bg: #f8fafc;
        }

        .registro-layout {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .rd-card {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid var(--rd-border);
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }

        .profile-hero {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 1.5rem;
            background: linear-gradient(135deg, #eff6ff, #fdf2f8);
            border: 1px solid rgba(37, 99, 235, 0.15);
        }

        .profile-hero__content {
            max-width: 580px;
        }

        .hero-eyebrow {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--rd-primary);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .hero-title {
            font-size: 2rem;
            margin: 0 0 0.5rem;
            color: var(--rd-text);
        }

        .hero-text {
            margin-bottom: 1rem;
            color: var(--rd-muted);
            line-height: 1.5;
        }

        .profile-hero__actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: flex-start;
            margin-left: auto;
        }

        .rd-btn.rd-btn-default {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.55rem 1.1rem;
            border-radius: 999px;
            border: none;
            background: var(--rd-text);
            color: #fff;
            font-weight: 600;
            transition: opacity 0.2s ease;
            text-decoration: none;
        }

        .rd-btn.rd-btn-default:hover {
            opacity: 0.9;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.25rem;
        }

        .info-card header {
            margin-bottom: 1rem;
        }

        .info-card h3 {
            margin: 0;
            font-size: 1.1rem;
            color: var(--rd-text);
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .section-hint {
            font-size: 0.85rem;
            color: var(--rd-muted);
        }

        .info-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .info-item {
            padding-bottom: 0.75rem;
            border-bottom: 1px dashed var(--rd-border);
        }

        .info-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .info-item-inline {
            display: flex;
            gap: 1.25rem;
        }

        .info-item-inline > div {
            flex: 1;
        }

        .info-label {
            font-size: 0.8rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--rd-muted);
        }

        .info-value {
            margin: 0.2rem 0 0;
            font-size: 1.05rem;
            color: var(--rd-text);
            font-weight: 600;
        }

        .info-footer {
            margin-top: 1.25rem;
        }

        .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.9rem;
            border-radius: 999px;
            background: rgba(34, 197, 94, 0.12);
            color: #15803d;
            font-weight: 600;
        }

        .registro-card {
            background: linear-gradient(135deg, #fff, #f8fafc);
        }

        .info-helper {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.8rem;
            color: var(--rd-muted);
        }

        @media (max-width: 576px) {
            .profile-hero__actions {
                align-items: flex-start;
            }

            .hero-title {
                font-size: 1.5rem;
            }

            .info-item-inline {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
@endsection