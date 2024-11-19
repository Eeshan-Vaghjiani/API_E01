<?php

use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Submission routes
Route::get('/submissions/create', [SubmissionController::class, 'create'])->name('submissions.create');
Route::post('/submissions', [SubmissionController::class, 'store'])->name('submissions.store');
Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
Route::get('/submissions/{submission}', [SubmissionController::class, 'show'])->name('submissions.show');
Route::get('/submissions/{submission}/edit', [SubmissionController::class, 'edit'])->name('submissions.edit');
Route::put('/submissions/{submission}', [SubmissionController::class, 'update'])->name('submissions.update');
Route::delete('/submissions/{submission}', [SubmissionController::class, 'destroy'])->name('submissions.destroy');

// Or you can use the resource route which is equivalent to all routes above
// Route::resource('submissions', SubmissionController::class);


