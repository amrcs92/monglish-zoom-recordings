<?php

use App\Http\Controllers\ExportController;
use App\Http\Controllers\ZoomController;
use Illuminate\Support\Facades\Route;

Route::prefix('zoom')->group(function () {
    Route::get('get-recordings', [ZoomController::class, 'getZoomRecordings']);
    Route::get('recordings', [ZoomController::class, 'getRecordings']);
    Route::get('meetings/{userId}', [ZoomController::class, 'getMeetings']);
    Route::get('recordings/details/{meetingId}', [ZoomController::class, 'getRecordingDetails']);
});