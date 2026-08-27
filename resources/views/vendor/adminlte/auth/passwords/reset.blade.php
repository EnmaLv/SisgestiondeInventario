@extends('adminlte::auth.auth-page', ['authType' => 'login'])

@php
    $passResetUrl = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset');

    if (config('adminlte.use_route_url', false)) {
        $passResetUrl = $passResetUrl ? route($passResetUrl) : '';
    } else {
        $passResetUrl = $passResetUrl ? url($passResetUrl) : '';
    }
@endphp

@section('auth_header', __('adminlte::adminlte.password_reset_message'))

@section('auth_body')
    <form action="{{ $passResetUrl }}" method="post">
        @csrf

        {{-- Token field --}}
        <input type="hidden" name="token" value="{{ $token }}">

        {{-- Email field --}}
        <div class="flex items-stretch w-full mb-3">
            <input type="email" name="email" class="block w-full rounded-lg border px-3 py-2 text-sm @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}" autofocus>

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

        {{-- Password confirmation field --}}
        <div class="flex items-stretch w-full mb-3">
            <input type="password" name="password_confirmation"
                class="block w-full rounded-lg border px-3 py-2 text-sm @error('password_confirmation') is-invalid @enderror"
                placeholder="{{ trans('adminlte::adminlte.retype_password') }}">

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

        {{-- Confirm password reset button --}}
        <button type="submit" class="flex w-full items-center justify-center {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}" style="border-radius: 10px">
            <span class="fas fa-sync-alt"></span>
            {{ __('adminlte::adminlte.reset_password') }}
        </button>
    </form>
@stop
