@php
    $labels = ['vi' => 'VI', 'en' => 'EN', 'ja' => '日本'];
@endphp

<div {{ $attributes->merge(['class' => 'lang']) }}>
    @foreach ($labels as $code => $label)
        <a href="{{ route('lang.switch', $code) }}" class="{{ app()->getLocale() === $code ? 'on' : '' }}">{{ $label }}</a>
    @endforeach
</div>
