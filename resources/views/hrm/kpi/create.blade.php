@extends('layouts.app')

@section('title', 'New KPI Evaluation')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20 p-8 md:p-12">
    <div class="max-w-4xl mx-auto">
        <div class="mb-12">
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Record Evaluation</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Measure employee performance against defined standards.</p>
        </div>

        <div class="bg-white rounded-[3rem] border border-slate-200 shadow-2xl overflow-hidden">
            <form action="{{ route('hrm.evaluations.store') }}" method="POST" class="p-12 space-y-12">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Employee</label>
                        <select name="employee_id" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 rounded-2xl font-bold text-slate-800 transition-all outline-none appearance-none" required>
                            <option value="">-- Choose Employee --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Evaluation Period</label>
                        <input type="text" name="period_name" placeholder="e.g. Q1 2026 or January 2026" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 rounded-2xl font-bold text-slate-800 transition-all outline-none" required>
                    </div>
                </div>

                <div class="space-y-8">
                    <h3 class="text-xl font-black text-slate-900 border-b-4 border-amber-500 inline-block">Indicator Scoring</h3>
                    
                    <div class="space-y-6">
                        @foreach($indicators as $indicator)
                        <div class="flex flex-col md:flex-row md:items-center justify-between p-6 bg-slate-50 rounded-3xl border border-slate-100 gap-6">
                            <div class="flex-1">
                                <span class="block font-black text-slate-800">{{ $indicator->name }}</span>
                                <p class="text-xs text-slate-400 font-bold tracking-tight">{{ $indicator->description }} (Weight: {{ $indicator->weight }}%)</p>
                            </div>
                            <div class="w-full md:w-32">
                                <input type="number" name="scores[{{ $indicator->id }}]" min="0" max="100" placeholder="0-100" class="w-full px-6 py-4 bg-white border-2 border-slate-200 focus:border-blue-500 rounded-2xl font-black text-slate-800 outline-none text-center" required>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">General Feedback</label>
                    <textarea name="feedback" rows="4" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 rounded-2xl font-bold text-slate-800 transition-all outline-none" placeholder="Manager comments on overall performance..."></textarea>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full py-6 bg-slate-900 hover:bg-black text-white rounded-[2.5rem] font-black text-xl shadow-2xl transition-all active:scale-[0.98] uppercase tracking-tighter italic flex items-center justify-center gap-3">
                        Finalize Evaluation <i data-feather="check-circle"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
