<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// In routes/web.php

// Protect routes with role middleware
Route::middleware(['role:admin'])->group(function () {
    // Only users with admin role can access these routes
});

// Protect routes with permission middleware
Route::middleware(['permission:edit members'])->group(function () {
    // Only users with 'edit members' permission can access these routes
});

// Protect routes with role OR permission middleware
Route::middleware(['role_or_permission:admin|edit members'])->group(function () {
    // Users with either admin role OR edit members permission can access these routes
});

// Combine multiple middleware
Route::middleware(['auth', 'role:admin', 'verified'])->group(function () {
    // Only authenticated, verified admins can access these routes
});

Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::post('/newsletter', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/about', function () {
    return view('about');
})->name('about');  // This adds the name to the route

Route::get('/services', function () {
    return view('services');
})->name('services');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');