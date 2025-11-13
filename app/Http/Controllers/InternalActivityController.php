<?php

namespace App\Http\Controllers;

use App\Models\InternalActivity;
use Illuminate\Http\Request;

class InternalActivityController extends Controller
{
    public function index()
    {
        $activities = InternalActivity::with('updater')->latest()->paginate(10);
        return view('master.internal-activities.index', compact('activities'));
    }

    public function create()
    {
        return view('master.internal-activities.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        InternalActivity::create($data);

        return redirect()->route('master.internal-activities.index')
            ->with('success', 'Internal activity created.');
    }

    public function edit(InternalActivity $internalActivity)
    {
        return view('master.internal-activities.edit', compact('internalActivity'));
    }

    public function update(Request $request, InternalActivity $internalActivity)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $internalActivity->update($data);

        return redirect()->route('master.internal-activities.index')
            ->with('success', 'Internal activity updated.');
    }

    public function destroy(InternalActivity $internalActivity)
    {
        $internalActivity->delete();
        return back()->with('success', 'Internal activity deleted.');
    }
}