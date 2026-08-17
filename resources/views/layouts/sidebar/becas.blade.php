@php
    $becaItems = collect(config('adminlte.menu'))->filter(function ($item) {
        return isset($item['module']) && $item['module'] === 'beca';
    });

    $headerItem = $becaItems->first(function ($item) {
        return isset($item['header']);
    });

    $menuItems = $becaItems->filter(function ($item) {
        return !isset($item['header']) && isset($item['key']);
    });

    $allKeys = $menuItems->pluck('key')->toArray();
@endphp

@canMenu($allKeys)
    @if ($headerItem)
        <div class="pt-3 pb-1" :class="sidebarOpen ? 'block' : 'hidden'">
            <span class="px-3 text-[11px] font-bold uppercase tracking-wider text-white/50">
                {{ $headerItem['header'] }}
            </span>
        </div>
    @else
        <div class="pt-3 pb-1" :class="sidebarOpen ? 'block' : 'hidden'">
            <span class="px-3 text-[11px] font-bold uppercase tracking-wider text-white/50">
                Gestión de Becas
            </span>
        </div>
    @endif

    @foreach ($menuItems as $item)
        @canMenu($item['key'])
            <a href="{{ url($item['url']) }}"
               class="w-full flex items-center h-10 rounded-lg px-3 gap-2.5 text-white/90 hover:bg-[#623739] hover:text-white transition-all min-w-0"
               :class="sidebarOpen ? 'px-3' : 'justify-center px-0'"
               title="{{ $item['text'] }}">
                <i class="{{ $item['icon'] ?? 'fas fa-link' }} text-base w-5 text-center flex-shrink-0 text-white"></i>
                <span class="text-sm font-medium truncate" :class="sidebarOpen ? 'block' : 'hidden'">
                    {{ $item['text'] }}
                </span>
            </a>
        @endcanMenu
    @endforeach
@endcanMenu