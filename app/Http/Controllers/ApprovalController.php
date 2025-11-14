<?php

namespace App\Http\Controllers;

use App\Models\Jobdesk;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index()
    {
        $pendingEntries = Jobdesk::with([
            'instructor',
            'course',
            'production',
            'training',
            'internalActivity', 
            'updater' 
        ])
        ->pending()
        ->latest('activity_date')
        ->paginate(15);

        return view('master.approvals.index', compact('pendingEntries'));
    }

    public function approve(Jobdesk $jobdesk)
    {
        $jobdesk->update(['status' => 'approved']);
        return back()->with('success', 'Entry approved.');
    }

    public function reject(Jobdesk $jobdesk)
    {
        $jobdesk->update(['status' => 'rejected']);
        return back()->with('success', 'Entry rejected.');
    }
}