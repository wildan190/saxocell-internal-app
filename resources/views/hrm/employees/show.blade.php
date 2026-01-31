@extends('layouts.app')

@section('title', 'Employee Profile - ' . $employee->full_name)

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20 p-8 md:p-12">
    <div class="max-w-6xl mx-auto">
        <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <a href="{{ route('hrm.employees.index') }}" class="p-4 bg-white border border-slate-200 text-slate-400 rounded-2xl hover:text-slate-900 transition-colors">
                    <i data-feather="arrow-left" class="w-6 h-6"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">{{ $employee->full_name }}</h1>
                    <p class="text-slate-500 font-bold uppercase tracking-wider text-xs mt-1">{{ $employee->employee_id }} • {{ $employee->position }}</p>
                </div>
            </div>
            
            <div class="flex gap-4">
                <a href="{{ route('hrm.employees.edit', $employee->id) }}" class="flex items-center gap-3 px-8 py-4 bg-white border border-slate-200 text-slate-600 rounded-[2rem] font-black transition-all hover:bg-slate-50 active:scale-95 shadow-lg">
                    <i data-feather="edit-2" class="w-5 h-5"></i> Edit Profile
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <!-- Sidebar -->
            <div class="space-y-8">
                <div class="bg-white p-10 rounded-[3.5rem] border border-slate-200 shadow-xl overflow-hidden relative">
                    <div class="aspect-square rounded-[3rem] bg-slate-100 mb-8 overflow-hidden border-4 border-white shadow-xl">
                        @if($employee->profile_picture)
                            <img src="{{ asset('storage/' . $employee->profile_picture) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-blue-600 text-white text-3xl font-bold">
                                {{ substr($employee->first_name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-black text-slate-400 uppercase tracking-wider">Status</span>
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg font-black uppercase">{{ $employee->status }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-black text-slate-400 uppercase tracking-wider">Joined</span>
                            <span class="font-bold text-slate-700">{{ $employee->join_date->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900 p-10 rounded-[3.5rem] text-white shadow-2xl">
                    <h4 class="text-xs font-bold text-white/40 uppercase tracking-[0.3em] mb-8 border-b border-white/10 pb-4 italic">Quick Stats</h4>
                    <div class="space-y-8">
                        <div>
                            <span class="block text-xs font-bold text-white/30 uppercase tracking-wider mb-1">Base Salary</span>
                            <p class="text-2xl font-black italic">Rp {{ number_format($employee->base_salary, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-white/30 uppercase tracking-wider mb-1">Attendance Rate</span>
                            <p class="text-2xl font-black italic">--%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="md:col-span-2 space-y-12">
                <div class="bg-white p-12 rounded-[4rem] border border-slate-200 shadow-xl">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-[0.4em] mb-12 border-b border-slate-50 pb-6 italic">Information Overview</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-12 gap-x-12">
                        <div class="space-y-1">
                            <span class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Email Address</span>
                            <p class="font-bold text-slate-800 text-lg">{{ $employee->email }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Phone Number</span>
                            <p class="font-bold text-slate-800 text-lg">{{ $employee->phone ?? '--' }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Department</span>
                            <p class="font-bold text-slate-800 text-lg">{{ $employee->department->name }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Address</span>
                            <p class="font-bold text-slate-800 text-lg">{{ $employee->address ?? '--' }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Linked User</span>
                            <p class="font-bold text-slate-800 text-lg">{{ $employee->user->name ?? 'Not Linked' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity/Performance -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white p-10 rounded-[3rem] border border-slate-200 shadow-lg">
                        <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-6">Latest KPI</h5>
                        <div class="text-3xl font-black text-slate-900 italic">N/A</div>
                    </div>
                    <div class="bg-white p-10 rounded-[3rem] border border-slate-200 shadow-lg">
                        <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-6">Latest Payroll</h5>
                        <div class="text-3xl font-black text-slate-900 italic">--</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
