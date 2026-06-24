@extends('layouts.app')

@section('title', __('Sửa tài khoản'))

@section('content')
    <x-page-head crumb="{{ __('Hệ thống').' · '.__('Tài khoản') }}" title="{{ __('Sửa tài khoản') }}" />

    <div class="panel" style="max-width:560px">
        <div class="modal-b" style="padding:24px">
            @if ($errors->any())
                <div class="login-error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="field full">
                        <label>{{ __('Họ tên') }}</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="inp">
                    </div>

                    <div class="field full">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="inp">
                    </div>

                    <div class="field full">
                        <label>{{ __('Vai trò') }}</label>
                        <input type="text" value="{{ $user->isAdmin() ? __('Quản trị viên') : __('Giáo viên') }}" class="inp" disabled>
                    </div>
                </div>

                <div class="flex gap-[10px]" style="margin-top:22px;justify-content:flex-end">
                    <a href="{{ route('users.index') }}" class="btn btn-ghost">{{ __('Hủy') }}</a>
                    <button type="submit" class="btn btn-accent">{{ __('Cập nhật') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
