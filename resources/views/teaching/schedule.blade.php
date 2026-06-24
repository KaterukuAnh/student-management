@extends('layouts.app')

@section('content')
    <x-page-head crumb="{{ __('Cá nhân').' · '.__('Thời khóa biểu') }}" title="{{ __('Thời khóa biểu') }}" />

    <div class="panel">
        <div class="empty">
            <div class="big">{{ __('Sắp ra mắt') }}</div>
            <div>{{ __('Thời khóa biểu sẽ hiển thị tại đây trong một bản cập nhật sau.') }}</div>
        </div>
    </div>
@endsection
