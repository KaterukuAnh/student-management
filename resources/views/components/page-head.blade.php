@props(['crumb' => null, 'title', 'sub' => null])

<div class="page-head">
    <div>
        @if ($crumb)
            <div class="crumb">{{ $crumb }}</div>
        @endif
        <div class="h1">{{ $title }}</div>
        @if ($sub)
            <div class="sub">{{ $sub }}</div>
        @endif
    </div>
    @isset($actions)
        <div class="flex gap-[10px]">{{ $actions }}</div>
    @endisset
</div>
