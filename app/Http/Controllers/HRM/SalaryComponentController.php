<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\SalaryComponent;
use Illuminate\Http\Request;

class SalaryComponentController extends Controller
{
    public function index()
    {
        $components = SalaryComponent::all();
        return view('hrm.payroll.components.index', compact('components'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:allowance,deduction',
            'default_amount' => 'required|numeric|min:0',
            'is_fixed' => 'nullable|boolean',
        ]);

        $validated['is_fixed'] = $request->has('is_fixed');

        SalaryComponent::create($validated);

        return redirect()->back()->with('success', 'Salary component created.');
    }

    public function destroy(SalaryComponent $component)
    {
        $component->delete();
        return redirect()->back()->with('success', 'Salary component deleted.');
    }
}
