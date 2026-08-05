@extends('layouts.app')

@section('title', __('Quản lý tài khoản'))

@section('content')
    <x-page-head crumb="{{ __('Hệ thống').' · '.__('Tài khoản') }}" title="{{ __('Quản lý tài khoản') }}">
        <x-slot:actions>
            <a href="{{ route('users.create') }}" class="btn btn-primary"><span class="plus">+</span> {{ __('Thêm giáo viên') }}</a>
        </x-slot:actions>
    </x-page-head>

    @if (session('generated_password'))
        <div class="pwd-reveal" x-data="{ copied: false }">
            <div class="lbl">⚠ {{ __('Mật khẩu vừa tạo cho').' '.session('generated_user')['email'].' — '.__('chỉ hiển thị một lần này') }}</div>
            <div class="row">
                <code x-ref="pwd">{{ session('generated_password') }}</code>
                <button
                    type="button"
                    class="btn btn-sm btn-ghost"
                    @click="navigator.clipboard.writeText($refs.pwd.textContent); copied = true; setTimeout(() => copied = false, 1500)"
                >
                    <span x-text="copied ? '{{ __('Đã chép!') }}' : '{{ __('Chép mật khẩu') }}'"></span>
                </button>
            </div>
            <div class="note">{{ __('Hãy copy mật khẩu này và gửi cho') }} <strong>{{ session('generated_user')['name'] }}</strong> {{ __('qua email. Mật khẩu sẽ không hiển thị lại sau khi tải lại trang.') }}</div>
        </div>
    @endif

    <div class="panel">
        <div class="panel-h" style="padding:14px 22px">
            <form method="GET" action="{{ route('users.index') }}" x-data x-ref="searchForm" style="display:flex;align-items:center;gap:10px;width:100%">
                <div class="field" style="margin:0;flex:1;max-width:340px">
                    <input type="text" name="search" value="{{ $search }}" class="inp"
                           placeholder="{{ __('Tìm theo tên hoặc email…') }}"
                           @input.debounce.350ms="$refs.searchForm.submit()">
                </div>
                @if($search)
                    <a href="{{ route('users.index') }}" class="iconbtn">✕ {{ __('Xóa lọc') }}</a>
                @endif
            </form>
        </div>
        <table class="tbl">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ __('Họ tên') }}</th>
                    <th>Email</th>
                    <th>{{ __('Vai trò') }}</th>
                    <th>{{ __('Ngày tạo') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td class="id">{{ $user->id }}</td>
                        <td class="cell-name">
                            <x-avatar :name="$user->name" :size="34" />
                            <span class="nm">{{ $user->name }}</span>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge {{ $user->isAdmin() ? 'badge-cls' : 'badge-soft' }}">
                                {{ $user->isAdmin() ? __('Quản trị viên') : __('Giáo viên') }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="rowact">
                            <a href="{{ route('users.edit', $user->id) }}" class="iconbtn">{{ __('Sửa') }}</a>
                            @if ($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('{{ __('Xóa tài khoản này?') }}')" class="iconbtn danger">{{ __('Xóa') }}</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="empty"><div class="big">{{ $search ? __('Không tìm thấy kết quả phù hợp') : __('Chưa có tài khoản nào') }}</div></div></td></tr>
                @endforelse
            </tbody>
        </table>

        <x-pagination :paginator="$users" />
    </div>
@endsection
