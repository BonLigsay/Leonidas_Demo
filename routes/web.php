<?php

use App\Http\Controllers\FollowupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::redirect('/', '/login'); // Redirect '/' to '/login'


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/administration', function () {
    return Inertia::render('Administration');
})->middleware(['auth', 'verified', 'role:admin'])->name('administration');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('tickets', TicketController::class)
    // ->only(['index', 'store'])
    ->middleware(['auth', 'verified']);

Route::middleware(['auth'])->group(function () {
    Route::post('/followups', [FollowupController::class, 'store'])->middleware('can.create.solution');
    Route::get('/tickets/{ticket}/followups', [FollowupController::class, 'index']);
    Route::get('/followups/{followup}', [FollowupController::class, 'show']);
    Route::put('/followups/{followup}', [FollowupController::class, 'update'])->middleware('can.create.solution');
    Route::delete('/followups/{followup}', [FollowupController::class, 'destroy'])->middleware('can.create.solution');
});

require __DIR__.'/auth.php';
