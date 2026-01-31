@extends('layouts.app')

@section('title', 'Edit Employee - ' . $employee->full_name)

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20 p-8 md:p-12">
    <div class="max-w-5xl mx-auto">
        <div class="mb-12">
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight italic">Update Employee</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Modify employment and professional details.</p>
        </div>

        <div class="bg-white rounded-[4rem] border border-slate-200 shadow-2xl overflow-hidden">
            <form action="{{ route('hrm.employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data" class="p-12 space-y-12">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <!-- Personal Info -->
                    <div class="space-y-8">
                        <h3 class="text-xl font-black text-slate-900 border-b-4 border-blue-500 inline-block italic">Profile Information</h3>
                        
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">First Name</label>
                                <input type="text" name="first_name" value="{{ $employee->first_name }}" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none" required>
                            </div>
                            <div class="space-y-3">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Last Name</label>
                                <input type="text" name="last_name" value="{{ $employee->last_name }}" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none">
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Email Address</label>
                            <input type="email" name="email" value="{{ $employee->email }}" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none" required>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Phone Number</label>
                            <input type="text" name="phone" value="{{ $employee->phone }}" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none">
                        </div>
                    </div>

                    <!-- Employment Info -->
                    <div class="space-y-8">
                        <h3 class="text-xl font-black text-slate-900 border-b-4 border-emerald-500 inline-block italic">Job Details</h3>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Employee ID (NIK)</label>
                                <input type="text" name="employee_id" value="{{ $employee->employee_id }}" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-black text-slate-800 transition-all outline-none" required>
                            </div>
                            <div class="space-y-3">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Status</label>
                                <select name="status" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none appearance-none" required>
                                    <option value="active" {{ $employee->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="probation" {{ $employee->status === 'probation' ? 'selected' : '' }}>Probation</option>
                                    <option value="resigned" {{ $employee->status === 'resigned' ? 'selected' : '' }}>Resigned</option>
                                    <option value="terminated" {{ $employee->status === 'terminated' ? 'selected' : '' }}>Terminated</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Position</label>
                                <input type="text" name="position" value="{{ $employee->position }}" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none" required>
                            </div>
                            <div class="space-y-3">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Department</label>
                                <select name="department_id" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none appearance-none" required>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ $employee->department_id == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Base Monthly Salary</label>
                            <div class="relative">
                                <span class="absolute left-6 top-1/2 -translate-y-1/2 font-black text-slate-400">Rp</span>
                                <input type="number" name="base_salary" value="{{ (int)$employee->base_salary }}" class="w-full pl-14 pr-6 py-4 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-black text-slate-800 transition-all outline-none" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-8 bg-slate-50 p-10 rounded-[3rem] border border-slate-100">
                    <h3 class="text-xl font-black text-slate-900 border-b-4 border-amber-500 inline-block italic">Advanced</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-3">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Link to Auth Account</label>
                            <select name="user_id" class="w-full px-6 py-4 bg-white border-2 border-slate-100 focus:border-blue-500 rounded-2xl font-bold text-slate-800 transition-all outline-none appearance-none">
                                <option value="">-- No Account linked --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $employee->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-3">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Profile Photo</label>
                            <input type="file" name="profile_picture" class="w-full px-6 py-4 bg-white border-2 border-slate-100 focus:border-blue-500 rounded-2xl font-bold text-slate-800 transition-all outline-none">
                            @if($employee->profile_picture)
                                <p class="text-[8px] font-black text-emerald-500 uppercase tracking-wider mt-2 flex items-center gap-1">
                                    <i data-feather="check"></i> Existing photo will be replaced if you upload a new one
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="pt-10 flex flex-col md:flex-row gap-6">
                    <button type="submit" class="flex-1 py-6 bg-slate-900 hover:bg-black text-white rounded-[2.5rem] font-black text-xl shadow-2xl transition-all active:scale-[0.98] uppercase tracking-tighter italic flex items-center justify-center gap-3">
                        Save Changes <i data-feather="save"></i>
                    </button>
                    <a href="{{ route('hrm.employees.show', $employee->id) }}" class="px-12 py-6 bg-white border border-slate-200 text-slate-500 rounded-[2.5rem] font-black text-center transition-all hover:bg-slate-50 active:scale-95 text-xs uppercase tracking-wider flex items-center justify-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
