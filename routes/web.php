<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

// Public: redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});

// All app routes require authentication
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        $user = auth()->user();
        $totalSeconds = $user->sessions()->sum('duration_seconds');
        $totalHours = round($totalSeconds / 3600, 1);
        return view('dashboard', compact('totalHours'));
    })->name('dashboard');

    Route::get('/monitor', function () {
        $settings = auth()->user()->getOrCreateSettings();
        return view('monitor', compact('settings'));
    })->name('monitor');

    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    Route::get('/history/export', [HistoryController::class, 'export'])->name('history.export');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/picture', [ProfileController::class, 'uploadPicture'])->name('profile.picture');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Monitor session API
    Route::post('/api/monitor/start', [MonitorController::class, 'start'])->name('monitor.start');
    Route::post('/api/monitor/stop', [MonitorController::class, 'stop'])->name('monitor.stop');
});

require __DIR__.'/auth.php';
