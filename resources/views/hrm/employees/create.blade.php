@extends('layouts.app')

@section('title', 'Add Employee')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20 p-8 md:p-12">
    <div class="max-w-5xl mx-auto">
        <div class="mb-12">
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Add Employee</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Onboard a new member to your professional team.</p>
        </div>

        <div class="bg-white rounded-[3rem] border border-slate-200 shadow-2xl overflow-hidden">
            <form action="{{ route('hrm.employees.store') }}" method="POST" enctype="multipart/form-data" class="p-12 space-y-12">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <!-- Personal Info -->
                    <div class="space-y-8">
                        <h3 class="text-xl font-black text-slate-900 border-b-4 border-blue-500 inline-block">Personal Information</h3>
                        
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">First Name</label>
                                <input type="text" name="first_name" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none" required>
                            </div>
                            <div class="space-y-3">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Last Name</label>
                                <input type="text" name="last_name" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none">
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Email Address</label>
                            <input type="email" name="email" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none" required>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Phone Number</label>
                            <input type="text" name="phone" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none">
                        </div>
                    </div>

                    <!-- Employment Info -->
                    <div class="space-y-8">
                        <h3 class="text-xl font-black text-slate-900 border-b-4 border-emerald-500 inline-block">Employment Details</h3>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Employee ID (NIK)</label>
                                <input type="text" name="employee_id" placeholder="NIK-2026-001" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-black text-slate-800 transition-all outline-none" required>
                            </div>
                            <div class="space-y-3">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Join Date</label>
                                <input type="date" name="join_date" value="{{ date('Y-m-d') }}" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Position</label>
                                <input type="text" name="position" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none" required>
                            </div>
                            <div class="space-y-3">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Department</label>
                                <select name="department_id" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none appearance-none" required>
                                    <option value="">-- Select Dept --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Base Monthly Salary</label>
                            <div class="relative">
                                <span class="absolute left-6 top-1/2 -translate-y-1/2 font-black text-slate-400">Rp</span>
                                <input type="number" name="base_salary" class="w-full pl-14 pr-6 py-4 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-black text-slate-800 transition-all outline-none" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-8 bg-slate-50 p-10 rounded-[2.5rem] border border-slate-100">
                    <h3 class="text-xl font-black text-slate-900 border-b-4 border-amber-500 inline-block">System Integration</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-3">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Link to Auth Account (for ESS)</label>
                            <select name="user_id" class="w-full px-6 py-4 bg-white border-2 border-slate-100 focus:border-blue-500 rounded-2xl font-bold text-slate-800 transition-all outline-none appearance-none">
                                <option value="">-- No Account (Manual Only) --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-3">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Profile Picture</label>
                            <input type="file" name="profile_picture" class="w-full px-6 py-4 bg-white border-2 border-slate-100 focus:border-blue-500 rounded-2xl font-bold text-slate-800 transition-all outline-none">
                        </div>
                    </div>
                </div>

                <div class="pt-10 flex flex-col md:flex-row gap-6">
                    <button type="submit" class="flex-1 py-6 bg-blue-600 hover:bg-blue-700 text-white rounded-[2.5rem] font-black text-xl shadow-2xl transition-all active:scale-[0.98] uppercase tracking-tighter italic flex items-center justify-center gap-3">
                        Save Employee Record <i data-feather="user-plus"></i>
                    </button>
                    <a href="{{ route('hrm.employees.index') }}" class="px-12 py-6 bg-white border border-slate-200 text-slate-500 rounded-[2.5rem] font-black text-center transition-all hover:bg-slate-50 active:scale-95 text-xs uppercase tracking-wider flex items-center justify-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
