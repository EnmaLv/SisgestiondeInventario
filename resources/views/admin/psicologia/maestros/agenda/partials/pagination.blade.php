@if ($paginator->hasPages())
    
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center mt-4">
        <ul class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-slate-100 dark:border-gray-700">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="w-8 h-8 flex items-center justify-center text-slate-300 dark:text-gray-600 rounded-lg cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="w-8 h-8 flex items-center justify-center text-slate-500 dark:text-gray-400 hover:text-sky-700 dark:hover:text-sky-400 hover:bg-slate-50 dark:hover:bg-gray-700 rounded-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li>
                        <span class="w-8 h-8 flex items-center justify-center text-slate-400 dark:text-gray-500 text-sm">...</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span class="w-8 h-8 flex items-center justify-center text-white bg-sky-600 font-medium rounded-lg shadow-sm text-sm" aria-current="page">{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center text-slate-600 dark:text-gray-300 hover:text-sky-700 dark:hover:text-sky-400 hover:bg-slate-50 dark:hover:bg-gray-700 font-medium rounded-lg transition-all text-sm">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="w-8 h-8 flex items-center justify-center text-slate-500 dark:text-gray-400 hover:text-sky-700 dark:hover:text-sky-400 hover:bg-slate-50 dark:hover:bg-gray-700 rounded-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </li>
            @else
                <li>
                    <span class="w-8 h-8 flex items-center justify-center text-slate-300 dark:text-gray-600 rounded-lg cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
