@extends('layouts.app')

@section('title', __('Thêm tài khoản'))

@section('content')
    <x-page-head crumb="{{ __('Hệ thống').' · '.__('Tài khoản') }}" title="{{ __('Thêm giáo viên mới') }}" />

    <div class="panel" style="max-width:560px">
        <div class="modal-b" style="padding:24px">
            @if ($errors->any())
                <div class="login-error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <div class="form-grid">
                    <div class="field full">
                        <label>{{ __('Họ tên') }}</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="inp">
                    </div>

                    <div class="field full">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="inp">
                    </div>

                    <div class="field full">
                        <label>{{ __('Vai trò') }}</label>
                        <input type="text" value="{{ __('Giáo viên') }}" class="inp" disabled>
                    </div>
                </div>

                <div style="margin-top:14px;font-size:12.5px;color:var(--ink-soft)">
                    {{ __('Mật khẩu sẽ được hệ thống tự sinh ngẫu nhiên và hiển thị một lần ngay sau khi tạo — hãy copy và gửi cho giáo viên qua email.') }}
                </div>

                <div class="flex gap-[10px]" style="margin-top:22px;justify-content:flex-end">
                    <a href="{{ route('users.index') }}" class="btn btn-ghost">{{ __('Hủy') }}</a>
                    <button type="submit" class="btn btn-accent">{{ __('Tạo tài khoản') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection