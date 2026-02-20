<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\ActivityLog::with(['user', 'subject'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $logs = $query->paginate(20);

        return view('activity-logs.index', compact('logs'));
    }
}
