@php( $logout_url = View::getSection('logout_url') ?? config('adminlte.logout_url', 'logout') )
@php( $profile_url = View::getSection('profile_url') ?? config('adminlte.profile_url', 'logout') )

@if (config('adminlte.usermenu_profile_url', false))
    @php( $profile_url = Auth::user()->adminlte_profile_url() )
@endif

@if (config('adminlte.use_route_url', false))
    @php( $profile_url = $profile_url ? route($profile_url) : '' )
    @php( $logout_url = $logout_url ? route($logout_url) : '' )
@else
    @php( $profile_url = $profile_url ? url($profile_url) : '' )
    @php( $logout_url = $logout_url ? url($logout_url) : '' )
@endif

<li class="nav-item user-menu d-flex align-items-center">

    @if(config('adminlte.usermenu_image'))
        <img src="{{ Auth::user()->adminlte_image() }}"
             class="user-image img-circle elevation-2 me-2"
             alt="{{ Auth::user()->name }}">
    @endif

    <form id="logout-form" action="{{ $logout_url }}" method="POST" class="m-0">
        @if(config('adminlte.logout_method'))
            {{ method_field(config('adminlte.logout_method')) }}
        @endif
        {{ csrf_field() }}
        <button type="submit" class="rd-icon-btn" title="{{ __('adminlte::adminlte.log_out') }}">
            <i class="fas fa-power-off"></i>
        </button>
    </form>

</li>
