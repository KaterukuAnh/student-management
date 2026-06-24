@extends('layouts.guest')

@section('title', __('Xác nhận mật khẩu').' · Académie')

@section('content')
    <div class="login">
        <div class="login-form">
            <div class="login-card">
                <h1>{{ __('Xác nhận mật khẩu') }}</h1>
                <div class="ls">{{ __('Đây là khu vực bảo mật. Vui lòng xác nhận mật khẩu trước khi tiếp tục.') }}</div>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <div class="login-fields">
                        <div class="field">
                            <x-input-label for="password" value="{{ __('Mật khẩu') }}" />
                            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('password')" />
                        </div>
                    </div>

                    <x-primary-button class="w-full justify-center" style="margin-top:22px;padding:14px">{{ __('Xác nhận') }}</x-primary-button>
                </form>
            </div>
        </div>
    </div>
@endsection
