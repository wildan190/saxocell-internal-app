<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\Applicant;
use App\Models\Department;
use Illuminate\Http\Request;

class RecruitmentController extends Controller
{
    public function index()
    {
        $jobs = JobPosting::with('department')->withCount('applicants')->latest()->paginate(10);
        return view('hrm.recruitment.index', compact('jobs'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('hrm.recruitment.jobs.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'closing_date' => 'nullable|date',
        ]);

        JobPosting::create($validated);

        return redirect()->route('hrm.recruitment.index')->with('success', 'Job posting published.');
    }

    public function show(JobPosting $job)
    {
        $job->load('applicants', 'department');
        return view('hrm.recruitment.jobs.show', compact('job'));
    }

    public function applicants()
    {
        $applicants = Applicant::with('jobPosting')->latest()->paginate(15);
        return view('hrm.recruitment.applicants.index', compact('applicants'));
    }

    public function updateApplicant(Request $request, Applicant $applicant)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,shortlisted,interviewing,offered,hired,rejected',
        ]);

        $applicant->update($validated);

        return back()->with('success', 'Applicant status updated.');
    }
}
