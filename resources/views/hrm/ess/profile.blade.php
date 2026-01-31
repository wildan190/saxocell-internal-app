@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20 p-8 md:p-12">
    <div class="max-w-4xl mx-auto">
        <div class="mb-12 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight italic">My Profile</h1>
                <p class="text-slate-500 mt-3 font-medium text-lg">Manage your personal and professional information.</p>
            </div>
            <a href="{{ route('hrm.ess.index') }}" class="p-4 bg-white border border-slate-200 text-slate-400 rounded-2xl hover:text-slate-900 transition-colors">
                <i data-feather="arrow-left" class="w-6 h-6"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <!-- Sidebar -->
            <div class="space-y-8">
                <div class="bg-white p-8 rounded-[3rem] border border-slate-200 shadow-xl text-center">
                    <div class="w-32 h-32 rounded-[2.5rem] bg-slate-100 mx-auto mb-6 overflow-hidden border-4 border-white shadow-lg">
                        @if($employee->profile_picture)
                            <img src="{{ asset('storage/' . $employee->profile_picture) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-blue-600 text-white text-4xl font-black italic">
                                {{ substr($employee->first_name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <h3 class="text-xl font-black text-slate-900">{{ $employee->full_name }}</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1">{{ $employee->position }}</p>
                    <div class="mt-8 pt-6 border-t border-slate-50">
                        <span class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl font-black text-[10px] uppercase tracking-wider">{{ $employee->status }}</span>
                    </div>
                </div>

                <div class="bg-slate-900 p-8 rounded-[3rem] text-white shadow-2xl relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/5 rounded-full blur-2xl"></div>
                    <span class="block text-xs font-bold text-white/40 uppercase tracking-wider mb-4">Official NIK</span>
                    <div class="text-2xl font-black tracking-wider">{{ $employee->employee_id }}</div>
                    <p class="text-[10px] font-bold text-white/30 mt-4 uppercase tracking-tighter italic">Joined {{ $employee->join_date->format('F Y') }}</p>
                </div>
            </div>

            <!-- Content -->
            <div class="md:col-span-2 space-y-12">
                <div class="bg-white p-12 rounded-[3.5rem] border border-slate-200 shadow-xl space-y-12">
                    <section class="space-y-8">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-[0.3em] border-b border-slate-100 pb-4">Contact Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div>
                                <span class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Work Email</span>
                                <p class="font-bold text-slate-800">{{ $employee->email }}</p>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Phone Number</span>
                                <p class="font-bold text-slate-800">{{ $employee->phone ?? '--' }}</p>
                            </div>
                        </div>
                    </section>

                    <section class="space-y-8">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-[0.3em] border-b border-slate-100 pb-4">Job Details</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div>
                                <span class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Department</span>
                                <p class="font-bold text-slate-800">{{ $employee->department->name }}</p>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Direct Manager</span>
                                <p class="font-bold text-slate-800">{{ $employee->department->manager->name ?? 'None' }}</p>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Base Salary</span>
                                <p class="font-black text-slate-600">Rp {{ number_format($employee->base_salary, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </section>

                    <div class="pt-8 border-t border-slate-50">
                        <p class="text-[10px] font-bold text-slate-400 flex items-center gap-2 italic">
                            <i data-feather="info" class="w-3 h-3"></i>
                            To update these details, please contact your HR Administrator.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
