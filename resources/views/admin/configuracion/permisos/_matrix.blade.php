@php
    use Illuminate\Support\Str;
    $depth = $depth ?? 0;
    $margin = $depth * 15;
@endphp

@foreach($items as $it)
    @php
        $margin = ($depth) * 15;
    @endphp

    @if(isset($it['submenu']) && is_array($it['submenu']))
        <div class="permission-group-title mt-2 mb-2" style="margin-left:{{ $margin }}px;">
            <strong class="text-uppercase small text-muted" style="letter-spacing:0.5px; color:var(--color-secondary) !important;">
                <i class="fas fa-folder-open mr-1"></i> {{ $it['text'] }}
            </strong>
        </div>
        @include('admin.configuracion.permisos._matrix', [
            'items' => $it['submenu'],
            'rolePerms' => $rolePerms,
            'rolePatterns' => $rolePatterns,
            'effective' => $effective,
            'keyToPatterns' => $keyToPatterns,
            'depth' => $depth + 1,
        ])
    @else
        @php
            $val = $it['key'] ?? ($it['url'] ?? ($it['route'] ?? null));
            if (!$val) { continue; }

            $patterns = $keyToPatterns[$val] ?? [$val];

            $isRoleProvided = in_array($val, (array)$rolePerms) || count(array_intersect($patterns, (array)$rolePatterns)) > 0;
            $checked = (count(array_intersect($patterns, (array)$effective)) > 0);
            $id = 'check_' . Str::slug($val);
        @endphp

        <div class="custom-control custom-checkbox mb-2 permission-item d-flex align-items-center justify-content-between" style="margin-left:{{ $margin }}px;">
            <div class="flex-grow-1">
                <input type="checkbox" class="custom-control-input perm-chk" id="{{ $id }}" value="{{ e($val) }}" @if($checked) checked @endif data-role="{{ $isRoleProvided ? '1' : '0' }}" @if(! $isRoleProvided) name="allow[]" @endif>
                <label class="custom-control-label font-weight-normal" style="cursor:pointer; font-size:0.9rem;" for="{{ $id }}">
                    {{ e($it['text']) }}
                </label>
            </div>
            @if($isRoleProvided)
                <i class="fas fa-id-badge text-muted ml-2" title="Provisto por Rol" style="font-size:0.75rem;"></i>
            @endif
        </div>
    @endif
@endforeach
