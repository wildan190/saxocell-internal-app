@extends('layouts.app')

@section('title', 'Employee Self Service')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div class="flex items-center gap-6">
            <div class="w-24 h-24 rounded-[2rem] overflow-hidden bg-white shadow-xl border-4 border-white">
                @if($employee->profile_picture)
                    <img src="{{ asset('storage/' . $employee->profile_picture) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-blue-600 text-white">
                        <span class="text-3xl font-black">{{ substr($employee->first_name, 0, 1) }}</span>
                    </div>
                @endif
            </div>
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight">Halo, {{ $employee->first_name }}!</h1>
                <p class="text-slate-500 font-bold uppercase tracking-wider text-xs mt-1">{{ $employee->position }} • {{ $employee->department->name }}</p>
            </div>
        </div>
        
        <div class="flex flex-wrap gap-4">
            <form action="{{ route('hrm.attendance.clock-in') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-[2rem] font-black transition-all active:scale-95 shadow-xl shadow-emerald-200">
                    <i data-feather="log-in" class="w-5 h-5"></i> Clock In
                </button>
            </form>
            <form action="{{ route('hrm.attendance.clock-out') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-8 py-4 bg-rose-600 hover:bg-rose-700 text-white rounded-[2rem] font-black transition-all active:scale-95 shadow-xl shadow-rose-200">
                    <i data-feather="log-out" class="w-5 h-5"></i> Clock Out
                </button>
            </form>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <div class="bg-white p-8 rounded-[3rem] border border-slate-200 shadow-xl relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full blur-2xl group-hover:bg-blue-100 transition-colors"></div>
            <span class="block text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mb-4">Today's Attendance</span>
            @if($latestAttendance && $latestAttendance->date->isToday())
                <div class="flex items-end gap-3">
                    <span class="text-4xl font-black text-slate-900">{{ $latestAttendance->clock_in->format('H:i') }}</span>
                    <span class="text-xs font-black text-emerald-500 uppercase mb-2 tracking-wider">{{ $latestAttendance->status }}</span>
                </div>
                <p class="text-slate-400 text-xs mt-3 font-bold italic">{{ $latestAttendance->clock_out ? 'Clocked out at ' . $latestAttendance->clock_out->format('H:i') : 'Still working...' }}</p>
            @else
                <div class="text-4xl font-black text-slate-300 italic">--:--</div>
                <p class="text-slate-400 text-xs mt-3 font-bold tracking-wider uppercase">Not clocked in yet</p>
            @endif
        </div>

        <div class="bg-white p-8 rounded-[3rem] border border-slate-200 shadow-xl relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full blur-2xl"></div>
            <span class="block text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mb-4">Last Net Salary</span>
            @if($recentPayrolls->first())
                <div class="text-4xl font-black text-slate-900">Rp {{ number_format($recentPayrolls->first()->net_salary, 0, ',', '.') }}</div>
                <p class="text-slate-400 text-xs mt-3 font-bold tracking-wider uppercase">{{ date('F Y', mktime(0, 0, 0, $recentPayrolls->first()->month, 1, $recentPayrolls->first()->year)) }}</p>
            @else
                <div class="text-4xl font-black text-slate-300 italic">No Data</div>
                <p class="text-slate-400 text-xs mt-3 font-bold tracking-wider uppercase">Pending generation</p>
            @endif
        </div>

        <div class="bg-white p-8 rounded-[3rem] border border-slate-200 shadow-xl relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 rounded-full blur-2xl"></div>
            <span class="block text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mb-4">Performance Score</span>
            @if($latestKpi)
                <div class="text-4xl font-black text-slate-900">{{ number_format($latestKpi->total_score, 1) }}%</div>
                <p class="text-slate-400 text-xs mt-3 font-bold tracking-wider uppercase">{{ $latestKpi->period_name }}</p>
            @else
                <div class="text-4xl font-black text-slate-300 italic">N/A</div>
                <p class="text-slate-400 text-xs mt-3 font-bold tracking-wider uppercase">No evaluation yet</p>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
        <div class="space-y-6">
            <h3 class="text-2xl font-black text-slate-900 tracking-tight ml-4 flex items-center gap-3">
                <i data-feather="file-text" class="w-6 h-6 text-blue-600"></i> Recent Payslips
            </h3>
            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-lg overflow-hidden">
                <div class="divide-y divide-slate-50">
                    @forelse($recentPayrolls as $pr)
                    <a href="{{ route('hrm.payroll.payslip', $pr->id) }}" class="flex items-center justify-between p-8 hover:bg-slate-50 transition-colors group">
                        <div class="flex items-center gap-6">
                            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all">
                                <i data-feather="download-cloud" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <span class="font-black text-slate-800 text-lg">{{ date('F Y', mktime(0, 0, 0, $pr->month, 1, $pr->year)) }}</span>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $pr->status }}</p>
                            </div>
                        </div>
                        <span class="font-black text-slate-900">Rp {{ number_format($pr->net_salary, 0, ',', '.') }}</span>
                    </a>
                    @empty
                    <div class="p-12 text-center text-slate-400 font-bold italic">No payslips available.</div>
                    @endforelse
                </div>
                <a href="{{ route('hrm.ess.payslips') }}" class="block p-6 text-center bg-slate-50 text-xs font-bold text-blue-600 uppercase tracking-[0.3em] hover:bg-slate-100 transition-colors">View All Payslips</a>
            </div>
        </div>

        <div class="space-y-6">
            <h3 class="text-2xl font-black text-slate-900 tracking-tight ml-4 flex items-center gap-3">
                <i data-feather="calendar" class="w-6 h-6 text-emerald-600"></i> My Information
            </h3>
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-lg">
                <div class="space-y-8">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500">
                            <i data-feather="mail" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Email</span>
                            <span class="font-bold text-slate-800">{{ $employee->email }}</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500">
                            <i data-feather="phone" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Phone</span>
                            <span class="font-bold text-slate-800">{{ $employee->phone ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500">
                            <i data-feather="map-pin" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Address</span>
                            <span class="font-bold text-slate-800">{{ $employee->address ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-10 pt-8 border-t border-slate-100">
                    <a href="{{ route('hrm.ess.profile') }}" class="flex items-center justify-center gap-3 w-full py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-black text-xs uppercase tracking-wider transition-all active:scale-95">
                        <i data-feather="user" class="w-4 h-4"></i> Update Profile Details
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
