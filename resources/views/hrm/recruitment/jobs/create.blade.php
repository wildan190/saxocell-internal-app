@extends('layouts.app')

@section('title', 'Create Job Posting')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20 p-8 md:p-12">
    <div class="max-w-4xl mx-auto">
        <div class="mb-12">
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Post a New Job</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Define the role and requirements for your next team member.</p>
        </div>

        <div class="bg-white rounded-[3rem] border border-slate-200 shadow-2xl overflow-hidden">
            <form action="{{ route('hrm.jobs.store') }}" method="POST" class="p-12 space-y-10">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Job Title</label>
                        <input type="text" name="title" placeholder="e.g. Senior Backend Developer" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none" required>
                    </div>
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Department</label>
                        <select name="department_id" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none appearance-none" required>
                            <option value="">-- Choose Dept --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Closing Date (Optional)</label>
                    <input type="date" name="closing_date" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none">
                </div>

                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Job Description</label>
                    <textarea name="description" rows="6" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none" placeholder="Primary responsibilities and daily tasks..."></textarea>
                </div>

                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Requirements</label>
                    <textarea name="requirements" rows="6" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none" placeholder="Skills, education, and experience needed..."></textarea>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full py-6 bg-blue-600 hover:bg-blue-700 text-white rounded-[2.5rem] font-black text-xl shadow-2xl transition-all active:scale-[0.98] uppercase tracking-tighter italic flex items-center justify-center gap-3">
                        Publish Job Posting <i data-feather="send"></i>
                    </button>
                    <a href="{{ route('hrm.recruitment.index') }}" class="block text-center text-xs font-bold text-slate-400 uppercase tracking-[0.3em] mt-8 hover:text-slate-600 transition-colors">Cancel and return</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
