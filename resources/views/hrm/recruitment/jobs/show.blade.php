@extends('layouts.app')

@section('title', 'Manage Job - ' . $job->title)

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20 p-8 md:p-12">
    <div class="max-w-6xl mx-auto">
        <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <a href="{{ route('hrm.recruitment.index') }}" class="p-4 bg-white border border-slate-200 text-slate-400 rounded-2xl hover:text-slate-900 transition-colors">
                    <i data-feather="arrow-left" class="w-6 h-6"></i>
                </a>
                <div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tight italic">{{ $job->title }}</h1>
                    <p class="text-slate-500 font-bold uppercase tracking-wider text-[10px] mt-2 italic">{{ $job->department->name }} • Open Since {{ $job->created_at->format('M d, Y') }}</p>
                </div>
            </div>
            
            <div class="flex gap-4">
                <span class="px-6 py-4 bg-emerald-100 text-emerald-600 rounded-[2rem] font-black text-xs uppercase tracking-wider flex items-center gap-2">
                    <i data-feather="activity" class="w-4 h-4"></i> {{ strtoupper($job->status) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <!-- Job Details -->
            <div class="md:col-span-2 space-y-12">
                <div class="bg-white p-12 rounded-[4rem] border border-slate-200 shadow-xl space-y-12">
                    <section class="space-y-6">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-[0.4em] border-b border-slate-50 pb-4 italic">Description</h4>
                        <div class="text-slate-700 font-medium leading-relaxed whitespace-pre-wrap">{{ $job->description }}</div>
                    </section>
                    
                    <section class="space-y-6">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-[0.4em] border-b border-slate-50 pb-4 italic">Requirements</h4>
                        <div class="text-slate-700 font-medium leading-relaxed whitespace-pre-wrap">{{ $job->requirements }}</div>
                    </section>
                </div>

                <!-- Applicants for this job -->
                <div class="space-y-6">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight italic ml-4">Applicants for this Position</h3>
                    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-xl overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left font-sans">
                                <thead>
                                    <tr class="bg-slate-50/50">
                                        <th class="px-8 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Name</th>
                                        <th class="px-8 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Status</th>
                                        <th class="px-8 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($job->applicants as $app)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-8 py-6">
                                            <span class="font-black text-slate-800">{{ $app->name }}</span>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $app->email }}</p>
                                        </td>
                                        <td class="px-8 py-6 text-center">
                                            <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg font-black text-[8px] uppercase tracking-wider">{{ $app->status }}</span>
                                        </td>
                                        <td class="px-8 py-6 text-center">
                                            <a href="{{ route('hrm.applicants.index') }}" class="text-blue-600 font-black text-[10px] uppercase tracking-wider hover:underline italic">Details</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-8 py-12 text-center text-slate-400 font-bold italic">No one has applied for this position yet.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <div class="bg-slate-900 p-10 rounded-[3.5rem] text-white shadow-2xl">
                    <h4 class="text-xs font-bold text-white/40 uppercase tracking-[0.3em] mb-8 border-b border-white/10 pb-4 italic">Summary</h4>
                    <div class="space-y-8">
                        <div>
                            <span class="block text-xs font-bold text-white/30 uppercase tracking-wider mb-1">Total Applicants</span>
                            <p class="text-4xl font-black italic">{{ $job->applicants->count() }}</p>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-white/30 uppercase tracking-wider mb-1">Closing Date</span>
                            <p class="text-xl font-black italic">{{ $job->closing_date ? $job->closing_date->format('M d, Y') : 'Open Indefinitely' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-10 rounded-[3.5rem] border border-slate-200 shadow-xl">
                    <button class="w-full py-5 bg-rose-50 text-rose-600 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-rose-100 transition-all italic">
                        Close this Vacancy
                    </button>
                    <p class="text-[8px] font-bold text-slate-400 mt-6 text-center italic uppercase leading-relaxed">Closing a vacancy will prevent new applications but won't delete existing data.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
