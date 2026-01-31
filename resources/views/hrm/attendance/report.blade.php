@extends('layouts.app')

@section('title', 'Attendance Report')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Attendance Report</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Monthly summary of team presence and punctuality.</p>
        </div>
        
        <form action="{{ route('hrm.attendance.report') }}" method="GET" class="flex gap-4">
            <select name="month" class="px-6 py-4 bg-white border border-slate-200 rounded-2xl font-bold text-slate-700 outline-none">
                @for($i=1; $i<=12; $i++)
                    <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                @endfor
            </select>
            <select name="year" class="px-6 py-4 bg-white border border-slate-200 rounded-2xl font-bold text-slate-700 outline-none">
                @for($y=date('Y'); $y>=date('Y')-2; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="px-8 py-4 bg-slate-900 text-white rounded-2xl font-black transition-all hover:bg-black active:scale-95">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Employee</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Present</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Late</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Absent</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Punctuality Rate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($employees as $emp)
                    @php
                        $present = $emp->attendances->where('status', 'present')->count();
                        $late = $emp->attendances->where('status', 'late')->count();
                        $total = $present + $late;
                        $rate = $total > 0 ? ($present / $total) * 100 : 0;
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-10 py-6">
                            <span class="font-black text-slate-800">{{ $emp->full_name }}</span>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $emp->position }}</p>
                        </td>
                        <td class="px-10 py-6 text-center">
                            <span class="font-black text-emerald-600">{{ $present }}</span>
                        </td>
                        <td class="px-10 py-6 text-center">
                            <span class="font-black text-amber-600">{{ $late }}</span>
                        </td>
                        <td class="px-10 py-6 text-center text-slate-300 font-bold italic">--</td>
                        <td class="px-10 py-6 text-center">
                            <span class="px-4 py-2 bg-slate-100 rounded-xl font-black text-xs {{ $rate >= 90 ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ number_format($rate, 1) }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
