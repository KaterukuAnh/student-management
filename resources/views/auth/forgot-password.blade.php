@extends('layouts.guest')

@section('title', __('Quên mật khẩu').' · Académie')

@section('content')
    <div class="login">
        <div class="login-form">
            <div class="login-card">
                <h1>{{ __('Quên mật khẩu?') }}</h1>
                <div class="ls">{{ __('Nhập email của bạn, chúng tôi sẽ gửi liên kết đặt lại mật khẩu.') }}</div>

                <x-auth-session-status class="mt-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="login-fields">
                        <div class="field">
                            <x-input-label for="email" value="{{ __('Email') }}" />
                            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
                            <x-input-error :messages="$errors->get('email')" />
                        </div>
                    </div>

                    <x-primary-button class="w-full justify-center" style="margin-top:22px;padding:14px">{{ __('Gửi liên kết đặt lại mật khẩu') }}</x-primary-button>
                </form>

                <div class="login-demo">
                    <a href="{{ route('login') }}" style="color:inherit;text-decoration:underline">{{ __('Quay lại đăng nhập') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
