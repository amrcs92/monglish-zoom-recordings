<?php

namespace App\Http\Controllers;

use App\Services\ZoomService;
use Illuminate\Http\Request;

class ZoomController extends Controller
{
    protected $zoomService;

    public function __construct(ZoomService $zoomService)
    {
        $this->zoomService = $zoomService;
    }

    public function getZoomRecordings()
    {
        $recordings = $this->zoomService->fetchRecordings('2024-08-27', '2024-08-28');
        return response()->json($recordings);
    }

    // Fetch recordings for a specific user
    public function getRecordings($fromDate, $toDate)
    {
        $recordings = $this->zoomService->fetchRecordings($fromDate, $toDate);
        return response()->json($recordings);
    }

    public function fetchRecordings($fromDate, $toDate)
    {
        return $this->zoomService->fetchRecordings($fromDate, $toDate);
    }

    public function getMeetings($userId)
    {
        $meetings = $this->zoomService->getMeetings($userId);
        return response()->json($meetings);
    }

    // Fetch details for a specific recording
    public function getRecordingDetails($meetingId)
    {
        $recordingDetails = $this->zoomService->getRecordingDetails($meetingId);
        return response()->json($recordingDetails);
    }
}
