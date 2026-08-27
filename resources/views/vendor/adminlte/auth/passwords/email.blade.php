@extends('adminlte::auth.auth-page', ['authType' => 'login'])

@php
    $passEmailUrl = View::getSection('password_email_url') ?? config('adminlte.password_email_url', 'password/email');

    if (config('adminlte.use_route_url', false)) {
        $passEmailUrl = $passEmailUrl ? route($passEmailUrl) : '';
    } else {
        $passEmailUrl = $passEmailUrl ? url($passEmailUrl) : '';
    }
@endphp

@section('auth_header', __('adminlte::adminlte.password_reset_message'))

@section('auth_body')

    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ $passEmailUrl }}" method="post">
        @csrf

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

        {{-- Send reset link button --}}
        <button type="submit" class="flex w-full items-center justify-center {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}" style="border-radius: 10px">
            <span class="fas fa-share-square"></span>
            {{ __('adminlte::adminlte.send_password_reset_link') }}
        </button>
    </form>

@stop
