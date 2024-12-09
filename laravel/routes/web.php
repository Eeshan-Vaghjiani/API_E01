<?php

use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::get('/resources/views/auth/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('verify-2fa', [AuthController::class, 'show2faForm'])->name('verify-2fa');
Route::post('verify-2fa', [AuthController::class, 'verify2fa']);


