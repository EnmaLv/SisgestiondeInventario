<x-app-layout>
    <div class="pt-12 pb-4 bg-slate-50 dark:bg-gray-900 min-h-[calc(100vh-4rem)] flex flex-col">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 flex-1 flex flex-col w-full space-y-6">

            <div class="mb-6 text-left">
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Mural de Avisos</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Mantente informado con los avisos y noticias de nuestros psicólogos.</p>
            </div>

            <!-- Feed de Publicaciones -->
            <div class="space-y-6">
                @forelse($publicaciones as $pub)
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <!-- Cabecera de la publicación -->
                        <div class="p-4 sm:p-5 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-300 font-bold overflow-hidden">
                                    @if(isset($pub->profile_photo_path) && $pub->profile_photo_path)
                                        <img src="{{ route('media.profile_photos', basename($pub->profile_photo_path)) }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($pub->nombres ?? 'P', 0, 1) }}{{ substr($pub->apellidos ?? '', 0, 1) }}
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white leading-tight">
                                        {{ explode(' ', trim($pub->nombres ?? ''))[0] ?: 'Psicólogo' }} {{ explode(' ', trim($pub->apellidos ?? ''))[0] ?? '' }}
                                    </h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                        {{ \Carbon\Carbon::parse($pub->created_at)->format('d/m/Y h:i A') }}
                                        @if($pub->alcance === 'mis_pacientes')
                                            &middot; <span class="text-blue-500 font-medium">Solo para ti</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Contenido -->
                        <div class="px-4 sm:px-5 pb-4">
                            @if($pub->tipo === 'color')
                                <div class="rounded-2xl {{ $pub->color_fondo ?? 'bg-gray-800' }} p-12 text-center aspect-video flex flex-col items-center justify-center shadow-inner">
                                    <h3 class="text-2xl font-bold text-white leading-snug">{{ $pub->titulo }}</h3>
                                    @if($pub->contenido)
                                        <p class="text-white/90 mt-4 text-sm">{{ $pub->contenido }}</p>
                                    @endif
                                </div>
                            @elseif($pub->tipo === 'imagen' && $pub->media_path)
                                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-2">{{ $pub->titulo }}</h3>
                                @if($pub->contenido)
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">{{ $pub->contenido }}</p>
                                @endif
                                <div class="rounded-2xl overflow-hidden bg-gray-100 dark:bg-gray-900 shadow-inner">
                                    <img @click="$dispatch('open-image-modal', '{{ route('media.publicaciones', basename($pub->media_path)) }}')" src="{{ route('media.publicaciones', basename($pub->media_path)) }}" class="w-full h-auto object-cover max-h-[500px] cursor-pointer hover:opacity-90 transition-opacity">
                                </div>
                            @else
                                <!-- Texto normal -->
                                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-2">{{ $pub->titulo }}</h3>
                                <div class="text-sm text-gray-600 dark:text-gray-300 whitespace-pre-wrap">{{ $pub->contenido }}</div>
                            @endif
                        </div>
                        
                        <!-- Reacciones (Me interesa) -->
                        @php
                            $reaccionado = \Illuminate\Support\Facades\DB::table('publicacion_reacciones')
                                ->where('publicacion_id', $pub->id)
                                ->where('paciente_id', auth()->id())
                                ->exists();
                            
                            $likesCount = \Illuminate\Support\Facades\DB::table('publicacion_reacciones')
                                ->where('publicacion_id', $pub->id)
                                ->count();
                        @endphp
                        <div class="px-4 sm:px-5 py-3 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex items-center justify-between" 
                             x-data="{ 
                                reaccionado: {{ $reaccionado ? 'true' : 'false' }},
                                count: {{ $likesCount }},
                                loading: false,
                                toggle() {
                                    if(this.loading) return;
                                    this.loading = true;
                                    fetch('{{ route('admin.psicologia.maestros.publicaciones.reaccionar', $pub->id) }}', {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json'
                                        }
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        this.reaccionado = (data.status === 'added');
                                        this.count = data.total_likes;
                                    })
                                    .finally(() => {
                                        this.loading = false;
                                    });
                                }
                             }">
                            <button @click="toggle()" 
                                    :disabled="loading"
                                    :class="reaccionado ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
                                    class="flex items-center gap-2 text-sm font-medium transition-colors disabled:opacity-50">
                                <svg class="w-5 h-5 transition-transform" :class="reaccionado ? 'scale-110' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path x-show="!reaccionado" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                    <path x-show="reaccionado" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" fill="currentColor" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                </svg>
                                <span x-text="reaccionado ? 'Te interesa' : 'Me interesa'"></span>
                            </button>
                            <div class="text-xs text-gray-400 font-medium" x-show="count > 0" x-cloak>
                                <span x-text="count"></span> <span x-text="count == 1 ? 'persona' : 'personas'"></span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm">
                        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">Aún no hay publicaciones en el mural.</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">Vuelve pronto para ver las novedades de nuestros psicólogos.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Image Modal -->
        <div x-data="{ open: false, src: '' }"
             @open-image-modal.window="src = $event.detail; open = true"
             x-show="open" 
             style="display: none;" 
             class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-4"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="open = false"
             @click.self="open = false">
             
             <!-- Close Button -->
             <button @click="open = false" class="absolute top-4 right-4 text-white hover:text-gray-300 p-2 rounded-full bg-black/50 transition-colors">
                 <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
             </button>
             
             <!-- Modal Image -->
             <img :src="src" class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl" @click.stop>
        </div>
    </div>
</x-app-layout>
