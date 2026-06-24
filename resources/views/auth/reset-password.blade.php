@extends('layouts.guest')

@section('title', __('Đặt lại mật khẩu').' · Académie')

@section('content')
    <div class="login">
        <div class="login-form">
            <div class="login-card">
                <h1>{{ __('Đặt lại mật khẩu') }}</h1>

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="login-fields">
                        <div class="field">
                            <x-input-label for="email" value="{{ __('Email') }}" />
                            <x-text-input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" />
                        </div>

                        <div class="field">
                            <x-input-label for="password" value="{{ __('Mật khẩu mới') }}" />
                            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" />
                        </div>

                        <div class="field">
                            <x-input-label for="password_confirmation" value="{{ __('Xác nhận mật khẩu') }}" />
                            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" />
                        </div>
                    </div>

                    <x-primary-button class="w-full justify-center" style="margin-top:22px;padding:14px">{{ __('Đặt lại mật khẩu') }}</x-primary-button>
                </form>
            </div>
        </div>
    </div>
@endsection
