<?php

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