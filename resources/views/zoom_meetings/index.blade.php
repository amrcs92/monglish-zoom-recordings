@extends('layouts.app')

@section('content')

<div class="flex w-full max-w-4xl mx-auto mt-10 justify-center">
    <form id="fetchZoomMeetingsForm" class="bg-white shadow-md rounded-lg px-6 py-4 flex items-center space-x-4">
        @csrf
        <div class="flex items-center">
            <label for="fromDate" class="text-sm font-medium text-gray-700 mr-2 whitespace-nowrap">From:</label>
            <input type="text" id="fromDate" name="fromDate" class="datepicker block w-40 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="YYYY-MM-DD">
        </div>
        <div class="flex items-center">
            <label for="toDate" class="text-sm font-medium text-gray-700 mr-2 whitespace-nowrap">To:</label>
            <input type="text" id="toDate" name="toDate" class="datepicker block w-40 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="YYYY-MM-DD">
        </div>
        <button type="button" id="viewMeetings" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 whitespace-nowrap">
            View Meetings
        </button>
    </form>
</div>

<div class="max-w-3xl mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <h2 class="text-2xl font-bold mb-4">Zoom Meetings</h2>
    <div class="overflow-x-auto shadow-md sm:rounded-lg">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">From</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">To</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Total Recordings</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Meetings</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="zoomMeetingsBody">
                <tr class="bg-yellow-100 hover:bg-gray-50">
                    <td colspan="5" class="text-center">No meetings found</td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" id="meetingsResponse">
    </div>
</div>

{{-- view recordings modal --}}
<div id="meetingsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3">
            <p class="text-2xl font-bold">Zoom Meetings</p>
            <div class="modal-close cursor-pointer z-50">
                <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                    <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-5">
            <table class="min-w-full leading-normal">
                <thead class="bg-blue-800">
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Topic
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Start Time
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Duration
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Recording URL
                        </th>
                    </tr>
                </thead>
                <tbody id="meetingsTableBody">
                    <!-- Table rows will be inserted here by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Flatpickr CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Initialize Datepicker -->
<script>
    
    flatpickr('.datepicker', {
        dateFormat: 'Y-m-d',
        allowInput: true
    });

    const viewMeetingsBtn = document.getElementById('viewMeetings');
    const fetchZoomMeetingsForm = document.getElementById('fetchZoomMeetingsForm');
    const fromDateInput = document.getElementById('fromDate');
    const toDateInput = document.getElementById('toDate');
    const zoomMeetingsTableBody = document.getElementById('zoomMeetingsBody');
    const meetingResponseInput = document.getElementById('meetingsResponse');

    viewMeetingsBtn.addEventListener('click', () => {
        const fromDate = fromDateInput.value;
        const toDate = toDateInput.value;
        if (!fromDate || !toDate) {
            alert('Please select both From and To dates.');
            return;
        }
        fetch('/admin/get-zoom-recordings/' + fromDateInput.value + '/' + toDateInput.value, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("Received data:", data);
            
            if (data) {
                let tableContent = '';
                meetingResponseInput.value = JSON.stringify(data.meetings);
                tableContent += `
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap">${data.from}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${data.to}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${data.total_records}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${data.meetings.length}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="showMeetingDetails()">View recordings</button>
                        </td>
                    </tr>
                `;
                
                zoomMeetingsTableBody.innerHTML = tableContent;
            } else{
                zoomMeetingsTableBody.innerHTML = `
                    <tr class="bg-yellow-100 hover:bg-gray-50">
                        <td colspan="5" class="text-center py-4">No meetings found</td>
                    </tr>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            zoomMeetingsTableBody.innerHTML = `
                <tr class="bg-red-100 hover:bg-gray-50">
                    <td colspan="5" class="text-center py-4">Error fetching meetings</td>
                </tr>
            `;
        });
    });
    
    function showMeetingDetails() {
        const modal = document.getElementById('meetingsModal');
        const tableBody = document.getElementById('meetingsTableBody');
        const closeButton = modal.querySelector('.modal-close');
        const meetings = meetingResponseInput.value;
        const parsedData = JSON.parse(meetings);
        
        console.log('meetings:');
        console.log(meetings);
        console.log('parsed meetings:');
        console.log(parsedData);

        // Clear existing table rows
        meetings.innerHTML = '';

        // Populate the table with meeting data
        parsedData.forEach(meeting => {
            const row = document.createElement('tr');
            
            // Find the MP4 recording file with 'completed' status
            const recordingFile = meeting.recording_files.find(
                file => file.status === 'completed' && file.file_type === 'MP4'
            );
            
            // Construct the recording URL
            const recordingUrl = recordingFile
                ? `${recordingFile.play_url}?pwd=${meeting.recording_play_passcode}`
                : 'No recording available';

            const hours = Math.floor(meeting.duration / 60); // Get the number of hours
            const minutes = meeting.duration % 60;           // Get the remaining minutes
            let durationText;

            if (hours > 0) {
                durationText = `${hours} hour${hours > 1 ? 's' : ''}`; // Handle singular/plural for hours
                if (minutes > 0) {
                    durationText += ` and ${minutes} minute${minutes > 1 ? 's' : ''}`; // Handle singular/plural for minutes
                }
            } else {
                durationText = `${minutes} minute${minutes > 1 ? 's' : ''}`;
            }


            row.innerHTML = `
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <p class="text-gray-900 whitespace-no-wrap">${meeting.topic}</p>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <p class="text-gray-900 whitespace-no-wrap">${new Date(meeting.start_time).toLocaleString()}</p>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <p class="text-gray-900 whitespace-no-wrap">${durationText}</p>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <a href="${recordingUrl}" target="_blank" class="text-blue-600 hover:text-blue-900">
                        ${recordingUrl === 'No recording available' ? recordingUrl : 'View Recording'}
                    </a>
                </td>
            `;
            tableBody.appendChild(row);
        });

        // Show the modal
        modal.classList.remove('hidden');

        // Close modal when clicking the close button
        closeButton.onclick = function() {
            modal.classList.add('hidden');
        };

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.classList.add('hidden');
            }
        };
    }

</script>

@endsection