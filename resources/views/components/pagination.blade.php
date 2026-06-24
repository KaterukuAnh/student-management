@props(['paginator'])

@if ($paginator->hasPages())
    <div class="panel-f">
        <div class="ct">
            {{ __('Hiển thị') }} {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} {{ __('trong tổng') }} {{ $paginator->total() }}
        </div>
        <div class="pager">
            @if ($paginator->onFirstPage())
                <span class="btn btn-ghost btn-sm pg-nav" aria-disabled="true">‹</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-ghost btn-sm pg-nav">‹</a>
            @endif

            @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                <a href="{{ $url }}" class="btn btn-sm pg-num {{ $page == $paginator->currentPage() ? 'active' : 'btn-ghost' }}">{{ $page }}</a>
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-ghost btn-sm pg-nav">›</a>
            @else
                <span class="btn btn-ghost btn-sm pg-nav" aria-disabled="true">›</span>
            @endif
        </div>
    </div>
@endif
