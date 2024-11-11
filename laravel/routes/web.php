<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');

});


Route::get('/Eeshan', [App\Http\Controllers\EeshansController::class, 'index'])->name('Eeshan.index');
Route::post('/Eeshan', [App\Http\Controllers\EeshansController::class, 'store'])->name('Eeshan.store');
