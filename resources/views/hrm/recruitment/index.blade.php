@extends('layouts.app')

@section('title', 'Recruitment Hub')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Recruitment</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Manage job postings and evaluate talent.</p>
        </div>
        
        <div class="flex gap-4">
            <a href="{{ route('hrm.applicants.index') }}" class="flex items-center gap-3 px-8 py-4 bg-white border border-slate-200 text-slate-600 rounded-[2rem] font-black transition-all hover:bg-slate-50 active:scale-95 shadow-lg">
                <i data-feather="users" class="w-5 h-5"></i> View All Applicants
            </a>
            <a href="{{ route('hrm.jobs.create') }}" class="flex items-center gap-3 px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-[2rem] font-black transition-all active:scale-95 shadow-xl shadow-blue-200">
                <i data-feather="plus" class="w-5 h-5"></i> Create Job Posting
            </a>
        </div>
    </div>

    <!-- Active Jobs -->
    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-xl overflow-hidden">
        <div class="px-10 py-8 border-b border-slate-100">
            <h3 class="text-xl font-black text-slate-900 tracking-tight">Open Vacancies</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Position Title</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Department</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Applicants</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Closing Date</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Status</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($jobs as $job)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-10 py-6">
                            <span class="font-black text-slate-800 text-lg">{{ $job->title }}</span>
                        </td>
                        <td class="px-10 py-6">
                            <span class="px-4 py-2 bg-blue-50 text-blue-600 rounded-xl font-black text-[10px] uppercase tracking-wider">
                                {{ $job->department->name }}
                            </span>
                        </td>
                        <td class="px-10 py-6 text-center">
                            <span class="w-10 h-10 bg-slate-100 rounded-full inline-flex items-center justify-center font-black text-slate-600">{{ $job->applicants_count }}</span>
                        </td>
                        <td class="px-10 py-6 text-center">
                            <span class="font-bold text-slate-500 text-xs">{{ $job->closing_date ? $job->closing_date->format('M d, Y') : 'N/A' }}</span>
                        </td>
                        <td class="px-10 py-6 text-center">
                            @if($job->status === 'open')
                                <span class="px-4 py-2 bg-emerald-100 text-emerald-600 rounded-xl font-black text-xs uppercase tracking-wider">Open</span>
                            @else
                                <span class="px-4 py-2 bg-rose-100 text-rose-600 rounded-xl font-black text-xs uppercase tracking-wider">{{ $job->status }}</span>
                            @endif
                        </td>
                        <td class="px-10 py-6 text-center">
                            <a href="{{ route('hrm.jobs.show', $job->id) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 hover:bg-black text-white rounded-xl font-black text-xs uppercase tracking-wider transition-all active:scale-95">
                                <i data-feather="eye" class="w-4 h-4"></i> Manage
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-10 py-20 text-center text-slate-400 font-bold italic">No job postings found. Click "Create" to start.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($jobs->hasPages())
        <div class="px-10 py-8 border-t border-slate-50 bg-slate-50/30">
            {{ $jobs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
