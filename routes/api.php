<?php

use App\Http\Controllers\ExportController;
use App\Http\Controllers\ZoomController;
use Illuminate\Support\Facades\Route;

Route::prefix('zoom')->group(function () {
    Route::controller(ZoomController::class)->group(function () {
        Route::get('get-recordings', 'getZoomRecordings');
        Route::get('recordings', 'getRecordings');
        Route::get('meetings/{userId}', 'getMeetings');
        Route::get('recordings/details/{meetingId}', 'getRecordingDetails');
    });
});