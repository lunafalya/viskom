<?php

use Illuminate\Support\Facades\Route;


        Route::get('/', function () {
            return view('login');
        })->name('login');

        Route::get('/register', function () {
            return view('register');
        })->name('register');

        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::get('/profile', function () {
            return view('profile');
        })->name('profile');

        Route::get('/history', function () {
            return view('history');
        })->name('history');

        Route::get('/watch-history', function () {
            return view('watch_history');
        })->name('watch-history');

        Route::get('/settings', function () {
            return view('settings');
        })->name('settings');

        Route::get('/monitor', function () {
            return view('monitor');
            })->name('monitor');