<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instructor;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instructors = Instructor::with('updater')->latest()->paginate(10);
        return view('master.instructors.index', compact('instructors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.instructors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'employee_id' => 'required|unique:instructors,employee_id', // ← critical: must be unique
            'field_of_expertise' => 'required|string|max:255',
        ]);

        // Handle is_active (checkbox is only sent if checked)
        $is_active = $request->has('is_active');

        Instructor::create([
            'name' => $request->name,
            'employee_id' => $request->employee_id,
            'field_of_expertise' => $request->field_of_expertise,
            'is_active' => $is_active,
        ]);

        return redirect()->route('master.instructors.index')
            ->with('success', 'Instructor created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Instructor $instructor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Instructor $instructor)
    {
        return view('master.instructors.edit', compact('instructor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Instructor $instructor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'employee_id' => 'required|unique:instructors,employee_id,' . $instructor->id,
            'field_of_expertise' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        $instructor->update($request->only(['name', 'employee_id', 'field_of_expertise', 'is_active']) + ['is_active' => $request->has('is_active')]);

        return redirect()->route('master.instructors.index')->with('success', 'Instructor updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Instructor $instructor)
    {
        $instructor->delete();
        return back()->with('success', 'Instructor deleted.');
    }
}
