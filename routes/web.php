<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\FavoriteController;

Route::get('/', [MovieController::class, 'index'])
    ->name('movies.index');

Route::view('/contact', 'contact')
    ->name('contact');

Route::middleware('auth')->group(function () {

    Route::get('/favorites', [FavoriteController::class, 'index'])
        ->name('favorites.index');

    Route::post('/favorites', [FavoriteController::class, 'store'])
        ->name('favorites.store');

    Route::delete('/favorites/{favorite}', [FavoriteController::class, 'destroy'])
        ->name('favorites.destroy');
});

require __DIR__.'/auth.php';