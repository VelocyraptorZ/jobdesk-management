<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jobdesk;
use App\Models\Instructor;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function index(Request $request) {
        $query = Jobdesk::with('instructor', 'course', 'production', 'training');

        // Apply filters
        if ($request->filled('start') && $request->filled('end')) {
            $query->whereBetween('activity_date', [$request->start, $request->end]);
        }
        if ($request->filled('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }
        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }

        $jobdesks = $query->approved()->orderBy('activity_date', 'desc')->paginate(20);

        // ✅ Build chart data: always 4 values in fixed order
        $practical = $query->clone()->where('activity_type', 'practical')->count();
        $theoretical = $query->clone()->where('activity_type', 'theoretical')->count();
        $production = $query->clone()->where('activity_type', 'production')->count();
        $training = $query->clone()->where('activity_type', 'training')->count();
        $internal = $query->clone()->where('activity_type', 'internal')->count();

        $chartData = [(int)$practical, (int)$theoretical, (int)$production, (int)$training, (int)$internal];

        return view('dashboard.index', [
            'jobdesks' => $jobdesks,
            'instructors' => Instructor::active()->get(),
            'chartData' => $chartData,
        ]);
    }

    public function export(Request $request) {
        $jobdesks = Jobdesk::with('instructor')
            ->when($request->filled('start') && $request->filled('end'), fn($q) =>
                $q->whereBetween('activity_date', [$request->start, $request->end])
            )
            ->when($request->filled('instructor_id'), fn($q) =>
                $q->where('instructor_id', $request->instructor_id)
            )
            ->get();

        if ($request->format === 'excel') {
            return Excel::download(new \App\Exports\JobdeskExport($jobdesks), 'jobdesks.xlsx');
        }

        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('exports.jobdesks-pdf', compact('jobdesks'));
            return $pdf->download('jobdesks.pdf');
        }

        return back()->withErrors('Invalid export format.');
    }
}
