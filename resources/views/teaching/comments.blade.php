@extends('layouts.app')

@section('content')
    <x-page-head crumb="{{ __('Giảng dạy').' · '.__('Nhận xét') }}" title="{{ __('Nhận xét học sinh') }}" />

    <div class="panel">
        <div class="empty">
            <div class="big">{{ __('Sắp ra mắt') }}</div>
            <div>{{ __('Tính năng nhận xét học sinh sẽ hiển thị tại đây trong một bản cập nhật sau.') }}</div>
        </div>
    </div>
@endsection
