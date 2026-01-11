@extends('layouts.app')

@section('content')
@vite('resources/css/app.css')


<div class="flex min-h-screen bg-[#1A1F24] text-white transition-colors duration-500">

    {{-- SIDEBAR --}}
    <aside id="sidebar" class="w-64 bg-black/80 h-screen fixed top-0 left-0 shadow-xl border-r border-white/10 backdrop-blur-xl transition-all duration-300 hover:w-72 flex flex-col">
        <div class="p-6 flex flex-col items-center">
            <img src="{{ asset('assets/logo.png') }}" class="w-20 h-20 mb-4 transition-all duration-300 hover:scale-105">
            <h2 class="text-xl font-bold tracking-wide text-red-500">ADMIN</h2>
        </div>

        @php
            $menuItems = [
                ['name' => 'Dashboard', 'url' => '/admin/admindashboard'],
                ['name' => 'Analysis', 'url' => '/admin/adminanalysis'],
                ['name' => 'HR Management', 'url' => '/admin/adminhr'],
                ['name' => 'Vehicle Management', 'url' => '/admin/vehicles'],
                ['name' => 'Booking', 'url' => '/admin/booking'],
            ];
        @endphp

        <nav class="mt-10 space-y-2 px-4 flex-1 overflow-y-auto">
            @foreach ($menuItems as $item)
                @php
                    $isActive = request()->is(ltrim($item['url'],'/'));
                @endphp
                <a href="{{ $item['url'] }}"
                    class="block py-3 px-4 rounded-lg transition-all duration-300 text-white 
                    {{ $isActive ? 'bg-red-600/60 translate-x-2' : 'hover:bg-red-600/40 hover:translate-x-2' }}">
                    {{ $item['name'] }}
                </a>
            @endforeach
        </nav>

        {{-- Logout Button at Bottom --}}
        <div class="p-4 mt-auto">
            <form method="POST" action="/logout" id="logoutForm" class="w-full">
                @csrf
                <button type="button" onclick="confirmLogout(event)" class="flex items-left gap-2 py-3 px-4 w-full bg-red-700 hover:bg-red-500 rounded-xl text-white shadow-lg transition-all duration-300 font-bold">
                    <img src="{{ asset('assets/logout.png') }}" class="w-6 h-6">
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
<main id="dashboard-wrapper"
      class="ml-64 w-full min-h-screen p-8 transition-all duration-300">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-red-500 drop-shadow-lg">Admin Dashboard</h1>

            <div class="flex items-center space-x-4">

                {{-- Theme Toggle --}}
                <button id="theme-toggle" class="flex items-center gap-2 bg-black/30 backdrop-blur-xl p-2 rounded-lg hover:bg-[#998282] transition-all duration-300 cursor-pointer">
                    <img id="theme-icon" src="{{ asset('assets/moon.png') }}" class="w-6 h-6 transition-transform duration-500">
                    <span class="font-medium text-white">Dark Mode</span>
                </button>

            </div>
        </div>

        {{-- DASHBOARD CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            @php
                $cards = [
                    ['title' => 'Active Bookings', 'value' => 12],
                    ['title' => 'Available Vehicles', 'value' => 34],
                    ['title' => 'Employees', 'value' => 18],
                    ['title' => 'Reports Generated', 'value' => 52],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="backdrop-blur-xl rounded-2xl p-6 shadow-2xl hover:scale-105 transition-all duration-300 cursor-pointer card-text"
                    style="background-color: rgba(38,43,50,0.85);">
                    <h3 class="text-xl font-semibold mb-2">{{ $card['title'] }}</h3>
                    <p class="text-3xl font-bold text-red-500">{{ $card['value'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- PAGE CONTENT --}}
        <div class="modal-content backdrop-blur-xl p-8 rounded-2xl shadow-xl card-text dark-card">
            <h2 class="text-2xl font-bold text-red-500 mb-6">Welcome, {{ auth()->user()->name }}!</h2>

            <p class="text-gray-300 leading-relaxed">
                What's the agenda today?
            </p>
        </div>

    </main>
</div>

<script>
    function confirmLogout(event) {
        event.preventDefault();
        if (confirm('Are you sure you want to logout?')) {
            document.getElementById('logoutForm').submit();
        }
    }
</script>
@endsection
