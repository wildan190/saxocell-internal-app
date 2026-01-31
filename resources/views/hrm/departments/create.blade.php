@extends('layouts.app')

@section('title', 'Create Department')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20 p-8 md:p-12">
    <div class="max-w-3xl mx-auto">
        <div class="mb-12">
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">New Department</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Define a new functional unit in your organization.</p>
        </div>

        <div class="bg-white rounded-[3rem] border border-slate-200 shadow-2xl overflow-hidden">
            <form action="{{ route('hrm.departments.store') }}" method="POST" class="p-12 space-y-10">
                @csrf
                
                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Department Name</label>
                    <input type="text" name="name" placeholder="e.g. Technology & Engineering" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Department Code</label>
                        <input type="text" name="code" placeholder="e.g. TECH" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none text-center" required>
                    </div>
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Department Manager</label>
                        <select name="manager_id" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none appearance-none">
                            <option value="">-- No Manager --</option>
                            @foreach($managers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Description (Optional)</label>
                    <textarea name="description" rows="4" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none"></textarea>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full py-6 bg-slate-900 hover:bg-black text-white rounded-[2.5rem] font-black text-xl shadow-2xl transition-all active:scale-[0.98] uppercase tracking-tighter italic flex items-center justify-center gap-3">
                        Create Department <i data-feather="check-circle"></i>
                    </button>
                    <a href="{{ route('hrm.departments.index') }}" class="block text-center text-xs font-bold text-slate-400 uppercase tracking-[0.3em] mt-8 hover:text-slate-600 transition-colors">Cancel and return to list</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
