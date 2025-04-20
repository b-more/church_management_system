<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UssdSessionController;
use App\Http\Controllers\GetInTouchController;
use App\Http\Controllers\Reports\FinancialReportController;
use App\Http\Controllers\GalleryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\EventFrontendController;
use App\Http\Controllers\ReceiptController;



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

// Reports Routes
Route::prefix('reports')->group(function () {
    Route::get('/financial', [FinancialReportController::class, 'generate'])->name('reports.financial');
    Route::get('/tithe', [FinancialReportController::class, 'generateTitheReport'])->name('reports.tithe');
    Route::get('/offering', [FinancialReportController::class, 'generateOfferingReport'])->name('reports.offering');
});

// Receipt Routes
Route::get('/receipt/{transaction}', [ReceiptController::class, 'generate'])->name('receipt.generate');

Route::get('/notices', [NoticeController::class, 'index'])->name('notices.index');
Route::get('/notices/{id}', [NoticeController::class, 'show'])->name('notices.show');

// Public event routes
Route::get('/events', [App\Http\Controllers\EventFrontendController::class, 'index'])->name('events.index');
Route::get('/events/{id}', [App\Http\Controllers\EventFrontendController::class, 'show'])->name('events.show');
Route::get('/events/{id}/register', [App\Http\Controllers\EventFrontendController::class, 'register'])->name('events.register');
Route::post('/events/{id}/register', [App\Http\Controllers\EventFrontendController::class, 'storeRegistration'])->name('events.registration.store');
Route::get('/events/{id}/confirmation/{registration}', [App\Http\Controllers\EventFrontendController::class, 'showConfirmation'])->name('events.registration.confirmation');
