<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Académie · '.__('Quản lý học sinh'))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&family=Spectral:wght@500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="app theme-{{ auth()->user()->role }}">
        <aside class="sidebar">
            <div class="brand">
                <span class="mk"></span>
                <span class="nm">Académie<small>{{ __('Quản lý học sinh') }}</small></span>
            </div>

            <nav class="nav">
                @if (auth()->user()->isAdmin())
                    <div class="nav-sec">{{ __('Quản lý') }}</div>
                    <a href="{{ route('dashboard') }}" class="nav-i {{ request()->routeIs('dashboard') ? 'on' : '' }}"><span class="dot"></span>{{ __('Tổng quan') }}</a>
                    <a href="{{ route('students.index') }}" class="nav-i {{ request()->routeIs('students.*') ? 'on' : '' }}"><span class="dot"></span>{{ __('Học sinh') }}</a>
                    <a href="{{ route('classrooms.index') }}" class="nav-i {{ request()->routeIs('classrooms.*') ? 'on' : '' }}"><span class="dot"></span>{{ __('Lớp học') }}</a>
                    <a href="{{ route('subjects.index') }}" class="nav-i {{ request()->routeIs('subjects.*') ? 'on' : '' }}"><span class="dot"></span>{{ __('Môn học') }}</a>
                    <a href="{{ route('grades.index') }}" class="nav-i {{ request()->routeIs('grades.*') ? 'on' : '' }}"><span class="dot"></span>{{ __('Điểm số') }}</a>
                    <a href="{{ route('users.index') }}" class="nav-i {{ request()->routeIs('users.*') ? 'on' : '' }}"><span class="dot"></span>{{ __('Quản lý tài khoản') }}</a>
                @else
                    <div class="nav-sec">{{ __('Giảng dạy') }}</div>
                    <a href="{{ route('dashboard') }}" class="nav-i {{ request()->routeIs('dashboard') ? 'on' : '' }}"><span class="dot"></span>{{ __('Tổng quan') }}</a>
                    <a href="{{ route('grades.index') }}" class="nav-i {{ request()->routeIs('grades.*') ? 'on' : '' }}"><span class="dot"></span>{{ __('Nhập điểm') }}</a>
                    <a href="{{ route('schedule') }}" class="nav-i {{ request()->routeIs('schedule') ? 'on' : '' }}"><span class="dot"></span>{{ __('Thời khóa biểu') }}</a>
                    <a href="{{ route('comments') }}" class="nav-i {{ request()->routeIs('comments') ? 'on' : '' }}"><span class="dot"></span>{{ __('Nhận xét') }}</a>
                @endif
            </nav>

            <div class="sb-foot">
                <div class="sb-user">
                    <x-avatar :name="auth()->user()->name" :size="36" />
                    <div>
                        <div class="nm">{{ auth()->user()->name }}</div>
                        <div class="rl">{{ auth()->user()->isAdmin() ? __('Quản trị viên') : __('Giáo viên') }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="out" title="{{ __('Đăng xuất') }}">⏻</button>
                </form>
            </div>
        </aside>

        <div class="main">
            <div class="topbar">
                <div style="font-size:13.5px;color:var(--ink-soft)">
                    <span style="font-weight:600;color:var(--ink)">Académie</span>
                    <span style="margin:0 8px;color:var(--ink-faint)">·</span>2025–2026
                </div>
                <div class="topbar-r">
                    <x-lang-switcher />
                    <x-avatar :name="auth()->user()->name" :size="40" />
                </div>
            </div>

            <div class="content">
                @if (session('success'))
                    <div class="login-status" style="margin-bottom:24px">{{ session('success') }}</div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
