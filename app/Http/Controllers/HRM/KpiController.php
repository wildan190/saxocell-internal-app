<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\KpiIndicator;
use App\Models\KpiEvaluation;
use App\Models\KpiEvaluationDetail;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KpiController extends Controller
{
    public function index()
    {
        $evaluations = KpiEvaluation::with('employee', 'evaluator')->latest()->paginate(15);
        return view('hrm.kpi.index', compact('evaluations'));
    }

    public function indicators()
    {
        $indicators = KpiIndicator::all();
        return view('hrm.kpi.indicators', compact('indicators'));
    }

    public function create()
    {
        $employees = Employee::where('status', 'active')->get();
        $indicators = KpiIndicator::all();
        return view('hrm.kpi.create', compact('employees', 'indicators'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period_name' => 'required|string',
            'feedback' => 'nullable|string',
            'scores' => 'required|array',
            'scores.*' => 'numeric|min:0|max:100',
        ]);

        DB::transaction(function() use ($validated) {
            $totalScore = 0;
            $indicators = KpiIndicator::whereIn('id', array_keys($validated['scores']))->get();
            
            foreach ($indicators as $indicator) {
                $score = $validated['scores'][$indicator->id];
                $totalScore += ($score * ($indicator->weight / 100));
            }

            $evaluation = KpiEvaluation::create([
                'employee_id' => $validated['employee_id'],
                'period_name' => $validated['period_name'],
                'total_score' => $totalScore,
                'feedback' => $validated['feedback'],
                'evaluator_id' => auth()->id() ?? \App\Models\User::first()->id,
                'status' => 'finalized',
            ]);

            foreach ($validated['scores'] as $indicatorId => $score) {
                KpiEvaluationDetail::create([
                    'kpi_evaluation_id' => $evaluation->id,
                    'kpi_indicator_id' => $indicatorId,
                    'score' => $score,
                ]);
            }
        });

        return redirect()->route('hrm.kpi.index')->with('success', 'KPI Evaluation recorded successfully.');
    }
}
