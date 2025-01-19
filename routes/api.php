<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UssdSessionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// USSD Routes - no leading slash in the path
Route::post('ussd/callback', [UssdSessionController::class, 'ussd'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// Test route to verify API is working
Route::get('test', function() {
    return response()->json(['message' => 'API is working']);
});