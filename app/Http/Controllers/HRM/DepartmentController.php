<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('manager')->get();
        return view('hrm.departments.index', compact('departments'));
    }

    public function create()
    {
        $managers = User::all();
        return view('hrm.departments.create', compact('managers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:departments,code',
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        Department::create($validated);

        return redirect()->route('hrm.departments.index')->with('success', 'Department created successfully.');
    }

    public function edit(Department $department)
    {
        $managers = User::all();
        return view('hrm.departments.edit', compact('department', 'managers'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:departments,code,' . $department->id,
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $department->update($validated);

        return redirect()->route('hrm.departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        if ($department->employees()->exists()) {
            return back()->with('error', 'Cannot delete department with active employees.');
        }
        $department->delete();
        return redirect()->route('hrm.departments.index')->with('success', 'Department deleted successfully.');
    }
}
