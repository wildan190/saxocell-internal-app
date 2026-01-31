<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('department')->latest()->paginate(15);
        return view('hrm.employees.index', compact('employees'));
    }

    public function create()
    {
        $departments = Department::all();
        $users = User::whereDoesntHave('employee')->get();
        return view('hrm.employees.create', compact('departments', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'employee_id' => 'required|string|unique:employees,employee_id',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'nullable|string',
            'position' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'join_date' => 'required|date',
            'base_salary' => 'required|numeric',
            'user_id' => 'nullable|exists:users,id',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = $request->file('profile_picture')->store('employees', 'public');
        }

        Employee::create($validated);

        return redirect()->route('hrm.employees.index')->with('success', 'Employee record created successfully.');
    }

    public function show(Employee $employee)
    {
        return view('hrm.employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::all();
        $users = User::all();
        return view('hrm.employees.edit', compact('employee', 'departments', 'users'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'employee_id' => 'required|string|unique:employees,employee_id,' . $employee->id,
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string',
            'position' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'join_date' => 'required|date',
            'base_salary' => 'required|numeric',
            'status' => 'required|in:active,probation,resigned,terminated',
            'user_id' => 'nullable|exists:users,id',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($employee->profile_picture) {
                Storage::disk('public')->delete($employee->profile_picture);
            }
            $validated['profile_picture'] = $request->file('profile_picture')->store('employees', 'public');
        }

        $employee->update($validated);

        return redirect()->route('hrm.employees.index')->with('success', 'Employee record updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->profile_picture) {
            Storage::disk('public')->delete($employee->profile_picture);
        }
        $employee->delete();
        return redirect()->route('hrm.employees.index')->with('success', 'Employee record deleted successfully.');
    }

    public function salaryConfig(Employee $employee)
    {
        $components = \App\Models\SalaryComponent::all();
        $employeeComponents = $employee->salaryComponents->pluck('amount', 'salary_component_id')->toArray();
        return view('hrm.employees.salary_config', compact('employee', 'components', 'employeeComponents'));
    }

    public function updateSalaryConfig(Request $request, Employee $employee)
    {
        $request->validate([
            'components' => 'nullable|array',
            'components.*' => 'nullable|numeric|min:0',
        ]);

        \DB::transaction(function() use ($request, $employee) {
            $employee->salaryComponents()->delete();
            
            if ($request->has('components')) {
                foreach ($request->components as $compId => $amount) {
                    if ($amount > 0) {
                        $employee->salaryComponents()->create([
                            'salary_component_id' => $compId,
                            'amount' => $amount,
                        ]);
                    }
                }
            }
        });

        return redirect()->back()->with('success', 'Salary configuration updated.');
    }
}
