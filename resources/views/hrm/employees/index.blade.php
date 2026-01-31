@extends('layouts.app')

@section('title', 'Employee Directory')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Employees</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Manage your workforce and professional profiles.</p>
        </div>
        
        <div class="flex gap-4">
            <a href="{{ route('hrm.departments.index') }}" class="flex items-center gap-3 px-8 py-4 bg-white border border-slate-200 text-slate-600 rounded-[2rem] font-black transition-all hover:bg-slate-50 active:scale-95 shadow-lg">
                <i data-feather="grid" class="w-5 h-5"></i> Departments
            </a>
            <a href="{{ route('hrm.employees.create') }}" class="flex items-center gap-3 px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-[2rem] font-black transition-all active:scale-95 shadow-xl shadow-blue-200">
                <i data-feather="plus" class="w-5 h-5"></i> Add Employee
            </a>
        </div>
    </div>

    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Employee</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Position</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Department</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Join Date</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Status</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($employees as $emp)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-10 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl overflow-hidden bg-slate-100 flex-shrink-0">
                                    @if($emp->profile_picture)
                                        <img src="{{ asset('storage/' . $emp->profile_picture) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-400">
                                            <i data-feather="user" class="w-6 h-6"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <span class="font-black text-slate-800 text-lg">{{ $emp->full_name }}</span>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $emp->employee_id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-10 py-6">
                            <span class="font-bold text-slate-600">{{ $emp->position }}</span>
                        </td>
                        <td class="px-10 py-6">
                            <span class="px-4 py-2 bg-blue-50 text-blue-600 rounded-xl font-black text-[10px] uppercase tracking-wider">
                                {{ $emp->department->name }}
                            </span>
                        </td>
                        <td class="px-10 py-6 text-center">
                            <span class="font-bold text-slate-500 text-xs">{{ $emp->join_date->format('M d, Y') }}</span>
                        </td>
                        <td class="px-10 py-6 text-center">
                            @if($emp->status === 'active')
                                <span class="px-4 py-2 bg-emerald-100 text-emerald-600 rounded-xl font-black text-xs uppercase tracking-wider">Active</span>
                            @elseif($emp->status === 'probation')
                                <span class="px-4 py-2 bg-amber-100 text-amber-600 rounded-xl font-black text-xs uppercase tracking-wider">Probation</span>
                            @else
                                <span class="px-4 py-2 bg-rose-100 text-rose-600 rounded-xl font-black text-xs uppercase tracking-wider">{{ $emp->status }}</span>
                            @endif
                        </td>
                        <td class="px-10 py-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('hrm.employees.show', $emp->id) }}" class="p-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-all">
                                    <i data-feather="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('hrm.employees.salary-config', $emp->id) }}" class="p-3 bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-xl transition-all" title="Salary Config">
                                    <i data-feather="dollar-sign" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('hrm.employees.edit', $emp->id) }}" class="p-3 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl transition-all">
                                    <i data-feather="edit-3" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-10 py-20 text-center">
                            <div class="max-w-xs mx-auto">
                                <i data-feather="users" class="w-16 h-16 text-slate-200 mx-auto mb-6"></i>
                                <p class="text-slate-400 font-bold italic text-lg">No employee records found. Start building your team!</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($employees->hasPages())
        <div class="px-10 py-8 border-t border-slate-50 bg-slate-50/30">
            {{ $employees->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
