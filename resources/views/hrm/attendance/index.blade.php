@extends('layouts.app')

@section('title', 'Attendance Tracking')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Attendance</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Monitor daily team presence and punctuality.</p>
        </div>
        
        <div class="flex gap-4">
            <a href="{{ route('hrm.attendance.report') }}" class="flex items-center gap-3 px-8 py-4 bg-white border border-slate-200 text-slate-600 rounded-[2rem] font-black transition-all hover:bg-slate-50 active:scale-95 shadow-lg">
                <i data-feather="file-text" class="w-5 h-5"></i> Monthly Report
            </a>
        </div>
    </div>

    <!-- Today's Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
        <div class="bg-white p-8 rounded-[3rem] border border-slate-200 shadow-xl">
            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Total Present</span>
            <div class="text-4xl font-black text-slate-900">{{ $attendances->total() }}</div>
        </div>
        <!-- More stats can be added here -->
    </div>

    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Employee</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Clock In</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Clock Out</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Status</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Location</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($attendances as $att)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-10 py-6">
                            <span class="font-black text-slate-800 text-lg">{{ $att->employee->full_name }}</span>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $att->employee->department->name }}</p>
                        </td>
                        <td class="px-10 py-6 text-center">
                            <span class="font-black text-slate-900">{{ $att->clock_in->format('H:i') }}</span>
                        </td>
                        <td class="px-10 py-6 text-center">
                            <span class="font-black text-slate-900">{{ $att->clock_out ? $att->clock_out->format('H:i') : '--:--' }}</span>
                        </td>
                        <td class="px-10 py-6 text-center">
                            @if($att->status === 'present')
                                <span class="px-4 py-2 bg-emerald-100 text-emerald-600 rounded-xl font-black text-xs uppercase tracking-wider">Present</span>
                            @elseif($att->status === 'late')
                                <span class="px-4 py-2 bg-amber-100 text-amber-600 rounded-xl font-black text-xs uppercase tracking-wider">Late</span>
                            @else
                                <span class="px-4 py-2 bg-rose-100 text-rose-600 rounded-xl font-black text-xs uppercase tracking-wider">{{ $att->status }}</span>
                            @endif
                        </td>
                        <td class="px-10 py-6">
                            <div class="flex items-center gap-2 text-slate-400 text-xs font-bold">
                                <i data-feather="map-pin" class="w-3 h-3"></i>
                                <span>{{ $att->lat_in ? round($att->lat_in, 4) . ', ' . round($att->long_in, 4) : 'GPS Restricted' }}</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-10 py-20 text-center text-slate-400 font-bold italic">No attendance records for today yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
