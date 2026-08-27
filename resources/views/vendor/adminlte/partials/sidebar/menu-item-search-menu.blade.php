<li>

    <div class="form-inline my-2">
        <div class="flex items-stretch w-full" data-widget="sidebar-search" data-arrow-sign="&raquo;">

            {{-- Search input --}}
            <input class="block w-full rounded-lg border px-3 py-2 text-sm form-control-sidebar" type="search"
                @isset($item['id']) id="{{ $item['id'] }}" @endisset placeholder="{{ $item['text'] }}"
                aria-label="{{ $item['text'] }}">

            {{-- Search button --}}
            <div class="flex">
                <button class="btn btn-sidebar">
                    <i class="fas fa-fw fa-search"></i>
                </button>
            </div>

        </div>
    </div>

</li>
