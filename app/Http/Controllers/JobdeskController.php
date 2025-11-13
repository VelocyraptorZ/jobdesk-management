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
            'activity_date' => 'required|date',
            'start_time' => 'required',
            'activity_type' => ['required', Rule::in(['practical', 'theoretical', 'production', 'training', 'internal'])],
            'description' => 'required|string',
        ]);

        // Conditional validations
        if (in_array($request->activity_type, ['practical', 'theoretical'])) {
            $request->validate(['course_id' => 'nullable|exists:courses,id']);
        }

        if ($request->activity_type === 'production') {
            $request->validate(['production_id' => 'nullable|exists:productions,id']);
        }

        if ($request->activity_type === 'training') {
            $request->validate(['training_id' => 'nullable|exists:trainings,id']);
        }

        if ($request->activity_type === 'internal') {
            $request->validate(['internal_activity_id' => 'required|exists:internal_activities,id']);
        }

        $data = $request->only([
            'instructor_id', 'activity_date', 'start_time', 'activity_type', 'description'
        ]);

        // Clear all references first
        $data['course_id'] = null;
        $data['production_id'] = null;
        $data['training_id'] = null;
        $data['internal_activity_id'] = null; 

        // Set the correct one
        if (in_array($request->activity_type, ['practical', 'theoretical'])) {
            $data['course_id'] = $request->course_id;
        } elseif ($request->activity_type === 'production') {
            $data['production_id'] = $request->production_id;
        } elseif ($request->activity_type === 'training') {
            $data['training_id'] = $request->training_id;
        } elseif ($request->activity_type === 'internal') {
            $data['internal_activity_id'] = $request->internal_activity_id;
        }

        $data['status'] = 'pending';

        Jobdesk::create($data);

        return redirect()->route('jobdesks.entries.index')->with('success', 'Jobdesk entry submitted for approval.');
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
            'activity_type' => ['required', Rule::in(['practical', 'theoretical', 'production', 'training', 'internal'])],
            'description' => 'required|string',
        ]);

        // Conditional validations
        if (in_array($request->activity_type, ['practical', 'theoretical'])) {
            $request->validate(['course_id' => 'nullable|exists:courses,id']);
        }

        if ($request->activity_type === 'production') {
            $request->validate(['production_id' => 'nullable|exists:productions,id']);
        }

        if ($request->activity_type === 'training') {
            $request->validate(['training_id' => 'nullable|exists:trainings,id']);
        }

        if ($request->activity_type === 'internal') {
            $request->validate(['internal_activity_id' => 'required|exists:internal_activities,id']);
        }

        $data = $request->only([
            'instructor_id', 'activity_date', 'start_time', 'activity_type', 'description'
        ]);

        // Clear all reference fields
        $data['course_id'] = null;
        $data['production_id'] = null;
        $data['training_id'] = null;
        $data['internal_activity_id'] = null; 

        // Set the correct reference
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

        return redirect()->route('jobdesks.entries.index')->with('success', 'Jobdesk entry updated successfully.');
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
