@extends('layouts.app')

@section('title', 'Payslip - ' . $payroll->employee->full_name)

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20 p-8 md:p-12">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-12">
            <h1 class="text-4xl font-black text-slate-900 tracking-tight italic">Payslip Details</h1>
            <button onclick="window.print()" class="px-8 py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-wider flex items-center gap-2 hover:bg-black transition-all">
                <i data-feather="printer" class="w-4 h-4"></i> Print Slip
            </button>
        </div>

        <div class="bg-white rounded-[3rem] shadow-2xl border border-slate-200 overflow-hidden print:shadow-none print:border-slate-300">
            <!-- Header -->
            <div class="p-12 border-b-2 border-slate-50 flex justify-between items-start bg-slate-50/30">
                <div>
                    <h2 class="text-3xl font-black text-slate-900">SAXOCELL</h2>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-2">Professional Internal Systems</p>
                </div>
                <div class="text-right text-xs font-bold uppercase tracking-wider text-slate-400">
                    <p>Payslip ID: {{ strtoupper(substr($payroll->id, 0, 8)) }}</p>
                    <p class="mt-1">Period: {{ date('F Y', mktime(0, 0, 0, $payroll->month, 1, $payroll->year)) }}</p>
                </div>
            </div>

            <!-- Employee Detail -->
            <div class="p-12 border-b-2 border-slate-50 grid grid-cols-2 gap-12">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 block">Employee</span>
                    <h3 class="text-xl font-black text-slate-800">{{ $payroll->employee->full_name }}</h3>
                    <p class="text-slate-500 font-bold text-sm">{{ $payroll->employee->position }}</p>
                    <p class="text-slate-400 font-bold text-xs mt-1">{{ $payroll->employee->employee_id }}</p>
                </div>
                <div class="text-right">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 block">Department</span>
                    <h3 class="text-xl font-black text-slate-800">{{ $payroll->employee->department->name }}</h3>
                    <p class="text-emerald-500 font-black text-xs uppercase tracking-wider mt-2 px-4 py-2 bg-emerald-50 rounded-xl inline-block">{{ $payroll->status }}</p>
                </div>
            </div>

            <!-- Salary Breakdown -->
            <div class="p-12">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-8 border-b border-slate-100 pb-4">Salary Breakdown</h4>
                
                <div class="space-y-6">
                    <div class="flex justify-between items-center bg-slate-50 p-6 rounded-2xl">
                        <span class="font-bold text-slate-600">Basic Monthly Salary</span>
                        <span class="font-black text-slate-900">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center p-6 border-b border-slate-50">
                        <span class="font-bold text-slate-600 text-sm">Allowances</span>
                        <span class="font-black text-emerald-600">+ Rp {{ number_format($payroll->allowances, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center p-6 border-b border-slate-50">
                        <span class="font-bold text-slate-600 text-sm">Deductions</span>
                        <span class="font-black text-rose-600">- Rp {{ number_format($payroll->deductions, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Total -->
                <div class="mt-12 bg-slate-900 text-white p-10 rounded-[2.5rem] flex justify-between items-center shadow-2xl shadow-slate-200">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block opacity-70">Net Take Home Pay</span>
                        <p class="text-xs font-bold mt-1 opacity-50 italic">Calculated for the period ending {{ date('t F Y', mktime(0, 0, 0, $payroll->month, 1, $payroll->year)) }}</p>
                    </div>
                    <div class="text-3xl font-black italic">
                        Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="p-12 bg-slate-50/50 text-center">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">This is a system generated payslip and requires no physical signature.</p>
                <div class="flex justify-center gap-6 mt-6">
                    <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 flex items-center justify-center grayscale opacity-30">
                        <i data-feather="terminal" class="w-6 h-6 text-slate-900"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-12 flex justify-center">
            <a href="{{ route('hrm.payroll.index') }}" class="text-xs font-bold text-slate-400 uppercase tracking-[0.4em] hover:text-blue-600 transition-colors">Return to Payroll Management</a>
        </div>
    </div>
</div>

<style>
@media print {
    body { background: white !important; }
    .content-wrapper { padding: 0 !important; }
    .topbar, .sidebar, .sidebar-toggle, .menu-toggle, .print\:hidden, [class*="nav-"], header, footer, button, .mt-12.flex.justify-center { display: none !important; }
    .main-content { margin-left: 0 !important; padding: 0 !important; width: 100% !important; }
    .bg-slate-50\/50, .bg-slate-50\/30 { background: white !important; }
    .shadow-2xl { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
    .rounded-\[3rem\], .rounded-\[2\.5rem\] { border-radius: 0 !important; }
    .italic { font-style: normal !important; }
}
</style>
@endsection
