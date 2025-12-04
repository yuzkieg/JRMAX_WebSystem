<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>JRMAX Dashboard</title>

    @vite('resources/css/app.css')

    <style>
        html { scroll-behavior: smooth; }

        /* Dark/light card backgrounds */
        .dark-card { background-color: rgba(38, 43, 50, 0.85); }
        .light-card { background-color: rgba(255, 255, 255, 0.3); }

        #theme-icon { transition: transform 0.5s ease; }
        .rotate-180 { transform: rotate(180deg); }

        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-calendar-picker-indicator { filter: invert(1); cursor: pointer; }
    </style>
</head>
<body class="transition-colors duration-500">

<div class="flex min-h-screen bg-[#1A1F24] text-white transition-colors duration-500" id="dashboard-wrapper">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-black/80 h-screen fixed top-0 left-0 shadow-xl border-r border-white/10 backdrop-blur-xl transition-all duration-300 hover:w-72">
        <div class="p-6 flex flex-col items-center">
            <img src="{{ asset('assets/logo.png') }}" class="w-20 h-20 mb-4 transition-all duration-300 hover:scale-105">
            <h2 class="text-xl font-bold tracking-wide text-red-500">SUPER ADMIN</h2>
        </div>

        <nav class="mt-10 space-y-2 px-4">
            @php
                $menuItems = [
                    ['name' => 'Dashboard', 'url' => '/superadmin/dashboard'],
                    ['name' => 'Manage Admins', 'url' => '/superadmin/admins'],
                    ['name' => 'Companies', 'url' => '/superadmin/companies'],
                    ['name' => 'System Settings', 'url' => '/superadmin/settings'],
                    ['name' => 'Activity Logs', 'url' => '/superadmin/logs'],
                ];
            @endphp

            @foreach($menuItems as $item)
            <a href="{{ $item['url'] }}"
               class="block py-3 px-4 rounded-lg hover:bg-red-600/40 hover:translate-x-2 transition-all duration-300 text-white">
                {{ $item['name'] }}
            </a>
            @endforeach
        </nav>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="ml-64 w-full min-h-screen p-8 transition-all duration-300">

        {{-- TOP HEADER --}}
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-red-500 drop-shadow-lg">JRMAX Car Rentals Inc.</h1>

            <div class="flex items-center space-x-4">
                <button id="theme-toggle" class="flex items-center gap-2 bg-black/30 backdrop-blur-xl p-2 rounded-lg hover:bg-red-600/40 transition-all duration-300 cursor-pointer">
                    <img id="theme-icon" src="{{ asset('assets/moon.png') }}" class="w-6 h-6 transition-transform duration-500">
                    <span class="font-medium text-white">Dark Mode</span>
                </button>

                <form method="POST" action="/logout">
                    @csrf
                    <button class="flex items-center gap-2 px-5 py-2 bg-red-600 hover:bg-red-500 rounded-lg shadow-md transition-all duration-200 hover:scale-105 text-white">
                        <img src="{{ asset('assets/logout.png') }}" class="w-6 h-6">
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- DASHBOARD CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            @php
                $cards = $cards ?? [];
            @endphp

            @foreach($cards as $card)
            <div class="backdrop-blur-xl rounded-2xl p-6 shadow-2xl hover:scale-105 transition-all duration-300 cursor-pointer card-text dark-card">
                <h3 class="text-xl font-semibold mb-2">{{ $card['title'] }}</h3>
                <p class="text-3xl font-bold text-red-500">{{ $card['value'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- DYNAMIC PAGE CONTENT --}}
        <div class="backdrop-blur-xl p-8 rounded-2xl shadow-xl card-text dark-card">
            @yield('dashboard-content')
        </div>

    </main>
</div>

<script src="{{ asset('js/theme.js') }}"></script>
@yield('scripts')
</body>
</html>
