                @php
                    $unreadCount = \App\Models\Notification::obtenerConteoNoLeidas(auth()->id());
                    $allNotifications = \App\Models\Notification::obtenerNotificacionesRecientes(auth()->id());
                @endphp
                <div class="relative" x-data="{
                    openNotif: false,
                    optionsOpen: false,
                    filter: 'all'
                }" @click.away="openNotif = false; optionsOpen = false">
                    <!-- Bell Button -->
                    <button @click="openNotif = !openNotif"
                        class="relative p-2 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-full transition-all duration-200 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        @if ($unreadCount > 0)
                            <span id="main-notif-badge"
                                class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-0.5 flex items-center justify-center rounded-full bg-red-500 text-white text-[10px] font-bold border-2 border-white dark:border-gray-800 shadow">
                                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown Panel -->
                    <div x-show="openNotif" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                        class="absolute -right-12 sm:right-0 mt-3 w-[320px] max-w-[95vw] sm:w-[380px] bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 z-50 overflow-hidden"
                        style="display: none;">

                        <!-- Header -->
                        <div class="px-4 pt-4 pb-2 flex items-center justify-between">
                            <h3 class="text-[17px] font-black text-gray-900 dark:text-white tracking-tight">
                                Notificaciones</h3>
                            <div class="relative">
                                <button @click.stop="optionsOpen = !optionsOpen"
                                    class="p-1.5 text-gray-400 dark:text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
                                    </svg>
                                </button>
                                <div x-show="optionsOpen" x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    class="absolute right-0 mt-1 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 z-50 overflow-hidden"
                                    style="display: none;">
                                    <button type="button"
                                        @click.stop="
                                                fetch('{{ route('notifications.readAll') }}', {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                                        'Accept': 'application/json',
                                                        'Content-Type': 'application/json'
                                                    }
                                                }).then(() => {
                                                    document.querySelectorAll('[data-unread=\'true\']').forEach(el => {
                                                        el.dataset.unread = 'false';
                                                        el.classList.remove('bg-blue-50/40');
                                                        const dot = el.querySelector('.notif-dot');
                                                        if (dot) dot.remove();
                                                        const time = el.querySelector('.notif-time');
                                                        if (time) { time.classList.remove('text-blue-600', 'font-bold'); time.classList.add('text-gray-400'); }
                                                        const body = el.querySelector('.notif-body');
                                                        if (body) body.classList.remove('font-semibold');
                                                    });
                                                    document.querySelectorAll('.notif-badge').forEach(b => b.remove());
                                                    const mb = document.getElementById('main-notif-badge');
                                                    if(mb) mb.remove();
                                                    optionsOpen = false;
                                                })
                                            "
                                        class="w-full flex items-center gap-3 px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition text-left">
                                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Marcar todo como leído
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="px-4 pb-2 flex gap-2">
                            <button @click.stop="filter = 'all'"
                                :class="filter === 'all' ? 'bg-blue-600 text-white' :
                                    'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                                class="px-4 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap">
                                Todas
                            </button>
                            <button @click.stop="filter = 'unread'"
                                :class="filter === 'unread' ? 'bg-blue-600 text-white' :
                                    'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                                class="px-4 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap">
                                No leídas
                                @if ($unreadCount > 0)
                                    <span
                                        class="ml-1 bg-red-500 text-white rounded-full px-1.5 py-0.5 text-[9px] notif-badge">{{ $unreadCount }}</span>
                                @endif
                            </button>
                        </div>

                        <!-- Lista -->
                        <div x-ref="notifList"
                            class="custom-scrollbar max-h-[400px] overflow-y-auto divide-y divide-gray-50 dark:divide-gray-700">
                            @forelse($allNotifications as $notification)
                                <a href="{{ route('notifications.read', $notification->id) }}"
                                    data-notif="{{ $notification->id }}"
                                    data-unread="{{ is_null($notification->read_at) ? 'true' : 'false' }}"
                                    x-show="filter === 'all' || (filter === 'unread' && {{ is_null($notification->read_at) ? 'true' : 'false' }})"
                                    class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition group relative {{ is_null($notification->read_at) ? 'bg-blue-50/40 dark:bg-blue-900/20' : '' }}">
                                    <div class="flex items-start gap-3">
                                        <div class="relative flex-shrink-0">
                                            @if (($notification->data['type_id'] ?? '') === 'new_message')
                                                <div
                                                    class="w-11 h-11 bg-indigo-100 dark:bg-indigo-900/50 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @elseif(($notification->data['type_id'] ?? '') === 'cita_confirmed')
                                                <div
                                                    class="w-11 h-11 bg-emerald-100 dark:bg-emerald-900/50 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <rect x="3" y="4" width="18" height="18" rx="2"
                                                            ry="2" stroke-width="2" />
                                                        <line x1="16" y1="2" x2="16"
                                                            y2="6" stroke-width="2" stroke-linecap="round" />
                                                        <line x1="8" y1="2" x2="8"
                                                            y2="6" stroke-width="2" stroke-linecap="round" />
                                                        <line x1="3" y1="10" x2="21"
                                                            y2="10" stroke-width="2" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M9 16l2 2 4-4" />
                                                    </svg>
                                                </div>
                                            @elseif(($notification->data['type_id'] ?? '') === 'cita_cancelled')
                                                <div
                                                    class="w-11 h-11 bg-red-100 dark:bg-red-900/50 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <rect x="3" y="4" width="18" height="18"
                                                            rx="2" ry="2" stroke-width="2" />
                                                        <line x1="16" y1="2" x2="16"
                                                            y2="6" stroke-width="2" stroke-linecap="round" />
                                                        <line x1="8" y1="2" x2="8"
                                                            y2="6" stroke-width="2" stroke-linecap="round" />
                                                        <line x1="3" y1="10" x2="21"
                                                            y2="10" stroke-width="2" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M10 14l4 4M14 14l-4 4" />
                                                    </svg>
                                                </div>
                                            @elseif(($notification->data['type_id'] ?? '') === 'cita_requested')
                                                <div
                                                    class="w-11 h-11 bg-amber-100 dark:bg-amber-900/50 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <rect x="3" y="4" width="18" height="18"
                                                            rx="2" ry="2" stroke-width="2" />
                                                        <line x1="16" y1="2" x2="16"
                                                            y2="6" stroke-width="2" stroke-linecap="round" />
                                                        <line x1="8" y1="2" x2="8"
                                                            y2="6" stroke-width="2" stroke-linecap="round" />
                                                        <line x1="3" y1="10" x2="21"
                                                            y2="10" stroke-width="2" />
                                                        <circle cx="12" cy="16" r="3"
                                                            stroke-width="1.5" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5" d="M12 14.5v1.5l1 1" />
                                                    </svg>
                                                </div>
                                            @elseif(($notification->data['type_id'] ?? '') === 'nuevo_aviso')
                                                <div
                                                    class="w-11 h-11 bg-sky-100 dark:bg-sky-900/50 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-sky-600 dark:text-sky-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            @elseif(($notification->data['type_id'] ?? '') === 'reaccion_aviso')
                                                <div
                                                    class="w-11 h-11 bg-pink-100 dark:bg-pink-900/50 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-pink-600 dark:text-pink-400"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5">
                                                        </path>
                                                    </svg>
                                                </div>
                                            @else
                                                <div
                                                    class="w-11 h-11 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p
                                                class="notif-body text-[13px] text-gray-800 dark:text-gray-200 leading-snug {{ is_null($notification->read_at) ? 'font-semibold' : '' }}">
                                                {{ $notification->data['body'] ?? '' }}
                                            </p>
                                            <p
                                                class="notif-time text-[11px] mt-0.5 {{ is_null($notification->read_at) ? 'text-blue-600 dark:text-blue-400 font-bold' : 'text-gray-400 dark:text-gray-500' }}">
                                                {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                            </p>
                                        </div>
                                        @if (is_null($notification->read_at))
                                            <div
                                                class="notif-dot w-2.5 h-2.5 bg-red-500 rounded-full flex-shrink-0 self-center mt-0.5">
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            @empty
                                <div class="py-10 flex flex-col items-center gap-2 text-gray-400 dark:text-gray-500">
                                    <svg class="w-10 h-10 text-gray-200 dark:text-gray-700" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                        </path>
                                    </svg>
                                    <p class="text-sm font-medium">Sin notificaciones</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
