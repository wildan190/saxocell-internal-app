<div {{ $attributes->merge(['class' => 'card']) }}>
    @if(isset($header))
        <div class="card-header border-b border-gray-100 px-6 py-4">
            <h3 class="text-lg font-semibold text-slate-800">{{ $header }}</h3>
            @if(isset($headerActions))
                <div class="card-actions">
                    {{ $headerActions }}
                </div>
            @endif
        </div>
    @endif
    
    <div class="card-body p-6">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="card-footer border-t border-gray-100 px-6 py-4 bg-slate-50/50 rounded-b-[2rem]">
            {{ $footer }}
        </div>
    @endif
</div>
