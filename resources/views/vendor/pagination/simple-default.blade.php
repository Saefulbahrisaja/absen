@if ($paginator->hasPages())
    <div class="pagination">
        {{-- Tombol Sebelumnya --}}
        @if ($paginator->onFirstPage())
            <span>&laquo; Sebelumnya</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo; Sebelumnya</a>
        @endif

        {{-- Nomor Halaman --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span>{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span style="background:#1e40af; color:#fff; border-color:#1e40af;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Tombol Berikutnya --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next">Berikutnya &raquo;</a>
        @else
            <span>Berikutnya &raquo;</span>
        @endif
    </div>
@endif
