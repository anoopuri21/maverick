<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

// Homepage
Route::get('/', [PageController::class, 'home'])->name('home');

// Dual MBA Programme
Route::get('/dual-mba-online', [PageController::class, 'dualMba'])->name('dual-mba');

// About Pages
Route::get('/our-story', [PageController::class, 'ourStory'])->name('our-story');
Route::get('/about-us', [PageController::class, 'aboutUs'])->name('about-us');
Route::get('/leadership-board', [PageController::class, 'leadershipBoard'])->name('leadership');
Route::get('/accreditations', [\App\Http\Controllers\AccreditationController::class, 'index'])->name('accreditations');
Route::get('/csr-community-impact', [PageController::class, 'csrCommunityImpact'])->name('csr');
Route::get('/media-gallery', [PageController::class, 'gallery'])->name('media-gallery');
Route::get('/global-university-partners', [PageController::class, 'globalUniversityPartners'])->name('global-partners');
Route::get('/global-bachelors-pathway', function () {return view('pages.global-bachelors-pathway');})->name('global-bachelors-pathway');
Route::get('/masters-pathways', function () {return view('pages.masters-pathway');})->name('masters-pathways');

// Edutainment
Route::get('/educational-tours-edutainment', function () {return view('pages.edutainment');})->name('edutainment');

// Contact
Route::get('/contact', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact');
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'submit'])
    ->middleware('throttle:5,1')
    ->name('contact.submit');


// Blogs
Route::get('/blogs', [\App\Http\Controllers\BlogController::class, 'index'])->name('blogs.index');

// News
Route::get('/news', [\App\Http\Controllers\NewsController::class, 'index'])->name('news.index');

// Programmes — must be registered before the catch-all slug route below
Route::get('/programs', [\App\Http\Controllers\ProgramController::class, 'index'])->name('programs.index');
Route::get('/programs/{slug}', [\App\Http\Controllers\ProgramController::class, 'show'])->name('programs.show');

// Unified Detail (WordPress-style root-level permalink)
// Registered LAST so every other named route above is matched first.


Route::get('/events', [\App\Http\Controllers\PageController::class, 'events'])->name('events');
Route::get('/student-success', [\App\Http\Controllers\PageController::class, 'studentSuccess'])->name('student-success');

Route::get('/{slug}', [\App\Http\Controllers\InsightController::class, 'show'])->name('insights.show');