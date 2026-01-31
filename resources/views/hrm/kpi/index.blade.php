@extends('layouts.app')

@section('title', 'KPI Evaluations')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Performance</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Evaluate and monitor employee performance metrics.</p>
        </div>
        
        <div class="flex gap-4">
            <a href="{{ route('hrm.kpi.indicators') }}" class="flex items-center gap-3 px-8 py-4 bg-white border border-slate-200 text-slate-600 rounded-[2rem] font-black transition-all hover:bg-slate-50 active:scale-95 shadow-lg">
                <i data-feather="settings" class="w-5 h-5"></i> Manage Indicators
            </a>
            <a href="{{ route('hrm.evaluations.create') }}" class="flex items-center gap-3 px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-[2rem] font-black transition-all active:scale-95 shadow-xl shadow-blue-200">
                <i data-feather="plus" class="w-5 h-5"></i> New Evaluation
            </a>
        </div>
    </div>

    <!-- Evaluations Table -->
    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Employee</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Period</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Score</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Evaluator</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Status</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($evaluations as $ev)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-10 py-6">
                            <span class="font-black text-slate-800 text-lg">{{ $ev->employee->full_name }}</span>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $ev->employee->department->name }}</p>
                        </td>
                        <td class="px-10 py-6 text-center">
                            <span class="px-4 py-2 bg-slate-100 text-slate-500 rounded-xl font-black text-xs">{{ $ev->period_name }}</span>
                        </td>
                        <td class="px-10 py-6 text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-2xl font-black {{ $ev->total_score >= 80 ? 'text-emerald-600' : ($ev->total_score >= 60 ? 'text-amber-600' : 'text-rose-600') }}">
                                    {{ number_format($ev->total_score, 1) }}%
                                </span>
                                <div class="w-16 h-1.5 bg-slate-100 rounded-full mt-1 overflow-hidden">
                                    <div class="h-full {{ $ev->total_score >= 80 ? 'bg-emerald-500' : ($ev->total_score >= 60 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ $ev->total_score }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-10 py-6">
                            <span class="font-bold text-slate-600 text-xs">{{ $ev->evaluator->name }}</span>
                        </td>
                        <td class="px-10 py-6 text-center">
                            <span class="px-4 py-2 bg-emerald-100 text-emerald-600 rounded-xl font-black text-[10px] uppercase tracking-wider">{{ $ev->status }}</span>
                        </td>
                        <td class="px-10 py-6 text-center">
                            <a href="{{ route('hrm.evaluations.show', $ev->id) }}" class="p-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-all inline-block">
                                <i data-feather="eye" class="w-4 h-4"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-10 py-20 text-center text-slate-400 font-bold italic">No performance evaluations recorded yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($evaluations->hasPages())
        <div class="px-10 py-8 border-t border-slate-50 bg-slate-50/30">
            {{ $evaluations->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
