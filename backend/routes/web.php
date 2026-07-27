<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/reset-password/{token}', function ($token) {
    return redirect('https://next-hj.vercel.app/reset-password/' . $token);
})->name('password.reset');




