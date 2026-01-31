<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Payroll;
use App\Models\KpiEvaluation;

class EssController extends Controller
{
    private function getEmployee()
    {
        $employee = auth()->user()->employee;
        if (!$employee) {
            abort(403, 'Your user account is not linked to an employee profile.');
        }
        return $employee;
    }

    public function index()
    {
        $employee = $this->getEmployee();
        $latestAttendance = Attendance::where('employee_id', $employee->id)->latest()->first();
        $recentPayrolls = Payroll::where('employee_id', $employee->id)->latest()->take(3)->get();
        $latestKpi = KpiEvaluation::where('employee_id', $employee->id)->latest()->first();
        
        return view('hrm.ess.index', compact('employee', 'latestAttendance', 'recentPayrolls', 'latestKpi'));
    }

    public function profile()
    {
        $employee = $this->getEmployee();
        return view('hrm.ess.profile', compact('employee'));
    }

    public function attendance()
    {
        $employee = $this->getEmployee();
        $attendances = Attendance::where('employee_id', $employee->id)->latest()->paginate(10);
        return view('hrm.ess.attendance', compact('employee', 'attendances'));
    }

    public function payslips()
    {
        $employee = $this->getEmployee();
        $payrolls = Payroll::where('employee_id', $employee->id)->where('status', 'paid')->latest()->get();
        return view('hrm.ess.payslips', compact('employee', 'payrolls'));
    }
}
