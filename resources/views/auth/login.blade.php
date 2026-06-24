@extends('layouts.guest')

@section('title', __('Đăng nhập').' · Académie')

@section('content')
    <div class="login">
        <div class="login-art">
            <div class="mk"></div>
            <h2>{{ __("Một mái trường,\nmột hệ thống gọn gàng.") }}</h2>
            <p>{{ __('Quản lý hồ sơ học sinh, phân lớp, điểm số và thời khóa biểu — tất cả trong một không gian thanh lịch.') }}</p>
            <div class="foot">ACADÉMIE · 2025–2026</div>
            <div class="glow"></div>
        </div>

        <div class="login-form">
            <div class="login-card">
                <x-lang-switcher />

                <h1>{{ __('Chào mừng trở lại') }}</h1>
                <div class="ls">{{ __('Đăng nhập hệ thống quản lý học sinh') }}</div>

                @if (session('status'))
                    <div class="login-status" style="margin-top:20px">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="login-error" style="margin-top:20px">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="login-fields">
                        <div class="field">
                            <label for="email">{{ __('Email') }}</label>
                            <input id="email" class="inp" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                        </div>

                        <div class="field">
                            <label for="password">{{ __('Mật khẩu') }}</label>
                            <input id="password" class="inp" type="password" name="password" required autocomplete="current-password">
                        </div>

                        <label style="display:flex;align-items:center;gap:8px;font-size:13.5px;color:var(--ink-soft)">
                            <input type="checkbox" name="remember">
                            {{ __('Ghi nhớ đăng nhập') }}
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('Đăng nhập') }}</button>
                </form>

                <div class="login-demo">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" style="color:inherit;text-decoration:underline">{{ __('Quên mật khẩu?') }}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
