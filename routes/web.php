<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UssdSessionController;
use App\Http\Controllers\GetInTouchController;
use App\Http\Controllers\GalleryController;
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

Route::get('/services', [ServiceController::class, 'index'])->name('services');
Route::get('/services/archive', [ServiceController::class, 'archive'])->name('services.archive');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

// Route::post('/ussd/callback', [UssdSessionController::class, 'ussd'])
//     ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
//     ->name('ussd.callback');
// Route::get('/ussd/status', function () {
//     return response()->json([
//         'status' => 'active',
//         'message' => 'USSD service is running'
//     ]);
// });

Route::post('/contact', [GetInTouchController::class, 'store'])->name('contact.submit');


Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
