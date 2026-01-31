@extends('layouts.app')

@section('title', 'My Attendance')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Attendance History</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Your personal daily presence logs.</p>
        </div>
        
        <div class="flex flex-wrap gap-4">
            <form action="{{ route('hrm.attendance.clock-in') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-[2rem] font-black transition-all active:scale-95 shadow-xl shadow-emerald-200">
                    <i data-feather="log-in" class="w-5 h-5"></i> Clock In Now
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Date</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Clock In</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Clock Out</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Status</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($attendances as $att)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-10 py-6">
                            <span class="font-black text-slate-800">{{ $att->date->format('l, d M Y') }}</span>
                        </td>
                        <td class="px-10 py-6 text-center">
                            <span class="font-black text-slate-900">{{ $att->clock_in->format('H:i') }}</span>
                        </td>
                        <td class="px-10 py-6 text-center">
                            <span class="font-black text-slate-900">{{ $att->clock_out ? $att->clock_out->format('H:i') : '--:--' }}</span>
                        </td>
                        <td class="px-10 py-6 text-center">
                            @if($att->status === 'present')
                                <span class="px-4 py-2 bg-emerald-100 text-emerald-600 rounded-xl font-black text-[10px] uppercase tracking-wider">Present</span>
                            @elseif($att->status === 'late')
                                <span class="px-4 py-2 bg-amber-100 text-amber-600 rounded-xl font-black text-[10px] uppercase tracking-wider">Late</span>
                            @else
                                <span class="px-4 py-2 bg-slate-100 text-slate-500 rounded-xl font-black text-[10px] uppercase tracking-wider">{{ $att->status }}</span>
                            @endif
                        </td>
                        <td class="px-10 py-6 italic text-slate-400 text-xs text-xs font-bold">
                            {{ $att->notes ?? 'Auto-logged via ESS' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-10 py-20 text-center text-slate-400 font-bold italic">No attendance records found yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($attendances->hasPages())
        <div class="px-10 py-8 border-t border-slate-50">
            {{ $attendances->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
