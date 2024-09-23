<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.png') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Zoom Recording Downloader') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        @auth
            <nav class="fixed top-0 left-0 w-64 h-screen bg-white text-black flex flex-col border-r-2 border-gray-200">
                <div class="px-6 py-10 flex justify-left">
                    <img src="{{ asset('assets/images/monglish-logo.png') }}" alt="Logo" class="h-16">
                </div>
                @if(Auth::user()->is_admin === 1)
                    <ul class="flex flex-col space-y-1">
                        <li class="flex p-2 hover:bg-blue-100 {{ request()->is('admin/export/form/zoom-meetings') ? 'bg-blue-100 text-sky-700 border-r-4 border-sky-800 font-semibold' : '' }}">
                            <a href="{{ route('export.form') }}" class="flex flex-row items-center justify-between">
                                <svg class="mx-2" width="20" height="20" viewBox="0 0 25 25" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path d="M15 21h1v2H3V7h2v1H4v14h11zm3-2H7v-4H6v5h13v-2h-1zM7 5h1V4H6v4h1zm14 10v1H10v-1H9v2h13v-2h-1zM9 1h9.4L22 4.6V8h-1V6h-4V2h-7v6H9zm9 4h3v-.31L18.31 2H18zm2.04 9h-1.063l-2.095-5h1.084l1.546 3.688L21.08 9h1.086zM16 9h-2.651A1.35 1.35 0 0 0 12 10.349v.302A1.35 1.35 0 0 0 13.349 12h1.302a.349.349 0 0 1 .349.349v.302a.349.349 0 0 1-.349.349H12v1h2.651A1.35 1.35 0 0 0 16 12.651v-.302A1.35 1.35 0 0 0 14.651 11H13.35a.349.349 0 0 1-.349-.349v-.302A.349.349 0 0 1 13.35 10H16zm-6.959 5H11v-1H9.041A1.042 1.042 0 0 1 8 11.959v-.918A1.042 1.042 0 0 1 9.041 10H11V9H9.041A2.044 2.044 0 0 0 7 11.041v.918A2.044 2.044 0 0 0 9.041 14z"></path>
                                        <path fill="none" d="M0 0h24v24H0z"></path>
                                    </g>
                                </svg>
                                <span class="w-40">
                                Export CSV
                            </span>
                            </a>
                        </li>
                        <li class="flex p-2 hover:bg-blue-100 {{ request()->is('admin/zoom-meetings') ? 'bg-blue-100 text-sky-700 border-r-4 border-sky-800 font-semibold' : '' }}">
                            <a href="{{ route('zoom-meetings.index') }}" class="flex flex-row items-center justify-between">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mx-2" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                </svg>
                                  
                                <span class="w-40">
                                Zoom Meetings
                            </span>
                            </a>
                        </li>
                    </ul>
                    <li class="flex p-2 border-b-2 border-gray-100" style="position: absolute; bottom: 50px; align-items: center">
                        <span class="w-40 ml-2">
                                {{ Auth::user()->name }}
                        </span>
                        <a class="block px-4 py-2 text-gray-700" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <svg class="mx-2 hover:text-gray-400" width="20" height="21" viewBox="0 0 23 24" fill="none">
                                <path d="M14.9453 1.25C13.5778 1.24998 12.4754 1.24996 11.6085 1.36652C10.7084 1.48754 9.95048 1.74643 9.34857 2.34835C8.82363 2.87328 8.55839 3.51836 8.41916 4.27635C8.28387 5.01291 8.25799 5.9143 8.25196 6.99583C8.24966 7.41003 8.58357 7.74768 8.99778 7.74999C9.41199 7.7523 9.74964 7.41838 9.75194 7.00418C9.75803 5.91068 9.78643 5.1356 9.89448 4.54735C9.99859 3.98054 10.1658 3.65246 10.4092 3.40901C10.686 3.13225 11.0746 2.9518 11.8083 2.85315C12.5637 2.75159 13.5648 2.75 15.0002 2.75H16.0002C17.4356 2.75 18.4367 2.75159 19.1921 2.85315C19.9259 2.9518 20.3144 3.13225 20.5912 3.40901C20.868 3.68577 21.0484 4.07435 21.1471 4.80812C21.2486 5.56347 21.2502 6.56459 21.2502 8V16C21.2502 17.4354 21.2486 18.4365 21.1471 19.1919C21.0484 19.9257 20.868 20.3142 20.5912 20.591C20.3144 20.8678 19.9259 21.0482 19.1921 21.1469C18.4367 21.2484 17.4356 21.25 16.0002 21.25H15.0002C13.5648 21.25 12.5637 21.2484 11.8083 21.1469C11.0746 21.0482 10.686 20.8678 10.4092 20.591C10.1658 20.3475 9.99859 20.0195 9.89448 19.4527C9.78643 18.8644 9.75803 18.0893 9.75194 16.9958C9.74964 16.5816 9.41199 16.2477 8.99778 16.25C8.58357 16.2523 8.24966 16.59 8.25196 17.0042C8.25799 18.0857 8.28387 18.9871 8.41916 19.7236C8.55839 20.4816 8.82363 21.1267 9.34857 21.6517C9.95048 22.2536 10.7084 22.5125 11.6085 22.6335C12.4754 22.75 13.5778 22.75 14.9453 22.75H16.0551C17.4227 22.75 18.525 22.75 19.392 22.6335C20.2921 22.5125 21.0499 22.2536 21.6519 21.6517C22.2538 21.0497 22.5127 20.2919 22.6337 19.3918C22.7503 18.5248 22.7502 17.4225 22.7502 16.0549V7.94513C22.7502 6.57754 22.7503 5.47522 22.6337 4.60825C22.5127 3.70814 22.2538 2.95027 21.6519 2.34835C21.0499 1.74643 20.2921 1.48754 19.392 1.36652C18.525 1.24996 17.4227 1.24998 16.0551 1.25H14.9453Z" fill="currentColor"/>
                                <path d="M15 11.25C15.4142 11.25 15.75 11.5858 15.75 12C15.75 12.4142 15.4142 12.75 15 12.75H4.02744L5.98809 14.4306C6.30259 14.7001 6.33901 15.1736 6.06944 15.4881C5.79988 15.8026 5.3264 15.839 5.01191 15.5694L1.51191 12.5694C1.34567 12.427 1.25 12.2189 1.25 12C1.25 11.7811 1.34567 11.573 1.51191 11.4306L5.01191 8.43056C5.3264 8.16099 5.79988 8.19741 6.06944 8.51191C6.33901 8.8264 6.30259 9.29988 5.98809 9.56944L4.02744 11.25H15Z" fill="currentColor"/>
                            </svg>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            {{ csrf_field() }}
                        </form>
                    </li>
                @endif
            </nav>
        @endauth
        <div class="relative mx-auto top-12">
            @yield('content')
        </div>
    </body>
</html>
