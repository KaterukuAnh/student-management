@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'login-status']) }}>
        {{ $status }}
    </div>
@endif
