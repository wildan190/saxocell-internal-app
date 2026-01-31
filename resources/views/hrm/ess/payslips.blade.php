@extends('layouts.app')

@section('title', 'My Payslips')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Payslips</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Download and view your salary records.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($payrolls as $pr)
        <div class="bg-white p-10 rounded-[3rem] border border-slate-200 shadow-xl group hover:border-blue-500 transition-all">
            <div class="flex justify-between items-start mb-10">
                <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                    <i data-feather="file-text" class="w-8 h-8"></i>
                </div>
                <span class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-lg font-black text-[8px] uppercase tracking-wider">Released</span>
            </div>
            
            <h3 class="text-2xl font-black text-slate-900 mb-1">{{ date('F Y', mktime(0, 0, 0, $pr->month, 1, $pr->year)) }}</h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-8 italic">Monthly Salary Slip</p>
            
            <div class="space-y-4 pt-6 border-t border-slate-50 mb-8">
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Net Payable</span>
                    <span class="font-black text-slate-900 text-lg">Rp {{ number_format($pr->net_salary, 0, ',', '.') }}</span>
                </div>
            </div>

            <a href="{{ route('hrm.payroll.payslip', $pr->id) }}" class="flex items-center justify-center gap-3 w-full py-4 bg-slate-900 hover:bg-black text-white rounded-2xl font-black text-[10px] uppercase tracking-wider transition-all active:scale-95 shadow-lg shadow-slate-100 italic">
                View & Download Slip <i data-feather="download" class="w-3 h-3"></i>
            </a>
        </div>
        @empty
        <div class="col-span-full py-20 bg-white rounded-[3rem] border border-dashed border-slate-300 text-center">
            <div class="max-w-xs mx-auto">
                <i data-feather="file" class="w-12 h-12 text-slate-200 mx-auto mb-4"></i>
                <p class="text-slate-400 font-bold italic">No payslips have been issued to you yet.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
