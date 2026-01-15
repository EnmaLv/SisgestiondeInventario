<div class="row fade-in">
    <div class="col-md-12 m-auto">
        <form wire:submit="{{ $isEdit ? 'update' : 'create' }}" method="POST">
            @csrf

            <div class="mb-4">
                <h5 class="rd-title-sm text-muted mb-3" style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">
                    <i class="fas fa-id-card-alt mr-1"></i> Identidad y Sistema
                </h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="rd-label">Cédula / ID</label>
                        <div class="rd-input-group {{ $errors->has('cedula') ? 'border-danger' : '' }}">
                            <span><i class="fas fa-fingerprint"></i></span>
                            <input type="number" wire:model.lazy="cedula" min="7" name="cedula" class="rd-input form-control" placeholder="25123456" value="{{ old('cedula')}}"
                            {{ $onlyShow ? 'disabled' : ''  }}>
                        </div>
                        @error('cedula')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="rd-label">Fecha de Nacimiento</label>
                        <div class="rd-input-group">
                            <span><i class="fas fa-calendar-day"></i></span>
                            <input wire:model="fecha_nacimiento" type="date" name="fecha_nacimiento" class="rd-input form-control" 
                            {{ $onlyShow || !$formHabilitado ? 'disabled' : '' }} style="{{ $onlyShow || !$formHabilitado ? 'opacity: 0.5;' : '' }}" value="{{ old('fecha_nacimiento') }}" 
                            max="{{ Carbon\Carbon::now()->subYears(15)->format('Y-m-d') }}">
                        </div>
                        @error('fecha_nacimiento')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <h5 class="rd-title-sm text-muted mb-3" style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">
                    <i class="fas fa-user mr-1"></i> Nombres y Apellidos
                </h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="rd-label">Primer Nombre</label>
                                <div class="rd-input-group">
                                    <input wire:model="nombre" type="text" name="nombre" class="rd-input form-control" {{$onlyShow ||  !$formHabilitado ? 'disabled' : '' }}
                                     style="{{$onlyShow ||  !$formHabilitado ? 'opacity: 0.5;' : '' }}" placeholder="Juan" value="{{ old('nombre') }}">
                                </div>
                                @error('nombre')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="rd-label">Segundo Nombre</label>
                                <div class="rd-input-group">
                                    <input wire:model="segundo_nombre" type="text" name="segundo_nombre" class="rd-input form-control" {{$onlyShow ||  !$formHabilitado ? 'disabled' : '' }} 
                                    style="{{$onlyShow ||  !$formHabilitado ? 'opacity: 0.5;' : '' }}" placeholder="Opcional" value="{{ old('segundo_nombre') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="rd-label">Primer Apellido</label>
                                <div class="rd-input-group">
                                    <input wire:model="apellido" type="text" name="apellido" class="rd-input form-control" {{$onlyShow ||  !$formHabilitado ? 'disabled' : '' }}
                                     style="{{$onlyShow ||  !$formHabilitado ? 'opacity: 0.5;' : '' }}" placeholder="Pérez" value="{{ old('apellido') }}">
                                </div>
                                @error('apellido')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="rd-label">Segundo Apellido</label>
                                <div class="rd-input-group">
                                    <input wire:model="segundo_apellido" type="text" name="segundo_apellido" class="rd-input form-control" {{$onlyShow ||  !$formHabilitado ? 'disabled' : '' }} style="{{$onlyShow ||  !$formHabilitado ? 'opacity: 0.5;' : '' }}" placeholder="Opcional" value="{{ old('segundo_apellido') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <h5 class="rd-title-sm text-muted mb-3" style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">
                    <i class="fas fa-address-book mr-1"></i> Información de Contacto
                </h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="rd-label">Género</label>
                        <div class="rd-input-group">
                            <span><i class="fas fa-venus-mars"></i></span>
                            <select wire:model="genero" name="genero" class="rd-input form-control" {{$onlyShow ||  !$formHabilitado ? 'disabled' : '' }} style="{{$onlyShow ||  !$formHabilitado ? 'opacity: 0.5;' : '' }}">
                                <option value="" selected>Seleccione</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                                <option value="Otro">Otro</option>
                            </select>                            
                        </div>
                        @error('genero')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="rd-label">Teléfono Móvil</label>
                        <div class="rd-input-group">
                            <span><i class="fas fa-mobile-alt"></i></span>
                            <input wire:model="telefono" id="telefono" type="text" name="telefono" class="rd-input form-control" {{$onlyShow ||  !$formHabilitado ? 'disabled' : '' }} style="{{$onlyShow ||  !$formHabilitado ? 'opacity: 0.5;' : '' }}" data-inputmask="'mask': '(999) 999-9999'" data-mask placeholder="(123) 456-7890" value="{{ old('telefono') }}"
                            >
                        </div>
                        @error('telefono')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="rd-label">Correo Electrónico</label>
                        <div class="rd-input-group">
                            <span><i class="fas fa-at"></i></span>
                            <input wire:model="email" type="email" name="email" class="rd-input form-control" {{$onlyShow ||  !$formHabilitado ? 'disabled' : '' }} style="{{$onlyShow ||  !$formHabilitado ? 'opacity: 0.5;' : '' }}" placeholder="usuario@gmail.com" value="{{ old('email') }}">
                        </div>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>


            <div class="mb-4 fade-in">
                <h5 class="rd-title-sm text-muted mb-3" style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">
                    <i class="fas fa-address-book mr-1"></i> Información Academica
                </h5>

                <div class="row">
                    <div class="col-md-4 mb-3 fade-in">
                        <label class="rd-label">PNF</label>
                        <div class="rd-input-group">
                            <span><i class="fas fa-university"></i></span>
                            <select wire:model="pnfId" class="rd-input form-control" {{ $onlyShow  ||  !$formHabilitado? 'disabled' : ''  }} >
                                <option value="">Seleccione PNF</option>
                                @foreach($pnfs as $pnf)
                                    <option value="{{ $pnf->id_pnf }}">{{ $pnf->nombre_pnf }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('pnfId') <small class="text-danger"><b>El PNF es obligatorio</b></small> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="rd-label">Sede del Estudiante</label>
                        <div class="rd-input-group">
                            <span><i class="fas fa-university"></i></span>
                            <select wire:model="sedeId" class="rd-input form-control" {{ $onlyShow || !$formHabilitado ? 'disabled' : '' }}>
                                <option value="">Seleccione Sede</option>
                                @foreach($sede as $sed)
                                    <option value="{{ $sed->id_sede }}">{{ $sed->nombre_sede }}</option>
                                @endforeach
                            </select>
                            @error('sedeId') <small class="text-danger"><b>La Sede es obligatoria</b></small> @enderror
                        </div>
                        
                    </div>
                </div>
            </div>


            <hr class="my-4" style="border-top: 2px dashed #eef2f6;">

            <div class="mb-3">
                <h5 class="rd-title-sm text-muted mb-3" style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">
                    <i class="fas fa-map-marked-alt mr-1"></i> Ubicación y Residencia
                </h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="rd-label">Estado</label>
                        <div class="rd-input-group">
                            <span><i class="fas fa-map"></i></span>
                            <select wire:model.lazy="estadosVeId" name="estado_id" class="rd-input form-control" {{$onlyShow ||  !$formHabilitado ? 'disabled' : '' }} style="{{$onlyShow ||  !$formHabilitado ? 'opacity: 0.5;' : '' }}">
                                <option value="">Seleccione Estado</option>
                                @foreach($estadosVE as $estado)
                                    <option value="{{ $estado->id }}">{{ $estado->nombre_estado }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('estadosVeId')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="rd-label">Municipio</label>
                        <div class="rd-input-group">
                            <select wire:model.lazy="municipiosId"  name="municipio_id" class="rd-input form-control"
                             {{$onlyShow ||  !$formHabilitado ? 'disabled' : '' }} style="{{$onlyShow ||  !$formHabilitado ? 'opacity: 0.5;' : '' }}" @if(!$enabledMunicipio) disabled @endif>
                                <option value="">Seleccione Municipio</option>
                                @foreach($municipiosVE as $municipio)
                                    <option value="{{ $municipio->id }}">{{ $municipio->nombre_municipio }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('municipiosId')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="rd-label">Localidad</label>
                        <div class="rd-input-group">
                            <select wire:model.lazy="parroquiaId" name="parroquia_id" class="rd-input form-control" 
                            {{$onlyShow ||  !$formHabilitado ? 'disabled' : '' }} style="{{$onlyShow ||  !$formHabilitado ? 'opacity: 0.5;' : '' }}" @if(!$enabledParroquia) disabled @endif>
                                <option value="">Seleccione Localidad</option>
                                @foreach($parroquiasVE as $parroquia)
                                    <option value="{{ $parroquia->id }}">{{ $parroquia->nombre_localidad }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('parroquiaId')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                
                <div class="row">
                    {{-- Calle --}}
                    <div class="col-md-6 mb-3">
                        <label class="rd-label">Calle / Avenida</label>
                        <div class="rd-input-group">
                            <span><i class="fas fa-road"></i></span>
                            <input wire:model="calle" type="text" name="calle" class="rd-input form-control" {{$onlyShow ||  !$formHabilitado ? 'disabled' : '' }} style="{{$onlyShow ||  !$formHabilitado ? 'opacity: 0.5;' : '' }}" placeholder="Ej: Av. Francisco de Miranda" value="{{ old('calle') }}">
                        </div>
                        @error('calle')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    {{-- Sector / Urbanización --}}
                    <div class="col-md-6 mb-3">
                        <label class="rd-label">Sector / Urbanización</label>
                        <div class="rd-input-group">
                            <span><i class="fas fa-building"></i></span>
                            <input wire:model="sector" type="text" name="sector" class="rd-input form-control" {{$onlyShow ||  !$formHabilitado ? 'disabled' : '' }} style="{{$onlyShow ||  !$formHabilitado ? 'opacity: 0.5;' : '' }}" placeholder="Ej: Urb. Los Palos Grandes" value="{{ old('sector') }}">
                        </div>
                        @error('sector')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            @if(!$onlyShow)
            <div class="mt-4 d-flex justify-content-end gap-2">
                <button type="reset" class="rd-btn rd-btn-default" wire:click="$set('formHabilitado', false)">
                    <i class="fas fa-undo"></i> Restablecer
                </button>
                <button type="submit" class="rd-btn rd-btn-primary" {{ !$formHabilitado ? 'disabled' : '' }} style="{{ !$formHabilitado ? 'opacity: 0.5; cursor: not-allowed;' : '' }}">
                    <i class="fas fa-save"></i> {{ $isEdit ? 'Actualizar' : 'Registrar' }} Estudiante
                </button>
            </div>
            @endif
        </form>
    </div>
</div>

@section('js')
    <script>
        $(document).ready(function() {
            $("[data-mask]").inputmask();
        });

        document.addEventListener('DOMContentLoaded', () => {
            const telefonoInput = document.querySelector('#telefono');
            telefonoInput.addEventListener('input', (e) => {
                let value = e.target.value.replace(/\D/g, '');
                value = value.substring(0, 10);
                if (value.length > 3) {
                    value = value.substring(0, 3) + ' ' + value.substring(3);
                }
                
                e.target.value = value;
            })
            
            const cedulaInput = document.querySelector('input[name="cedula"]');
            if (cedulaInput) {
                cedulaInput.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    if (this.value.length > 8) {
                        this.value = this.value.slice(0, 8);
                    }
                });
            }
            document.addEventListener('livewire:initialized', () => {
                @this.on('alert', (params) => {
                    Swal.fire({
                        icon: params.type,
                        title: params.title,
                        text: params.text,
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#3085d6',
                    });
                });
            });
        })
    </script>
@endsection





