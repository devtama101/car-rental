<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/language/{locale}', function (string $locale) {
    if (! in_array($locale, ['en', 'id'])) {
        $locale = 'en';
    }

    session(['locale' => $locale]);

    return redirect()->back();
})->name('language');
