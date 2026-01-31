@extends('layouts.app')

@section('title', 'Payroll Management')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Payroll</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Process salaries and manage financial integrations.</p>
        </div>
        
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('hrm.salary-components.index') }}" class="flex items-center gap-3 px-8 py-4 bg-white border border-slate-200 text-slate-600 rounded-[2rem] font-black transition-all hover:bg-slate-50 active:scale-95 shadow-lg">
                <i data-feather="settings" class="w-5 h-5"></i> Salary Components
            </a>
            <button onclick="document.getElementById('generateModal').classList.remove('hidden')" class="flex items-center gap-3 px-8 py-4 bg-slate-900 hover:bg-black text-white rounded-[2rem] font-black transition-all active:scale-95 shadow-xl">
                <i data-feather="refresh-ccw" class="w-5 h-5"></i> Generate Monthly Payroll
            </button>
        </div>
    </div>

    <!-- Payroll History -->
    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Employee</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Period</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Net Salary</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Status</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($payrolls as $pr)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-10 py-6">
                            <span class="font-black text-slate-800 text-lg">{{ $pr->employee->full_name }}</span>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $pr->employee->position }}</p>
                        </td>
                        <td class="px-10 py-6 text-center">
                            <span class="px-4 py-2 bg-slate-100 text-slate-500 rounded-xl font-black text-xs">
                                {{ date('F Y', mktime(0, 0, 0, $pr->month, 1, $pr->year)) }}
                            </span>
                        </td>
                        <td class="px-10 py-6 text-right font-black text-slate-900 text-xl">
                            RP {{ number_format($pr->net_salary, 0, ',', '.') }}
                        </td>
                        <td class="px-10 py-6 text-center">
                            @if($pr->status === 'paid')
                                <span class="px-4 py-2 bg-emerald-100 text-emerald-600 rounded-xl font-black text-xs uppercase tracking-wider">Paid</span>
                            @else
                                <span class="px-4 py-2 bg-amber-100 text-amber-600 rounded-xl font-black text-xs uppercase tracking-wider">Draft</span>
                            @endif
                        </td>
                        <td class="px-10 py-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('hrm.payroll.payslip', $pr->id) }}" class="p-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-all">
                                    <i data-feather="printer" class="w-4 h-4"></i>
                                </a>
                                @if($pr->status === 'draft')
                                <form action="{{ route('hrm.payroll.approve', $pr->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-black text-xs uppercase tracking-wider transition-all active:scale-95">
                                        <i data-feather="check-circle" class="w-4 h-4"></i> Approve & Pay
                                    </button>
                                </form>
                                @else
                                <span class="text-xs font-bold text-slate-400 uppercase">Reconciled</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-10 py-20 text-center text-slate-400 font-bold italic">No payroll records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Generate Modal -->
    <div id="generateModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-6">
        <div class="bg-white rounded-[3rem] shadow-2xl max-w-lg w-full overflow-hidden animate-in fade-in zoom-in duration-300">
            <div class="p-10 bg-slate-900 text-white flex justify-between items-start">
                <div>
                    <h3 class="text-3xl font-black tracking-tight italic">Generate Payroll</h3>
                    <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px] mt-2">Initialize salary records for the team</p>
                </div>
                <button onclick="document.getElementById('generateModal').classList.add('hidden')" class="p-2 hover:bg-white/10 rounded-xl transition-colors">
                    <i data-feather="x" class="w-6 h-6"></i>
                </button>
            </div>
            
            <form action="{{ route('hrm.payroll.generate') }}" method="POST" class="p-10 space-y-8">
                @csrf
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Month</label>
                        <select name="month" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl font-bold text-slate-800 outline-none focus:border-blue-500 transition-all">
                            @for($i=1; $i<=12; $i++)
                                <option value="{{ $i }}" {{ date('m') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Year</label>
                        <select name="year" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl font-bold text-slate-800 outline-none focus:border-blue-500 transition-all">
                            @for($y=date('Y'); $y>=date('Y')-2; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full py-5 bg-blue-600 hover:bg-blue-700 text-white rounded-[2rem] font-black text-lg transition-all active:scale-95 shadow-xl shadow-blue-200">
                        Process Generation
                    </button>
                    <p class="text-center text-xs font-bold text-slate-400 uppercase tracking-wider mt-6 italic">This will only create records for active employees</p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
