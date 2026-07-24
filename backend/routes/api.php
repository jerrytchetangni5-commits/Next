<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScholarshipController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminScholarshipController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserRecommendationController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AuthGoogleController;
use App\Http\Controllers\GeminiController;
use App\Http\Controllers\NotificationController;


Route::get('/scholarships', [ScholarshipController::class, 'index']);
Route::get('/scholarships/countries', [ScholarshipController::class, 'countries']);
Route::get('/scholarships/search', [ScholarshipController::class, 'search']);
Route::get('/scholarships/countries/{country}/scholarships', [ScholarshipController::class, 'byCountry']);
Route::get('/scholarships/{id}', [ScholarshipController::class, 'show']);



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/auth/login', [AuthGoogleController::class, 'googleLogin']);


Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/user/profile', [UserProfileController::class, 'index']);
    Route::put('/user/profile/recommendation', [UserProfileController::class, 'updateRecommendation']);
    Route::put('/user/profile', [UserProfileController::class, 'update']); 

    Route::get('/user/favorites', [FavoriteController::class, 'index']);    
    Route::post('/user/favorites/{scholarshipId}', [FavoriteController::class, 'toggle']); 

    Route::get('/user/dashboard', [UserDashboardController::class, 'index']);
    Route::get('/user/recommendation', [UserRecommendationController::class, 'index']);  
    
    Route::prefix('gemini')->group(function(){
        Route::post('/chat', [GeminiController::class, 'chat']); 
    });

});

Route::prefix('cv')->group(function(){
    Route::get('/templates', [CvController::class, 'templates']);
    Route::post('/preview', [CvController::class, 'preview']);
    Route::post('/download', [CvController::class, 'download']);

    Route::middleware('auth:sanctum')->group(function(){
        Route::get('/mycvs', [CvController::class, 'mycvs']);
        Route::post('/mycvs', [CvController::class, 'store']);
        Route::get('/mycvs/{id}/download', [CvController::class, 'downloadCv']);
        Route::put('/mycvs/{id}', [CvController::class, 'update']);
        Route::delete('/mycvs/{id}', [CvController::class, 'destroy']);
    });
});

Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);


Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function(){
    Route::prefix('users')->group(function(){
        Route::get('/', [AdminUserController::class, 'index']);
        Route::post('/', [AdminUserController::class, 'store']);
        Route::get('/{id}', [AdminUserController::class, 'show']);
        Route::put('/{id}', [AdminUserController::class, 'update']);
        Route::delete('/{id}', [AdminUserController::class, 'destroy']);    
    });

    Route::prefix('scholarships')->group(function(){
        Route::get('/', [AdminScholarshipController::class, 'index']);
        Route::post('/', [AdminScholarshipController::class, 'store']);
        Route::get('/{id}', [AdminScholarshipController::class, 'show']);
        Route::put('/{id}', [AdminScholarshipController::class, 'update']);
        Route::delete('/{id}', [AdminScholarshipController::class, 'destroy']);    
    });

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AdminController::class, 'profile']);
    Route::put('/profile', [AdminController::class, 'updateProfile']);
});

Route::post('/contact', [ContactController::class, 'store']);
