@extends('layouts.app')

@section('title', 'Applicant Tracking')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Applicants</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Track and manage candidates through the hiring pipeline.</p>
        </div>
        
        <a href="{{ route('hrm.recruitment.index') }}" class="flex items-center gap-3 px-8 py-4 bg-white border border-slate-200 text-slate-600 rounded-[2rem] font-black transition-all hover:bg-slate-50 active:scale-95 shadow-lg">
            <i data-feather="briefcase" class="w-5 h-5"></i> View Job Postings
        </a>
    </div>

    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Candidate</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Applied For</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Applied On</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Current Status</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($applicants as $app)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-10 py-6">
                            <span class="font-black text-slate-800 text-lg">{{ $app->name }}</span>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $app->email }}</p>
                        </td>
                        <td class="px-10 py-6">
                            <span class="font-bold text-slate-600">{{ $app->jobPosting->title }}</span>
                        </td>
                        <td class="px-10 py-6 text-center">
                            <span class="font-bold text-slate-500 text-xs">{{ $app->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="px-10 py-6 text-center">
                            <form action="{{ route('hrm.applicants.update-status', $app->id) }}" method="POST" class="inline-block">
                                @csrf
                                <select name="status" onchange="this.form.submit()" class="px-4 py-2 bg-slate-100 border-0 rounded-xl font-black text-[10px] uppercase tracking-wider focus:ring-2 focus:ring-blue-500 outline-none">
                                    <option value="new" {{ $app->status === 'new' ? 'selected' : '' }}>New</option>
                                    <option value="shortlisted" {{ $app->status === 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                    <option value="interviewing" {{ $app->status === 'interviewing' ? 'selected' : '' }}>Interviewing</option>
                                    <option value="offered" {{ $app->status === 'offered' ? 'selected' : '' }}>Offered</option>
                                    <option value="hired" {{ $app->status === 'hired' ? 'selected' : '' }}>Hired</option>
                                    <option value="rejected" {{ $app->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-10 py-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ $app->resume_path ? asset('storage/' . $app->resume_path) : '#' }}" class="p-3 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl transition-all" target="_blank">
                                    <i data-feather="file-text" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-10 py-20 text-center text-slate-400 font-bold italic">No candidates in the pipeline yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($applicants->hasPages())
        <div class="px-10 py-8 border-t border-slate-50 bg-slate-50/30">
            {{ $applicants->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
