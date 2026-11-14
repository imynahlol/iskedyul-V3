<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Public landing page
Route::get('/', function () {
    return view('welcome');
});

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.store');

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.store');
});

// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

Route::get('/admin/buildings/{building}/rooms/print', function (\App\Models\Building $building) {
    abort_unless(Auth::check() && Auth::user()->user_type === 'Admin', 403);

    $rooms = $building->rooms()
        ->orderBy('room_code')
        ->get();

    $settings = \App\Models\SystemSetting::pluck('value', 'key');

    return view('print.building-rooms', [
        'building' => $building,
        'rooms' => $rooms,
        'settings' => $settings,
    ]);
})->middleware('auth')->name('admin.buildings.rooms.print');


Route::get('/admin/buildings/print', function () {
    abort_unless(Auth::check() && Auth::user()->user_type === 'Admin', 403);

    $buildings = \App\Models\Building::withCount('rooms')
        ->orderBy('building_name')
        ->get();

    $settings = \App\Models\SystemSetting::pluck('value', 'key');

    return view('print.building-directory', [
        'buildings' => $buildings,
        'settings' => $settings,
    ]);
})->middleware('auth')->name('admin.buildings.print');
