<?php

use App\Http\Controllers\Api\V1\CampaignController;
use App\Http\Controllers\Api\V1\GameSessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function (): void {
    Route::middleware('auth:sanctum')->group(function (): void {
        Route::apiResource('campaigns', CampaignController::class);

        // shallow(): list/create nest under a campaign, but single-session
        // routes are just /sessions/{game_session}.
        // parameters(): renames {session} to {game_session} so it matches the
        // $gameSession argument in the controller. Route model binding only
        // works when the names match.
        Route::apiResource('campaigns.sessions', GameSessionController::class)
            ->shallow()
            ->parameters(['sessions' => 'game_session']);
    });
});
