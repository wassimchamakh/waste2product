<?php

use App\Http\Controllers\Frontoffice\DechetController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('FrontOffice.home');
})->name('home');

Route::get('/home', function () {
    return view('FrontOffice.pages.homestats');
})->name('home');


Route::get('/login', function () {
    return view('FrontOffice.auth.login');
})->name('login');

Route::get('/register', function () {
    return view('FrontOffice.auth.register');
})->name('register');



Route::prefix('dechets')->name('dechets.')->group(function () {
    Route::get('/', [DechetController::class, 'index'])->name('index');
    Route::get('/mesdechets', [DechetController::class, 'myDechets'])->name('my');
    Route::get('/create', [DechetController::class, 'create'])->name('create');
    Route::post('/', [DechetController::class, 'store'])->name('store');
    Route::get('/{id}', [DechetController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [DechetController::class, 'edit'])->name('edit');
    Route::put('/{id}', [DechetController::class, 'update'])->name('update');
    Route::delete('/{id}', [DechetController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/reserve', [DechetController::class, 'reserve'])->name('reserve');
});