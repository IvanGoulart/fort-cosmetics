@if ($paginator->hasPages())
    <div class="flex justify-center mt-10">
        <div class="flex space-x-1">

            {{-- Botão Anterior --}}
            @if ($paginator->onFirstPage())
                <button disabled
                    class="rounded-md border border-slate-300 py-2 px-3 text-sm text-slate-400 cursor-not-allowed">
                    Prev
                </button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="rounded-md border border-slate-300 py-2 px-3 text-sm transition-all shadow-sm hover:shadow-lg text-slate-600 hover:text-white hover:bg-slate-800 hover:border-slate-800">
                    Prev
                </a>
            @endif

            {{-- Números das páginas --}}
            @foreach ($elements as $element)
                {{-- Reticências ("...") --}}
                @if (is_string($element))
                    <span
                        class="rounded-md border border-transparent py-2 px-3 text-sm text-slate-400 select-none">{{ $element }}</span>
                @endif

                {{-- Links das páginas --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span
                                class="min-w-9 rounded-md bg-slate-800 py-2 px-3 border border-transparent text-sm text-white shadow-md">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                                class="min-w-9 rounded-md border border-slate-300 py-2 px-3 text-sm transition-all shadow-sm hover:shadow-lg text-slate-600 hover:text-white hover:bg-slate-800 hover:border-slate-800">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Botão Próximo --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="rounded-md border border-slate-300 py-2 px-3 text-sm transition-all shadow-sm hover:shadow-lg text-slate-600 hover:text-white hover:bg-slate-800 hover:border-slate-800">
                    Next
                </a>
            @else
                <button disabled
                    class="rounded-md border border-slate-300 py-2 px-3 text-sm text-slate-400 cursor-not-allowed">
                    Next
                </button>
            @endif

        </div>
    </div>
@endif
