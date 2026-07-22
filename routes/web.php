<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

Route::middleware('guest')->group(function () {
    Route::get('login', [\App\Http\Controllers\Auth\LoginController::class, 'show'])->name('login');
    Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'store']);
});

// Homepage
Route::get('/', [PageController::class, 'home'])->name('home');

// About Pages
Route::get('/about-us/our-story', [PageController::class, 'ourStory'])->name('our-story');
Route::get('/about-us', [PageController::class, 'aboutUs'])->name('about-us');
Route::get('/about-us/leadership-board', [PageController::class, 'founder'])->name('founder');

// Contact
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactSubmit'])->name('contact.submit');

// Gallery
Route::get('/about-us/media-gallery', [PageController::class, 'gallery'])->name('gallery');