@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <h2 class="text-2xl font-bold mb-4">Export Meetings to CSV</h2>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <strong>Error:</strong> {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            <strong>Success:</strong> {{ session('success') }}
            <a href="{{ route('export.download', ['filename' => session('filename')]) }}" class="text-blue-500 underline">
                Download the exported file
            </a>
        </div>
    @endif

    <form action="{{ route('export.csv') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="fromDate" class="block text-sm font-medium text-gray-700">From Date</label>
            <input type="text" id="fromDate" name="fromDate" class="datepicker mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Select a start date">
        </div>

        <div class="mb-4">
            <label for="toDate" class="block text-sm font-medium text-gray-700">To Date</label>
            <input type="text" id="toDate" name="toDate" class="datepicker mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Select an end date">
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Export to CSV
            </button>
        </div>
    </form>
</div>

<!-- Flatpickr CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Initialize Datepicker -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr('.datepicker', {
            dateFormat: 'Y-m-d',
            allowInput: true
        });
    });
</script>
    
@endsection