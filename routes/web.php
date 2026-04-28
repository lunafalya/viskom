<?php

use Illuminate\Support\Facades\Route;


        Route::get('/', function () {
            return view('login');
        });

        Route::get('/dashboard', function () {
            return view('dashboard');
        });

        Route::get('/history', function () {
            return view('history');
        });

        Route::get('/settings', function () {
            return view('settings');
        });