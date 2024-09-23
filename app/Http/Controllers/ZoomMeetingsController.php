<?php

namespace App\Http\Controllers;

use App\Services\ZoomService;
use Illuminate\Http\Request;

class ZoomMeetingsController extends Controller
{
    protected $zoomController;

    public function __construct()
    {
        $this->zoomController = new ZoomController(new ZoomService());
    }
    public function index()
    {
        // $zoomRecordings = $this->zoomController->getZoomRecordings();
        // dd(json_decode($zoomRecordings->getContent()));
        return view('zoom_meetings.index');
    }
}
