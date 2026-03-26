<?php
use App\Http\Controllers\CheckoutController;

use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

Route::post('/stripe/webhook', [CheckoutController::class, 'stripeWebhook']);