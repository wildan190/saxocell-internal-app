<div class="table-container overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table {{ $attributes->merge(['class' => 'table w-full']) }}>
            @if(isset($thead))
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        {{ $thead }}
                    </tr>
                </thead>
            @endif
            <tbody class="divide-y divide-slate-100">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
