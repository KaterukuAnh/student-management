@extends('layouts.app')

@section('title', __('Hồ sơ cá nhân'))

@section('content')
    <x-page-head crumb="{{ __('Cá nhân') }}" title="{{ __('Hồ sơ cá nhân') }}" />

    <div class="flex flex-col gap-[18px]" style="max-width:560px">
        <div class="panel" style="padding:24px">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="panel" style="padding:24px">
            @include('profile.partials.update-password-form')
        </div>

        <div class="panel" style="padding:24px">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
@endsection
