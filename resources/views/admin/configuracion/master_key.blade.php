<!-- Master key form for configuration access -->
@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4">
        <h1 class="m-0">Verificar Llave Maestra</h1>
    </div>
@stop

@section('content')
    <div class="rd-card rd-card-full">
        <div class="rd-card-body">
            <style>
                .master-key-input {
                    background: #f3f4f6; /* gray-100 */
                    border: 1px solid #9ca3af; /* gray-400 */
                    color: #0f172a;
                    box-shadow: 0 0 0 3px rgba(156,163,175,0.06);
                }
                .master-key-input:focus {
                    outline: none;
                    box-shadow: 0 0 0 4px rgba(156,163,175,0.12);
                }
            </style>
            <form action="{{ route('admin.configuracion.master_key.verify') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Llave Maestra</label>
                    <input type="password" name="master_key" class="form-control master-key-input" required />
                    @error('master_key') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mt-3">
                    <button class="rd-btn rd-btn-primary">Verificar</button>
                </div>
            </form>
        </div>
    </div>
@stop
