<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    <h1>{{ $count }}</h1>
    <button wire:click="increment">+ Agregar</button>
    <button wire:click="decrement">- Quitar</button>
</div>

<div class="flex flex-wrap -mx-2">
    <div class="w-full">
        <div class="card card-outline card-primary">
            <div model:wire.live class="card-header">
                <h3 class="card-title"><b>Productos Registrados</b></h3>

                <div class="card-tools">
                    <a class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700" href=" {{ url('admin/maestros/productos/create') }}" class="inline-flex items-center rounded-lg p-2 text-slate-500 hover:bg-slate-100">
                        <i class="fas fa-plus"></i>
                        <b>Crear Nuevo</b>
                    </a>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body" style="display: block;">
                <table id="example1" class="table table-bordered table-striped table-hover table-sm" border="1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Categoria</th>
                            <th>Codigo</th>
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Imagen</th>
                            <th>Precio de Compra</th>
                            <th>Precio de Venta</th>
                            <th>Stock Minimo</th>
                            <th>Stock Maximo</th>
                            <th>Unidad de Medida</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productos as $producto)
                            <tr>
                                <td style="text-align: center;">{{ $loop->iteration }}</td>
                                <td>{{ $producto->categoria->nombre }}</td>
                                <td>{{ $producto->codigo }}</td>
                                <td>{{ $producto->nombre }}</td>
                                <td>{!! $producto->descripcion !!}</td>
                                <td>
                                    <img src="{{ asset('storage/' . $producto->imagen) }}" class="img-thumbnail"
                                        alt="{{ $producto->nombre }}"
                                        style="width: 150px; height: auto; object-fit: cover;">
                                </td>
                                <td>{{ $producto->precio_compra }}</td>
                                <td>{{ $producto->precio_venta }}</td>
                                <td>{{ $producto->stock_minimo }}</td>
                                <td>{{ $producto->stock_maximo }}</td>
                                <td>{{ $producto->unidad_medida }}</td>
                                <td style="text-align: center;">
                                    @if ($producto->estado)
                                        <span class="badge badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <a href="{{ url('admin/maestros/productos/' . $producto->id) }}"
                                        class="inline-flex items-center justify-center rounded-lg bg-sky-600 px-3 py-2 font-semibold text-white hover:bg-sky-700"><i class="fas fa-eye"></i></a>
                                    <a href="{{ url('admin/maestros/productos/' . $producto->id . '/edit') }}"
                                        class="inline-flex items-center justify-center rounded-lg bg-amber-500 px-3 py-2 font-semibold text-white hover:bg-amber-600"><i class="fas fa-edit"></i></a>
                                    <form action="{{ url('admin/maestros/productos/' . $producto->id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-red-600 px-4 py-2 font-semibold text-white hover:bg-red-700"
                                            onclick="preguntar{{ $producto->id }}(event)"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                    <script>
                                        function preguntar{{ $producto->id }}(event) {
                                            event.preventDefault();
                                            Swal.fire({
                                                title: '¿Estás seguro?',
                                                text: "No podrás deshacer esta acción",
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#3085d6',
                                                cancelButtonColor: '#d33',
                                                confirmButtonText: 'Sí, eliminar',
                                                cancelButtonText: 'Cancelar'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    event.target.form.submit();
                                                }
                                            });
                                        }
                                    </script>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
