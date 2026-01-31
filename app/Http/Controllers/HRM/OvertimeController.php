<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\OvertimeRecord;
use App\Models\Employee;
use Illuminate\Http\Request;

class OvertimeController extends Controller
{
    public function index()
    {
        $overtimes = OvertimeRecord::with('employee')->latest()->paginate(15);
        $employees = Employee::where('status', 'active')->get();
        return view('hrm.overtime.index', compact('overtimes', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'hours' => 'required|numeric|min:0.5',
            'rate_per_hour' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['total_amount'] = $validated['hours'] * $validated['rate_per_hour'];
        $validated['status'] = 'pending';

        OvertimeRecord::create($validated);

        return redirect()->back()->with('success', 'Overtime record submitted for approval.');
    }

    public function approve(Request $request, OvertimeRecord $overtime)
    {
        $overtime->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Overtime approved.');
    }

    public function reject(Request $request, OvertimeRecord $overtime)
    {
        $overtime->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Overtime rejected.');
    }
}
