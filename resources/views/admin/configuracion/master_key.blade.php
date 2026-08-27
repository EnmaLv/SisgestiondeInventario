@extends('adminlte::page')

@section('title', 'Verificación de Seguridad')

@section('content')
    <div class="flex justify-center items-center" style="min-height: 70vh;">
        <div class="rd-card p-0" style="
            width: 100%;
            max-width: 450px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            border: 1px solid #e5e7eb;
            overflow: hidden;
        ">
            <div class="text-center p-4" style="background: #f8fafc; border-bottom: 1px solid #e5e7eb;">
                <div class="mb-3">
                    <i class="fas fa-shield-alt fa-3x" style="color: #64748b;"></i>
                </div>
                <h1 style="font-size: 1.5rem; color: #0f172a; font-weight: 700; margin-bottom: 8px;">
                    Verificar Llave Maestra
                </h1>
                <p style="font-size: 0.9rem; color: #64748b; line-height: 1.4; margin: 0;">
                    Para acceder a la configuración sensible, debes validar tu identidad.
                </p>
            </div>

            <div class="p-4">
                <form action="{{ route('admin.configuracion.master_key.verify') }}" method="POST" class="rd-prevent-double-submit">
                    @csrf
                    <div class="form-group">
                        <label for="master_key" style="font-weight: 600; color: #334155; font-size: 0.9rem;">
                            Llave Maestra
                        </label>
                        <div class="flex items-stretch w-full">
                            <div class="flex items-stretch w-full-prepend">
                                <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 bg-transparent border-right-0" style="border-radius: 8px 0 0 8px;">
                                    <i class="fas fa-key text-muted"></i>
                                </span>
                            </div>
                            <input type="password" 
                                name="master_key" 
                                id="master_key"
                                class="block w-full rounded-lg border px-3 py-2 text-sm border-left-0 @error('master_key') is-invalid @enderror" 
                                placeholder="Introduce tu clave..."
                                style="border-radius: 0 8px 8px 0; height: 45px;"
                                required 
                                autofocus />
                            <div class="flex">
                                <button type="button" class="rd-btn rd-btn-primary" id="togglePassword" style="border-radius: 0 8px 8px 0; border-left: none;">
                                    <i class="fas fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>
                        @error('master_key') 
                            <span class="invalid-feedback block mt-2" role="alert">
                                <strong>{{ $message }}</strong>
                            </span> 
                        @enderror
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="rd-btn rd-btn-primary rd-submit-btn w-100 text-center flex justify-center items-center">
                            Verificar Acceso
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@push('js')
<script>
    // Script para mostrar/ocultar contraseña
    document.getElementById('togglePassword').addEventListener('click', function (e) {
        const password = document.getElementById('master_key');
        const icon = document.getElementById('eyeIcon');
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });
</script>
@endpush

@push('css')
    <style>
        .inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800{
            border: none !important;
        }
    </style>
@endpush