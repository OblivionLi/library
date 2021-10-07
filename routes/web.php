<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layout.app');
});

Route::pattern('path', '[a-zA-Z0-9-/]+');
Route::any('{path}', function($page) {
    return view('layout.app');
});

