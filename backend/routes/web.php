<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/reset-password/{token}', function ($token) {
    return redirect('https://next-hj.vercel.app/reset-password/' . $token);
})->name('password.reset');


Route::post('/cron/sync-scholarships', function (Request $request) {
    if ($request->header('X-Cron-Secret') !== config('services.cron_secret')) {
        abort(403);
    }

    $data = $request->input('scholarships', []);
    $path = storage_path('app/scholarships-sync.json');
    file_put_contents($path, json_encode($data));

    Artisan::call('scholarships:import', ['path' => $path]);

    return response()->json(['status' => 'ok', 'count' => count($data)]);
});

Route::post('/cron/notify-deadlines', function (Request $request) {
    if ($request->header('X-Cron-Secret') !== config('services.cron_secret')) {
        abort(403);
    }

    Artisan::call('notify:deadlines');

    return response()->json(['status' => 'ok']);
});

