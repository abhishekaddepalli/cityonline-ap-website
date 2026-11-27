<?php

use App\Http\Controllers\Api\PublicApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/init', [PublicApiController::class, 'getInitData']);
    Route::get('/plans', [PublicApiController::class, 'getPlans']);
    Route::get('/sections', [PublicApiController::class, 'getSections']);
    Route::get('/brands', [PublicApiController::class, 'getBrands']);
    Route::get('/localities', [PublicApiController::class, 'getLocalities']);
    Route::get('/faqs', [PublicApiController::class, 'getFaqs']);

    // Public Form Intakes
    Route::post('/enquiries', [PublicApiController::class, 'submitEnquiry']);
    Route::post('/support', [PublicApiController::class, 'submitSupportTicket']);
    Route::post('/chat', [\App\Http\Controllers\Api\ChatController::class, 'handleChat']);
});
