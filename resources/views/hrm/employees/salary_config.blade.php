@extends('layouts.app')

@section('title', 'Salary Config - ' . $employee->full_name)

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20 p-8 md:p-12">
    <div class="max-w-4xl mx-auto">
        <div class="mb-12 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ route('hrm.employees.index') }}" class="p-4 bg-white border border-slate-200 text-slate-400 rounded-2xl hover:text-slate-900 transition-colors">
                    <i data-feather="arrow-left" class="w-6 h-6"></i>
                </a>
                <div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tight italic">Recurring Pay Structure</h1>
                    <p class="text-slate-500 mt-2 font-bold uppercase tracking-wider text-[10px] italic">{{ $employee->full_name }} • {{ $employee->position }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[4rem] border border-slate-200 shadow-2xl overflow-hidden">
            <form action="{{ route('hrm.employees.salary-config.update', $employee->id) }}" method="POST" class="p-12 space-y-12">
                @csrf
                
                <div class="space-y-4 bg-slate-50 p-10 rounded-[3rem] border border-slate-100 flex justify-between items-center">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Base Monthly Salary</span>
                        <div class="text-2xl font-black italic text-slate-800">Rp {{ number_format($employee->base_salary, 0, ',', '.') }}</div>
                    </div>
                    <div class="text-right">
                        <a href="{{ route('hrm.employees.edit', $employee->id) }}" class="text-[8px] font-black text-blue-500 uppercase tracking-wider underline decoration-2 underline-offset-4">Change Base Salary</a>
                    </div>
                </div>

                <div class="space-y-8">
                    <h3 class="text-2xl font-black text-slate-900 italic border-b-4 border-emerald-500 inline-block">Monthly Allowances</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($components->where('type', 'allowance') as $comp)
                        <div class="p-8 bg-slate-50 rounded-[2.5rem] border border-slate-100 space-y-4">
                            <div class="flex justify-between items-start">
                                <span class="font-black text-slate-800 italic">{{ $comp->name }}</span>
                                <span class="text-[8px] font-black text-emerald-500 uppercase bg-emerald-50 px-2 py-1 rounded-lg">Allowance</span>
                            </div>
                            <div class="relative">
                                <span class="absolute left-6 top-1/2 -translate-y-1/2 font-black text-slate-300">Rp</span>
                                <input type="number" name="components[{{ $comp->id }}]" value="{{ (int)($employeeComponents[$comp->id] ?? 0) }}" class="w-full pl-14 pr-6 py-4 bg-white border-2 border-slate-100 focus:border-emerald-500 rounded-2xl font-black text-slate-700 outline-none" placeholder="0">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-8">
                    <h3 class="text-2xl font-black text-slate-900 italic border-b-4 border-rose-500 inline-block">Monthly Deductions</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($components->where('type', 'deduction') as $comp)
                        <div class="p-8 bg-slate-50 rounded-[2.5rem] border border-slate-100 space-y-4">
                            <div class="flex justify-between items-start">
                                <span class="font-black text-slate-800 italic">{{ $comp->name }}</span>
                                <span class="text-[8px] font-black text-rose-500 uppercase bg-rose-50 px-2 py-1 rounded-lg">Deduction</span>
                            </div>
                            <div class="relative">
                                <span class="absolute left-6 top-1/2 -translate-y-1/2 font-black text-slate-300">Rp</span>
                                <input type="number" name="components[{{ $comp->id }}]" value="{{ (int)($employeeComponents[$comp->id] ?? 0) }}" class="w-full pl-14 pr-6 py-4 bg-white border-2 border-slate-100 focus:border-rose-500 rounded-2xl font-black text-slate-700 outline-none" placeholder="0">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="pt-10">
                    <button type="submit" class="w-full py-6 bg-slate-900 hover:bg-black text-white rounded-[2.5rem] font-black text-xl shadow-2xl transition-all active:scale-[0.98] uppercase tracking-tighter italic flex items-center justify-center gap-3">
                        Save Pay Structure <i data-feather="shield"></i>
                    </button>
                    <p class="text-center text-[8px] font-bold text-slate-400 uppercase tracking-wider mt-6 italic opacity-50">These changes will apply to all future payroll generations for this employee.</p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
