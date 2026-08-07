@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center mt-2">
        <ul style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-2xl shadow-sm border">
            
            {{-- Anterior --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="w-8 h-8 flex items-center justify-center text-gray-300 dark:text-gray-600 rounded-xl cursor-not-allowed">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" 
                       class="w-8 h-8 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/40 rounded-xl transition-all">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </a>
                </li>
            @endif

            {{-- Elementos de Paginación --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li>
                        <span class="w-8 h-8 flex items-center justify-center text-gray-400 font-bold text-xs">...</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span class="w-8 h-8 flex items-center justify-center text-white bg-blue-600 font-bold rounded-xl shadow-md shadow-blue-500/20 text-xs" aria-current="page">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" 
                                   class="w-8 h-8 flex items-center justify-center text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/40 font-semibold rounded-xl transition-all text-xs">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Siguiente --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" 
                       class="w-8 h-8 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/40 rounded-xl transition-all">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </a>
                </li>
            @else
                <li>
                    <span class="w-8 h-8 flex items-center justify-center text-gray-300 dark:text-gray-600 rounded-xl cursor-not-allowed">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif