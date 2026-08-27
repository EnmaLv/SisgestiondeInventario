@extends('adminlte::auth.auth-page', ['authType' => 'register'])

@php
    $loginUrl = View::getSection('login_url') ?? config('adminlte.login_url', 'login');
    $registerUrl = View::getSection('register_url') ?? config('adminlte.register_url', 'register');

    if (config('adminlte.use_route_url', false)) {
        $loginUrl = $loginUrl ? route($loginUrl) : '';
        $registerUrl = $registerUrl ? route($registerUrl) : '';
    } else {
        $loginUrl = $loginUrl ? url($loginUrl) : '';
        $registerUrl = $registerUrl ? url($registerUrl) : '';
    }
@endphp

@section('auth_header', __('adminlte::adminlte.register_message'))

@section('auth_body')
    <form action="{{ $registerUrl }}" method="post">
        @csrf

        {{-- Name field --}}
        <div class="flex items-stretch w-full mb-3">
            <input type="text" name="name" class="block w-full rounded-lg border px-3 py-2 text-sm @error('name') is-invalid @enderror"
                value="{{ old('name') }}" placeholder="{{ __('adminlte::adminlte.full_name') }}" autofocus>

            <div class="flex">
                <div class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800">
                    <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Email field --}}
        <div class="flex items-stretch w-full mb-3">
            <input type="email" name="email" class="block w-full rounded-lg border px-3 py-2 text-sm @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}">

            <div class="flex">
                <div class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800">
                    <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Password field --}}
        <div class="flex items-stretch w-full mb-3">
            <input type="password" name="password" class="block w-full rounded-lg border px-3 py-2 text-sm @error('password') is-invalid @enderror"
                placeholder="{{ __('adminlte::adminlte.password') }}">

            <div class="flex">
                <div class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Confirm password field --}}
        <div class="flex items-stretch w-full mb-3">
            <input type="password" name="password_confirmation"
                class="block w-full rounded-lg border px-3 py-2 text-sm @error('password_confirmation') is-invalid @enderror"
                placeholder="{{ __('adminlte::adminlte.retype_password') }}">

            <div class="flex">
                <div class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('password_confirmation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Register button --}}
        <button type="submit" class="flex w-full items-center justify-center {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}" style="border-radius: 10px">
            <span class="fas fa-user-plus"></span>
            {{ __('adminlte::adminlte.register') }}
        </button>
    </form>
@stop

@section('auth_footer')
    <p class="my-0">
        <a href="{{ $loginUrl }}">
            {{ __('adminlte::adminlte.i_already_have_a_membership') }}
        </a>
    </p>
@stop
