<?php

namespace App\Exports;

use DateTime;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MeetingsExport implements FromArray, WithHeadings
{
    protected $meetingsData;

    public function __construct(array $meetingsData)
    {
        $this->meetingsData = $meetingsData;
    }

    public function array(): array
    {
        $exportData = [];
        foreach ($this->meetingsData as $meeting) {
            $topic = $meeting['topic'] ?? '';
            $startTime = new DateTime($meeting['start_time']) ?? '';
            $passcode = $meeting['recording_play_passcode'] ?? '';
            $playUrl = '';
            $videoLength = '';

            // Get the duration in minutes
            $durationInMinutes = $meeting['duration'] ?? 0;

            // Convert minutes to hours and minutes
            $hours = floor($durationInMinutes / 60);
            $minutes = $durationInMinutes % 60;

            // Build the video length string
            if ($hours > 0) {
                $videoLength .= $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ';
            }
            if ($minutes > 0) {
                $videoLength .= $minutes . ' minute' . ($minutes > 1 ? 's' : '');
            }

            // If videoLength is empty, ensure to set it to "0 minutes"
            if (empty(trim($videoLength))) {
                $videoLength = '0 minutes';
            }
            
            if (isset($meeting['recording_files'])) {
                foreach ($meeting['recording_files'] as $recording) {
                    if ($recording['file_type'] === 'MP4' && $recording['status'] == 'completed') {
                        $playUrl = $recording['play_url'].'?pwd='.$passcode;
                        break;
                    }
                }
                
                $exportData[] = [
                    'topic' => $topic,
                    'start_time' => $startTime->format('Y-m-d g:i:s a'),
                    'recording_length' => trim($videoLength),
                    'play_url' => $playUrl,
                ];
            }
        }

        return $exportData;
    }

    public function headings(): array
    {
        return [
            'Topic',
            'Date',
            'Recording Length',
            'Play URL',
        ];
    }
}
