@extends('layouts.app')

@section('title', 'Salary Components')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight italic">Salary Components</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Manage master data for allowances and deductions.</p>
        </div>
        
        <button onclick="document.getElementById('createComponentModal').classList.remove('hidden')" class="flex items-center gap-3 px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-[2rem] font-black transition-all active:scale-95 shadow-xl shadow-blue-200">
            <i data-feather="plus" class="w-5 h-5"></i> Add Component
        </button>
    </div>

    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-xl overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Name</th>
                    <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Type</th>
                    <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Default Amount</th>
                    <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Status</th>
                    <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($components as $comp)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-10 py-6 font-black text-slate-800">{{ $comp->name }}</td>
                    <td class="px-10 py-6">
                        @if($comp->type === 'allowance')
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg font-black text-[8px] uppercase tracking-wider">Allowance (+)</span>
                        @else
                            <span class="px-3 py-1 bg-rose-50 text-rose-600 rounded-lg font-black text-[8px] uppercase tracking-wider">Deduction (-)</span>
                        @endif
                    </td>
                    <td class="px-10 py-6 text-right font-black text-slate-600">Rp {{ number_format($comp->default_amount, 0, ',', '.') }}</td>
                    <td class="px-10 py-6 text-center">
                        <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-lg font-black text-[8px] uppercase tracking-wider">{{ $comp->is_fixed ? 'Fixed' : 'Variable' }}</span>
                    </td>
                    <td class="px-10 py-6 text-center">
                        <form action="{{ route('hrm.salary-components.destroy', $comp->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="p-2 text-rose-400 hover:text-rose-600 transition-colors">
                                <i data-feather="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-10 py-20 text-center text-slate-400 font-bold italic">No components defined yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="createComponentModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-lg overflow-hidden">
        <form action="{{ route('hrm.salary-components.store') }}" method="POST" class="p-10 space-y-8">
            @csrf
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-black text-slate-900 italic">New Component</h3>
                <button type="button" onclick="document.getElementById('createComponentModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-900 transition-colors">
                    <i data-feather="x"></i>
                </button>
            </div>

            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Component Name</label>
                    <input type="text" name="name" placeholder="e.g. Transport Allowance" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 rounded-2xl font-bold outline-none" required>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Type</label>
                        <select name="type" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 rounded-2xl font-bold outline-none appearance-none" required>
                            <option value="allowance">Allowance (+)</option>
                            <option value="deduction">Deduction (-)</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Default Amount</label>
                        <input type="number" name="default_amount" value="0" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 rounded-2xl font-bold outline-none" required>
                    </div>
                </div>

                <div class="flex items-center gap-3 ml-1">
                    <input type="checkbox" name="is_fixed" id="is_fixed" value="1" checked class="w-5 h-5 rounded-lg border-2 border-slate-200 text-blue-600 focus:ring-blue-500">
                    <label for="is_fixed" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Fixed amount per month</label>
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full py-5 bg-slate-900 text-white rounded-2xl font-black text-sm uppercase tracking-wider hover:bg-black transition-all active:scale-95 italic">
                    Create Component
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
