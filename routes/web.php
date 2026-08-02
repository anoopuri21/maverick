<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

// Homepage
Route::get('/', [PageController::class, 'home'])->name('home');

// Dual MBA Programme
Route::get('/dual-degrees', [PageController::class, 'dualMba'])->name('dual-mba');

// About Pages
Route::get('/our-story', [PageController::class, 'ourStory'])->name('our-story');
Route::get('/about-us', [PageController::class, 'aboutUs'])->name('about-us');
Route::get('/leadership-board', function () {return view('pages.leadership');})->name('leadership');
Route::get('/accreditations', function () {return view('pages.accreditations');})->name('accreditations');
Route::get('/csr-community-impact', [PageController::class, 'csrCommunityImpact'])->name('csr');
Route::get('/media-gallery', function () {return view('pages.media-gallery');})->name('media-gallery');
Route::get('/global-university-partners', function () {return view('pages.global-university-partners');})->name('global-partners');
Route::get('/pathway-programs', function () {return view('pages.global-bachelors-pathway');})->name('pathway-programs');

// Edutainment
Route::get('/educational-tours-edutainment', function () {return view('pages.edutainment');})->name('edutainment');

// Contact
Route::get('/contact', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact');
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'submit'])
    ->middleware('throttle:5,1')
    ->name('contact.submit');

// Gallery
Route::get('/media-gallery', [PageController::class, 'gallery'])->name('gallery');

// Blogs
Route::get('/blogs', [\App\Http\Controllers\BlogController::class, 'index'])->name('blogs.index');

// News
Route::get('/news', [\App\Http\Controllers\NewsController::class, 'index'])->name('news.index');

// Unified Detail (WordPress-style root-level permalink)
// Registered LAST so every other named route above is matched first.
Route::get('/{slug}', [\App\Http\Controllers\InsightController::class, 'show'])->name('insights.show');