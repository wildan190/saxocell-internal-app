@props(['title', 'subtitle' => null])

<div class="page-header flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
    <div>
        <h1 class="page-title text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">{{ $title }}</h1>
        @if($subtitle)
            <p class="page-subtitle text-slate-500 mt-1">{{ $subtitle }}</p>
        @endif
    </div>
    
    @if(isset($actions))
        <div class="flex items-center gap-3">
            {{ $actions }}
        </div>
    @endif
</div>
