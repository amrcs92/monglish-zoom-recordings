<?php

namespace App\Http\Controllers;

use App\Exports\MeetingsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ZoomController;
use App\Services\ZoomService;

class ExportController extends Controller
{

    protected $zoomController;
    protected $zoomService;
    
    public function __construct()
    {
        $this->zoomService = new ZoomService();
        $this->zoomController = new ZoomController($this->zoomService);
    }

    public function index()
    {
        return view('export.csv');
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'fromDate' => 'required|date',
            'toDate' => 'required|date|after_or_equal:fromDate',
        ], [
            'fromDate.required' => 'From date is required.',
            'fromDate.date' => 'From date must be a valid date.',
            'toDate.required' => 'To date is required.',
            'toDate.date' => 'To date must be a valid date.',
            'toDate.after_or_equal' => 'To date must be a date after or equal to the from date.',
        ]);

        $recordingsData = $this->zoomController->fetchRecordings($validated['fromDate'], $validated['toDate']);
        
        if (is_array($recordingsData)) {
            if(!empty($recordingsData['meetings'])) {
                $meetingsData = $recordingsData['meetings'];

                $timestamp = date('Y-m-d_H-i-s'); // This format is safe for filenames
                $filename = 'meetings-' . $timestamp . '.xlsx';
                // Create the Excel file and store it temporarily on the server
                Excel::store(new MeetingsExport($meetingsData), 'public/meeting/' . $filename);

                Excel::download(new MeetingsExport($meetingsData), $filename);
                
                // Set a success message and the file path in the session
                return redirect()->route('export.form')->with([
                    'success' => 'Meetings exported successfully.',
                    'filename' => $filename,
                ]);
                
            } else {
                return redirect()->route('export.form')->with('error', 'No meetings found.');
            }
        }

        // Handle the case where the data is null or invalid
        return redirect()->route('export.form')->with('error', 'Invalid recordings data');
    }

    public function downloadFile($filename)
    {
        $filePath = storage_path('app/public/meeting/' . $filename);

        if (file_exists($filePath)) {
            return response()->download($filePath)->deleteFileAfterSend();
        } else {
            return redirect()->route('export.form')->with('error', 'File not found.');
        }
    }
    
}
