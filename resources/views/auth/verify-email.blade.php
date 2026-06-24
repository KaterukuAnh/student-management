@extends('layouts.guest')

@section('title', __('Xác minh email').' · Académie')

@section('content')
    <div class="login">
        <div class="login-form">
            <div class="login-card">
                <h1>{{ __('Xác minh email') }}</h1>
                <div class="ls">{{ __('Cảm ơn bạn đã đăng ký! Vui lòng xác minh email bằng liên kết chúng tôi đã gửi. Nếu chưa nhận được, bạn có thể yêu cầu gửi lại.') }}</div>

                @if (session('status') == 'verification-link-sent')
                    <div class="login-status" style="margin-top:18px">{{ __('Một liên kết xác minh mới đã được gửi đến email của bạn.') }}</div>
                @endif

                <div class="flex items-center justify-between" style="margin-top:22px">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <x-primary-button>{{ __('Gửi lại email xác minh') }}</x-primary-button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-ghost">{{ __('Đăng xuất') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
