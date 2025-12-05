@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center justify-end gap-3">
        <p class="text-xs text-slate-500">
            Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}
        </p>

        <div class="inline-flex rounded-lg shadow-sm border border-slate-200 bg-white overflow-hidden">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1.5 text-xs text-slate-400 bg-slate-50 cursor-not-allowed">
                    Sebelumnya
                </span>
            @else
                <a
                    href="{{ $paginator->previousPageUrl() }}"
                    class="px-3 py-1.5 text-xs text-slate-600 hover:bg-slate-50"
                >
                    Sebelumnya
                </a>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a
                    href="{{ $paginator->nextPageUrl() }}"
                    class="px-3 py-1.5 text-xs text-slate-600 hover:bg-slate-50 border-l border-slate-200"
                >
                    Berikutnya
                </a>
            @else
                <span class="px-3 py-1.5 text-xs text-slate-400 bg-slate-50 border-l border-slate-200 cursor-not-allowed">
                    Berikutnya
                </span>
            @endif
        </div>
    </nav>
@endif

