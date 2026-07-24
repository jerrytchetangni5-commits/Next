<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/reset-password/{token}', function ($token) {
    return redirect('https://next-hj.vercel.app/reset-password/' . $token);
})->name('password.reset');




Route::get('/test-smtp', function () {
    try {
        Mail::raw('Ceci est un test SMTP', function ($message) {
            $message->to('0v51iia0kc@yzcalo.com')->subject('Test SMTP Render');
        });
        return response()->json(['status' => 'sent']);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'class' => get_class($e),
        ], 500);
    }
});