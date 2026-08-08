<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-3xl border-t-8 border-blue-400 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Editar bloque de horario</h3>
                    @include('admin.psicologia.maestros.horarios.form', ['horario' => $horario, 'dias' => $dias, 'grupoRetorno' => $grupoRetorno ?? null])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
