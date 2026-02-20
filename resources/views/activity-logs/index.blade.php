@extends('layouts.app')

@section('title', 'System Activity Logs')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <div class="page-header mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Activity Logs</h1>
            <p class="text-slate-500 mt-2 font-medium text-sm">Monitor system changes and user actions in real-time.</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm mb-6">
        <form action="{{ route('activity-logs.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i data-feather="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by action, user, or description..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 transition-colors text-sm">
            </div>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
                Filter Logs
            </button>
            @if(request()->has('search'))
                <a href="{{ route('activity-logs.index') }}" class="px-6 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-200 transition-all">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Details</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                                        {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <span class="font-semibold text-slate-700 text-sm">{{ $log->user->name ?? 'System' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $log->action == 'deleted' ? 'bg-rose-100 text-rose-600' : ($log->action == 'created' ? 'bg-emerald-100 text-emerald-600' : 'bg-blue-100 text-blue-600') }}">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-slate-600 font-medium">{{ $log->description }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($log->subject)
                                    <span class="text-xs font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded">
                                        {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400 italic">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <button type="button" class="text-slate-400 hover:text-blue-600 p-1 rounded-lg hover:bg-blue-50 transition-all" onclick="toggleDetails('{{ $log->id }}')">
                                    <i data-feather="info" class="w-4 h-4"></i>
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-700">{{ $log->created_at->format('M d, Y') }}</span>
                                    <span class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">{{ $log->created_at->format('H:i:s') }}</span>
                                </div>
                            </td>
                        </tr>
                        <tr id="details-{{ $log->id }}" class="hidden bg-slate-900">
                            <td colspan="6" class="p-6">
                                <div class="flex flex-col gap-4">
                                    <div>
                                        <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2">Metadata</h4>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                            <div>
                                                <span class="block text-[10px] text-slate-600 font-bold uppercase tracking-wider">IP Address</span>
                                                <span class="text-xs font-mono text-emerald-500">{{ $log->ip_address }}</span>
                                            </div>
                                            <div class="col-span-3">
                                                <span class="block text-[10px] text-slate-600 font-bold uppercase tracking-wider">User Agent</span>
                                                <span class="text-xs font-mono text-slate-400 truncate block">{{ $log->user_agent }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @if($log->properties)
                                    <div>
                                        <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2">Properties</h4>
                                        <pre class="bg-black/50 p-4 rounded-xl text-[10px] font-mono text-blue-300 overflow-x-auto">{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center">
                                        <i data-feather="database" class="w-8 h-8 text-slate-300"></i>
                                    </div>
                                    <div class="max-w-xs">
                                        <h3 class="text-lg font-bold text-slate-900">No logs found</h3>
                                        <p class="text-sm text-slate-500 mt-1">Try adjusting your filters or wait for more system activity.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function toggleDetails(id) {
        const row = document.getElementById(`details-${id}`);
        row.classList.toggle('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush
@endsection
