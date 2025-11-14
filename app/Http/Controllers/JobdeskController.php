<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jobdesk;
use App\Models\Instructor;
use App\Models\Course;
use App\Models\Production;
use App\Models\Training;
use App\Models\InternalActivity;
use Illuminate\Validation\Rule;

class JobdeskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Jobdesk::with('instructor', 'course', 'production', 'training', 'updater');

        if ($request->filled('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('activity_date', [$request->start_date, $request->end_date]);
        }
        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $jobdesks = $query->latest('activity_date')->paginate(15);
        $instructors = Instructor::active()->get();

        return view('jobdesks.entries.index', compact('jobdesks', 'instructors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jobdesks.entries.create', [
            'instructors' => Instructor::active()->get(),
            'courses' => Course::all(),
            'productions' => Production::all(),
            'trainings' => Training::all(),
            'internalActivities' => InternalActivity::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Base validation
        $request->validate([
            'instructor_id' => 'required|exists:instructors,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'end_time' => 'nullable|after:start_time',
            'activity_type' => ['required', 'in:practical,theoretical,production,training,internal'],
            'description' => 'required|string',
        ]);

        // Conditional validation (100% Laravel 10 compatible)
        $conditionalRules = [];

        if (in_array($request->activity_type, ['practical', 'theoretical'])) {
            $conditionalRules['course_id'] = 'required|exists:courses,id';
        } else {
            $conditionalRules['course_id'] = 'nullable';
        }

        if ($request->activity_type === 'production') {
            $conditionalRules['production_id'] = 'required|exists:productions,id';
        } else {
            $conditionalRules['production_id'] = 'nullable';
        }

        if ($request->activity_type === 'training') {
            $conditionalRules['training_id'] = 'required|exists:trainings,id';
        } else {
            $conditionalRules['training_id'] = 'nullable';
        }

        if ($request->activity_type === 'internal') {
            $conditionalRules['internal_activity_id'] = 'required|exists:internal_activities,id';
        } else {
            $conditionalRules['internal_activity_id'] = 'nullable';
        }

        $request->validate($conditionalRules);

        // Prepare base data
        $baseData = [
            'instructor_id' => $request->instructor_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'activity_type' => $request->activity_type,
            'description' => $request->description,
            'course_id' => null,
            'production_id' => null,
            'training_id' => null,
            'internal_activity_id' => null,
            'status' => 'pending',
        ];

        // Set activity reference
        if (in_array($request->activity_type, ['practical', 'theoretical'])) {
            $baseData['course_id'] = $request->course_id;
        } elseif ($request->activity_type === 'production') {
            $baseData['production_id'] = $request->production_id;
        } elseif ($request->activity_type === 'training') {
            $baseData['training_id'] = $request->training_id;
        } elseif ($request->activity_type === 'internal') {
            $baseData['internal_activity_id'] = $request->internal_activity_id;
        }

        // Generate entries for date range
        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);

        $createdCount = 0;
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            Jobdesk::create(array_merge($baseData, [
                'activity_date' => $date->format('Y-m-d'),
            ]));
            $createdCount++;
        }

        return redirect()->route('jobdesks.entries.index')
            ->with('success', "Jobdesk entries created for {$createdCount} days.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Jobdesk $entry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jobdesk $entry)
    {
        return view('jobdesks.entries.edit', [
            'entry' => $entry,
            'instructors' => Instructor::active()->get(),
            'courses' => Course::all(),
            'productions' => Production::all(),
            'trainings' => Training::all(),
            'internalActivities' => InternalActivity::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jobdesk $entry)
    {
        // Base validation
        $request->validate([
            'instructor_id' => 'required|exists:instructors,id',
            'activity_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'nullable|after:start_time',
            'activity_type' => ['required', 'in:practical,theoretical,production,training,internal'],
            'description' => 'required|string',
        ]);

        // Conditional validation
        $rules = [];
        if (in_array($request->activity_type, ['practical', 'theoretical'])) {
            $rules['course_id'] = 'required|exists:courses,id';
        } else {
            $rules['course_id'] = 'nullable';
        }

        if ($request->activity_type === 'production') {
            $rules['production_id'] = 'required|exists:productions,id';
        } else {
            $rules['production_id'] = 'nullable';
        }

        if ($request->activity_type === 'training') {
            $rules['training_id'] = 'required|exists:trainings,id';
        } else {
            $rules['training_id'] = 'nullable';
        }

        if ($request->activity_type === 'internal') {
            $rules['internal_activity_id'] = 'required|exists:internal_activities,id';
        } else {
            $rules['internal_activity_id'] = 'nullable';
        }

        $request->validate($rules);

        // Prepare data
        $data = [
            'instructor_id' => $request->instructor_id,
            'activity_date' => $request->activity_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'activity_type' => $request->activity_type,
            'description' => $request->description,
            'course_id' => null,
            'production_id' => null,
            'training_id' => null,
            'internal_activity_id' => null,
            // ✅ Preserve current status (do NOT reset to 'pending')
            'status' => $entry->status,
        ];

        // Set correct reference
        if (in_array($request->activity_type, ['practical', 'theoretical'])) {
            $data['course_id'] = $request->course_id;
        } elseif ($request->activity_type === 'production') {
            $data['production_id'] = $request->production_id;
        } elseif ($request->activity_type === 'training') {
            $data['training_id'] = $request->training_id;
        } elseif ($request->activity_type === 'internal') {
            $data['internal_activity_id'] = $request->internal_activity_id;
        }

        $entry->update($data);

        return redirect()->route('jobdesks.entries.index')
            ->with('success', 'Jobdesk entry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jobdesk $entry)
    {
        $entry->delete();
        return back()->with('success', 'Deleted.');
    }
}
