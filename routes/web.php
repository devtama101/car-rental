<?php

use App\Http\Controllers\BookingReceiptController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/cars', function () {
    return view('cars');
})->name('cars.index');

Route::get('/booking/{reference}/receipt', BookingReceiptController::class)
    ->where('reference', 'BRK-[A-Z0-9]+')
    ->name('booking.receipt');

Route::get('/file/{path}', function (string $path) {
    $path = ltrim($path, '/');
    $disk = config('filament.default_filesystem_disk');

    abort_unless(Storage::disk($disk)->exists($path), 404);

    return response()->file(
        Storage::disk($disk)->path($path),
        ['Cache-Control' => 'public, max-age=86400'],
    );
})->where('path', '.*')->name('file.view');
