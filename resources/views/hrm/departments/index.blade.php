@extends('layouts.app')

@section('title', 'Departments')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Departments</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Organize your team structure and leadership.</p>
        </div>
        
        <a href="{{ route('hrm.departments.create') }}" class="flex items-center gap-3 px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-[2rem] font-black transition-all active:scale-95 shadow-xl shadow-blue-200">
            <i data-feather="plus" class="w-5 h-5"></i> New Department
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @forelse($departments as $dept)
        <div class="bg-white p-10 rounded-[3rem] border border-slate-200 shadow-xl group hover:border-blue-500 transition-all">
            <div class="flex justify-between items-start mb-8">
                <div class="w-16 h-16 bg-slate-900 text-white rounded-2xl flex items-center justify-center font-black text-xl italic group-hover:bg-blue-600 transition-colors">
                    {{ substr($dept->name, 0, 1) }}
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('hrm.departments.edit', $dept->id) }}" class="p-2 text-slate-400 hover:text-blue-500 transition-colors">
                        <i data-feather="edit-2" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>
            
            <h3 class="text-2xl font-black text-slate-900 mb-2">{{ $dept->name }}</h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-6">{{ $dept->code }}</p>
            
            <div class="space-y-4 pt-6 border-t border-slate-50">
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Manager</span>
                    <span class="font-bold text-slate-700 text-xs">{{ $dept->manager->name ?? 'Unassigned' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Team Size</span>
                    <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg font-black text-[10px]">{{ $dept->employees_count ?? $dept->employees()->count() }} People</span>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 py-20 bg-white rounded-[3rem] border border-dashed border-slate-300 text-center">
            <p class="text-slate-400 font-bold italic">No departments created yet.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
