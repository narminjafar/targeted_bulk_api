<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CampaignController;
use App\Http\Controllers\Api\V1\UnsubscribeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\SegmentController;

Route::prefix('v1')->group(function () {

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::get('unsubscribe/{user}/{campaign}', [UnsubscribeController::class, 'unsubscribe'])
        ->name('unsubscribe')
        ->middleware('signed');

    Route::middleware('auth:api')->group(function () {

        // User info
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);

        // Segment 
        Route::post('segments', [SegmentController::class, 'store']);
        Route::get('segments', [SegmentController::class, 'index']);
        Route::get('segments/{id}', [SegmentController::class, 'show']);
        Route::get('segments/{id}/preview', [SegmentController::class, 'preview']);

        // Campaign 
        Route::post('campaigns', [CampaignController::class, 'store']);

        Route::get('campaigns', [CampaignController::class, 'index']);
        Route::get('campaigns/{id}', [CampaignController::class, 'show']);
        Route::get('campaigns/{id}/stats', [CampaignController::class, 'showWithStats']);
        Route::post('campaigns/{id}/queue', [CampaignController::class, 'send']);
    });
});
