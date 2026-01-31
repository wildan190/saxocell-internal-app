@extends('layouts.app')

@section('title', 'Overtime Records')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight italic">Overtime</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Manage and approve additional work hours.</p>
        </div>
        
        <button onclick="document.getElementById('createOtModal').classList.remove('hidden')" class="flex items-center gap-3 px-8 py-4 bg-slate-900 text-white rounded-[2rem] font-black transition-all hover:bg-black active:scale-95 shadow-xl shadow-slate-200">
            <i data-feather="clock" class="w-5 h-5"></i> Record Overtime
        </button>
    </div>

    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-xl overflow-hidden">
        <table class="w-full text-left font-sans">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Employee</th>
                    <th class="px-8 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Date</th>
                    <th class="px-8 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Hours</th>
                    <th class="px-8 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Total Pay</th>
                    <th class="px-8 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Status</th>
                    <th class="px-8 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($overtimes as $ot)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-8 py-6">
                        <span class="font-black text-slate-800 italic">{{ $ot->employee->full_name }}</span>
                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">{{ $ot->employee->position }}</p>
                    </td>
                    <td class="px-8 py-6 text-center text-xs font-bold text-slate-500">{{ $ot->date->format('M d, Y') }}</td>
                    <td class="px-8 py-6 text-center">
                        <span class="px-3 py-1 bg-slate-100 rounded-lg font-black text-xs text-slate-600">{{ number_format($ot->hours, 1) }}h</span>
                    </td>
                    <td class="px-8 py-6 text-right font-black text-slate-900 italic">Rp {{ number_format($ot->total_amount, 0, ',', '.') }}</td>
                    <td class="px-8 py-6 text-center">
                        @if($ot->status === 'pending')
                            <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-lg font-black text-[8px] uppercase tracking-wider">Pending</span>
                        @elseif($ot->status === 'approved')
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg font-black text-[8px] uppercase tracking-wider">Approved</span>
                        @else
                            <span class="px-3 py-1 bg-rose-50 text-rose-600 rounded-lg font-black text-[8px] uppercase tracking-wider">Rejected</span>
                        @endif
                    </td>
                    <td class="px-8 py-6 text-center">
                        @if($ot->status === 'pending')
                        <div class="flex items-center justify-center gap-2">
                            <form action="{{ route('hrm.overtime.approve', $ot->id) }}" method="POST">
                                @csrf
                                <button class="p-2 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-600 hover:text-white transition-all">
                                    <i data-feather="check" class="w-4 h-4"></i>
                                </button>
                            </form>
                            <form action="{{ route('hrm.overtime.reject', $ot->id) }}" method="POST">
                                @csrf
                                <button class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-600 hover:text-white transition-all">
                                    <i data-feather="x" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                        @else
                            <span class="text-[8px] font-black text-slate-300 uppercase tracking-tighter">Processed</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-20 text-center text-slate-400 font-bold italic">No overtime records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-8">
        {{ $overtimes->links() }}
    </div>
</div>

<!-- Modal -->
<div id="createOtModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-lg overflow-hidden border border-slate-200">
        <form action="{{ route('hrm.overtime.store') }}" method="POST" class="p-10 space-y-8">
            @csrf
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-black text-slate-900 italic underline decoration-blue-500 decoration-4">Record Extra Work</h3>
                <button type="button" onclick="document.getElementById('createOtModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-900 transition-colors">
                    <i data-feather="x"></i>
                </button>
            </div>

            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Employee</label>
                    <select name="employee_id" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 rounded-2xl font-bold outline-none appearance-none" required>
                        <option value="">-- Select Employee --</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Date</label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full px-6 py-3 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 rounded-xl font-bold outline-none" required>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Hours</label>
                        <input type="number" step="0.5" name="hours" placeholder="e.g. 2.5" class="w-full px-6 py-3 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 rounded-xl font-bold outline-none text-center" required>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Rate per Hour (IDR)</label>
                    <input type="number" name="rate_per_hour" placeholder="e.g. 50000" class="w-full px-6 py-3 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 rounded-xl font-bold outline-none" required>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Notes</label>
                    <textarea name="notes" rows="2" class="w-full px-6 py-3 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 rounded-xl font-bold outline-none" placeholder="Task performed..."></textarea>
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-xl font-bold text-sm uppercase tracking-wider hover:bg-blue-700 transition-all active:scale-95 shadow-xl shadow-blue-100">
                    Submit for Approval
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
