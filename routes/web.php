<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

Route::get('/file/{path}', function (string $path) {
    $path = ltrim($path, '/');
    $disk = config('filament.default_filesystem_disk');

    abort_unless(Storage::disk($disk)->exists($path), 404);

    return response()->file(
        Storage::disk($disk)->path($path),
        ['Cache-Control' => 'public, max-age=86400'],
    );
})->where('path', '.*')->name('file.view');
