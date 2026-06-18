@if ($paginator->hasPages())
    <nav class="d-flex align-items-center justify-content-between w-100 mt-4" style="width: 100% !important;">
        <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 w-100">
            
            <div class="small text-center text-sm-start text-muted mb-0">
                Menampilkan
                <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                sampai
                <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                dari
                <span class="fw-semibold">{{ $paginator->total() }}</span>
                hasil
            </div>

            <div>
                <ul class="pagination mb-0 justify-content-center">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                            <span class="page-link" aria-hidden="true">Prev</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">Prev</a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">Next</a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                            <span class="page-link" aria-hidden="true">Next</span>
                        </li>
                    @endif
                </ul>
            </div>

        </div>
    </nav>
@endif