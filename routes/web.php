<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\{
    DashboardController,
    InstructorController,
    CourseController,
    ProductionController,
    TrainingController,
    JobdeskController,
    ApprovalController
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public home: redirect logged-in users to /home
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('home');
    }
    return view('welcome');
});

// Authenticated routes
Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Unified role-based home (MUST be accessible to all roles)
    Route::get('/home', function () {
        $user = auth()->user();
        if ($user->isDirector()) {
            return redirect()->route('dashboard');
        } elseif ($user->isAdmin()) {
            return redirect()->route('jobdesks.entries.index');
        } else {
            return redirect()->route('master.instructors.index');
        }
    })->name('home');

    // Superadmin: Master Data
    Route::middleware(['role:superadmin'])->prefix('master')->name('master.')->group(function () {
        Route::resource('instructors', InstructorController::class);
        Route::resource('courses', CourseController::class);
        Route::resource('productions', ProductionController::class);
        Route::resource('trainings', TrainingController::class);
        Route::get('approvals', [ApprovalController::class, 'index'])->name('approvals.index');
        Route::patch('approvals/{jobdesk}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
        Route::patch('approvals/{jobdesk}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');
    });

    // Admin (K.UPT): Jobdesk Entries
    Route::middleware(['role:admin'])->prefix('jobdesks')->name('jobdesks.')->group(function () {
        Route::resource('entries', JobdeskController::class)->except(['show']);
    });

    // Director: Dashboard
    Route::middleware(['role:user,admin,superadmin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/export', [DashboardController::class, 'export'])->name('dashboard.export');
    });
});

require __DIR__.'/auth.php';