<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $attendances = Attendance::with('employee.department')
            ->whereDate('date', $today)
            ->latest()
            ->paginate(15);
        return view('hrm.attendance.index', compact('attendances'));
    }

    public function clockIn(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            return back()->with('error', 'Your user account is not linked to an employee record.');
        }

        $today = Carbon::today();
        $existing = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already clocked in today.');
        }

        Attendance::create([
            'employee_id' => $employee->id,
            'date' => $today,
            'clock_in' => Carbon::now(),
            'lat_in' => $request->lat,
            'long_in' => $request->long,
            'status' => Carbon::now()->hour >= 9 ? 'late' : 'present', // simple rule
        ]);

        return back()->with('success', 'Clocked in successfully at ' . Carbon::now()->format('H:i'));
    }

    public function clockOut(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            return back()->with('error', 'Employee record not found.');
        }

        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', Carbon::today())
            ->first();

        if (!$attendance) {
            return back()->with('error', 'You must clock in first.');
        }

        if ($attendance->clock_out) {
            return back()->with('error', 'You have already clocked out today.');
        }

        $attendance->update([
            'clock_out' => Carbon::now(),
            'lat_out' => $request->lat,
            'long_out' => $request->long,
        ]);

        return back()->with('success', 'Clocked out successfully at ' . Carbon::now()->format('H:i'));
    }

    public function report(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $employees = Employee::with(['attendances' => function($q) use ($month, $year) {
            $q->whereMonth('date', $month)->whereYear('date', $year);
        }])->get();

        return view('hrm.attendance.report', compact('employees', 'month', 'year'));
    }
}
