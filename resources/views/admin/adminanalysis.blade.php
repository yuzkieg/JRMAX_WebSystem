@extends('layouts.app')

@section('content')
@vite('resources/css/app.css')

<div class="flex min-h-screen bg-[#1A1F24] text-white transition-colors duration-500">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-black/80 h-screen fixed top-0 left-0 shadow-xl border-r border-white/10 backdrop-blur-xl transition-all duration-300 hover:w-72">
        <div class="p-6 flex flex-col items-center">
            <img src="{{ asset('assets/logo.png') }}" class="w-20 h-20 mb-4 transition-all duration-300 hover:scale-105">
            <h2 class="text-xl font-bold tracking-wide text-red-500">ADMIN</h2>
        </div>

        @php
            $menuItems = [
                ['name' => 'Analysis', 'url' => '/admin/adminanalysis'],
                ['name' => 'HR Management', 'url' => '/admin/adminhr'],
                ['name' => 'Vehicle Management', 'url' => '/admin/vehicles'],
                ['name' => 'Vehicle Maintenance', 'url' => '/admin/maintenance'],
                ['name' => 'User Management', 'url' => '/admin/users'],
                ['name' => 'Reports', 'url' => '/admin/reports'],
                ['name' => 'Booking', 'url' => '/admin/booking'],
            ];
        @endphp

        <nav class="mt-10 space-y-2 px-4">
            @foreach ($menuItems as $item)
                <a href="{{ $item['url'] }}"
                    class="block py-3 px-4 rounded-lg 
                    hover:bg-red-600/40 hover:translate-x-2 transition-all duration-300 text-white">
                    {{ $item['name'] }}
                </a>
            @endforeach
        </nav>
    </aside>

    {{-- MAIN CONTENT WRAPPER --}}
    <main id="dashboard-wrapper" class="ml-64 w-full min-h-screen p-8 transition-all duration-300">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-red-500 drop-shadow-lg">Analysis Overview</h1>

            <div class="flex items-center space-x-4">

                {{-- Theme Toggle --}}
                <button id="theme-toggle"
                    class="flex items-center gap-2 bg-black/30 backdrop-blur-xl p-2 rounded-lg hover:bg-[#998282] transition-all duration-300 cursor-pointer">
                    <img id="theme-icon" src="{{ asset('assets/moon.png') }}"
                         class="w-6 h-6 transition-transform duration-500">
                    <span class="font-medium text-white">Dark Mode</span>
                </button>

                {{-- Logout --}}
                <form method="POST" action="/logout">
                    @csrf
                    <button class="cursor-pointer flex items-center gap-2 px-5 py-2 bg-[#742121] hover:bg-red-500 rounded-lg shadow-md transition-all duration-200 hover:scale-105 text-white">
                        <img src="{{ asset('assets/logout.png') }}" class="w-6 h-6">
                        <span>Logout</span>
                    </button>
                </form>

            </div>
        </div>

        {{-- ANALYSIS CARDS --}}
        @php
            $analysisCards = [
                ['title' => 'Total Bookings', 'value' => 240],
                ['title' => 'Completed Trips', 'value' => 198],
                ['title' => 'Active Vehicles', 'value' => 32],
                ['title' => 'Total Employees', 'value' => 41],
                ['title' => 'Pending Reports', 'value' => 7],
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-10">
            @foreach ($analysisCards as $card)
                <div class="backdrop-blur-xl rounded-2xl p-6 shadow-2xl hover:scale-105 transition-all duration-300 cursor-pointer card-text"
                     style="background-color: rgba(38,43,50,0.85);">
                    <h3 class="text-md font-medium">{{ $card['title'] }}</h3>
                    <p class="text-3xl font-bold text-red-500">{{ $card['value'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- CHARTS SECTION --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">

            {{-- Chart 1 --}}
            <div class="modal-content backdrop-blur-xl p-6 rounded-2xl shadow-xl card-text dark-card" style="min-height: 300px;">
                <h2 class="text-xl font-bold text-red-500 mb-4">Monthly Booking Trends</h2>
            </div>

            {{-- Chart 2 --}}
            <div class="modal-content backdrop-blur-xl p-6 rounded-2xl shadow-xl card-text dark-card" style="min-height: 300px;">
                <h2 class="text-xl font-bold text-red-500 mb-4">Vehicle Utilization</h2>
            </div>

        </div>

        {{-- TABLE SECTION --}}
        <div class="modal-content backdrop-blur-xl p-8 rounded-2xl shadow-xl card-text dark-card mb-20">

            <h2 class="text-2xl font-bold text-red-500 mb-6">Recent Activity Logs</h2>

            <table class="w-full text-left border-collapse dark-table">
                <thead>
                    <tr class="border-b border-white/10">
                        <th class="py-3 px-2">User</th>
                        <th class="py-3 px-2">Action</th>
                        <th class="py-3 px-2">Details</th>
                        <th class="py-3 px-2">Date</th>
                    </tr>
                </thead>

                <tbody>
                    <tr class="border-b border-white/5">
                        <td class="py-3 px-2">Admin</td>
                        <td class="py-3 px-2">Updated Booking</td>
                        <td class="py-3 px-2">BK-3028</td>
                        <td class="py-3 px-2">Nov 24, 2025</td>
                    </tr>

                    <tr class="border-b border-white/5">
                        <td class="py-3 px-2">Manager</td>
                        <td class="py-3 px-2">Added Vehicle</td>
                        <td class="py-3 px-2">Toyota Hilux 2024</td>
                        <td class="py-3 px-2">Nov 23, 2025</td>
                    </tr>

                    <tr>
                        <td class="py-3 px-2">HR</td>
                        <td class="py-3 px-2">Added Employee</td>
                        <td class="py-3 px-2">Maria Santos</td>
                        <td class="py-3 px-2">Nov 23, 2025</td>
                    </tr>
                </tbody>
            </table>

        </div>

    </main>
</div>
@endsection
