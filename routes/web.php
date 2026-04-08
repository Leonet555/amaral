<?php

use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\LogoController;
use App\Http\Controllers\PwaIconController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

Route::get('/logo', [LogoController::class, 'show']);

Route::get('/manifest.json', function () {
    $path = public_path('manifest.json');
    if (File::exists($path)) {
        return response(File::get($path), 200, ['Content-Type' => 'application/json']);
    }
    return response()->json([
        'name' => 'Vitorum',
        'short_name' => 'Vitorum',
        'description' => 'Sua arena de competição - torneios, atletas e comunidade',
        'start_url' => '/',
        'display' => 'standalone',
        'background_color' => '#f8fafc',
        'theme_color' => '#c41e3a',
        'orientation' => 'portrait-primary',
        'icons' => [
            ['src' => '/icons/icon-192.png', 'sizes' => '192x192', 'type' => 'image/png', 'purpose' => 'any maskable'],
            ['src' => '/icons/icon-512.png', 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any maskable'],
        ],
    ], 200, ['Content-Type' => 'application/json']);
});

Route::get('/icons/icon-192.png', [PwaIconController::class, 'icon192']);
Route::get('/icons/icon-512.png', [PwaIconController::class, 'icon512']);

Route::get('/uploads/{folder}/{filename}', [UploadController::class, 'serve'])
    ->where('folder', 'banners|athletes|posts')
    ->where('filename', '[a-zA-Z0-9._-]+')
    ->name('uploads.serve');

Route::view('/', 'home');
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register');
Route::view('/forgot-password', 'auth.forgot-password');
Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');
Route::view('/dashboard', 'dashboard');
Route::view('/organizer', 'dashboard');
Route::view('/athlete', 'dashboard');
