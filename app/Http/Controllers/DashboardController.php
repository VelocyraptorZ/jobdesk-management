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
        // ✅ Reuse the SAME query logic as index()
        $query = Jobdesk::with('instructor', 'course', 'production', 'training', 'internalActivity', 'updater')
            ->approved(); // Director only sees approved

        // Apply identical filters
        if ($request->filled('start') && $request->filled('end')) {
            $query->whereBetween('activity_date', [$request->start, $request->end]);
        }
        if ($request->filled('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }
        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }

        $jobdesks = $query->orderBy('activity_date', 'desc')->get();

        // Get filter labels for metadata
        $filters = [];
        if ($request->filled('start') && $request->filled('end')) {
            $filters[] = 'Date: ' . $request->start . ' to ' . $request->end;
        }
        if ($request->filled('instructor_id')) {
            $instructor = Instructor::find($request->instructor_id);
            $filters[] = 'Instructor: ' . ($instructor ? $instructor->name : 'Unknown');
        }
        if ($request->filled('activity_type')) {
            $typeMap = [
                'practical' => 'Practical',
                'theoretical' => 'Theoretical',
                'production' => 'Production',
                'training' => 'Training',
                'internal' => 'Internal Activity'
            ];
            $filters[] = 'Activity: ' . ($typeMap[$request->activity_type] ?? $request->activity_type);
        }

        if ($request->format === 'excel') {
            return Excel::download(
                new \App\Exports\JobdeskExport($jobdesks, $filters),
                'jobdesk_report_' . now()->format('Y-m-d_H-i') . '.xlsx'
            );
        }

        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('exports.jobdesks-pdf', [
                'jobdesks' => $jobdesks,
                'filters' => $filters,
                'exportedAt' => now()->format('d M Y H:i')
            ]);
            return $pdf->download('jobdesk_report_' . now()->format('Y-m-d_H-i') . '.pdf');
        }

        abort(400, 'Invalid export format');
    }
}
