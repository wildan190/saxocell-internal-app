@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col md:flex-row items-center justify-between gap-4 mt-8 px-6 py-4 bg-white/80 backdrop-blur-md rounded-3xl border border-white shadow-xl shadow-slate-200/50">
        {{-- Info Section --}}
        <div class="text-sm text-slate-500 font-medium">
            <p>
                {!! __('Showing') !!}
                <strong class="text-slate-900 font-bold">{{ $paginator->firstItem() }}</strong>
                {!! __('to') !!}
                <strong class="text-slate-900 font-bold">{{ $paginator->lastItem() }}</strong>
                {!! __('of') !!}
                <strong class="text-slate-900 font-bold">{{ $paginator->total() }}</strong>
                {!! __('results') !!}
            </p>
        </div>

        {{-- Links Section --}}
        <div class="flex items-center gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="flex items-center justify-center min-w-[40px] h-10 px-3 rounded-xl border border-slate-100 bg-slate-50 text-slate-400 opacity-50 cursor-not-allowed" aria-disabled="true">
                    <i data-feather="chevron-left" class="w-4 h-4"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="flex items-center justify-center min-w-[40px] h-10 px-3 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-600 transition-all duration-300 no-underline cursor-pointer hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 hover:shadow-lg hover:shadow-blue-500/10 hover:-translate-y-0.5" aria-label="{{ __('pagination.previous') }}">
                    <i data-feather="chevron-left" class="w-4 h-4"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="flex items-center justify-center min-w-0 h-10 px-1 text-slate-400 cursor-default" aria-disabled="true">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="flex items-center justify-center min-w-[40px] h-10 px-3 rounded-xl border border-blue-600 text-white text-sm font-semibold shadow-xl shadow-blue-500/30 active animate-none" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="flex items-center justify-center min-w-[40px] h-10 px-3 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-600 transition-all duration-300 no-underline cursor-pointer hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 hover:shadow-lg hover:shadow-blue-500/10 hover:-translate-y-0.5" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="flex items-center justify-center min-w-[40px] h-10 px-3 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-600 transition-all duration-300 no-underline cursor-pointer hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 hover:shadow-lg hover:shadow-blue-500/10 hover:-translate-y-0.5" aria-label="{{ __('pagination.next') }}">
                    <i data-feather="chevron-right" class="w-4 h-4"></i>
                </a>
            @else
                <span class="flex items-center justify-center min-w-[40px] h-10 px-3 rounded-xl border border-slate-100 bg-slate-50 text-slate-400 opacity-50 cursor-not-allowed" aria-disabled="true">
                    <i data-feather="chevron-right" class="w-4 h-4"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
