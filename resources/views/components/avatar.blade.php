@props(['name', 'size' => 38])

@php
    $parts = preg_split('/\s+/', trim($name));
    $initials = mb_strtoupper((mb_substr($parts[count($parts) > 1 ? count($parts) - 2 : 0], 0, 1) ?? '') . (mb_substr(end($parts), 0, 1) ?? ''));
    $fontSize = round($size * 0.36);
@endphp

<span
    {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-full bg-accent-soft text-accent-deep font-bold']) }}
    style="width:{{ $size }}px;height:{{ $size }}px;min-width:{{ $size }}px;font-size:{{ $fontSize }}px;letter-spacing:.01em"
>{{ $initials }}</span>
