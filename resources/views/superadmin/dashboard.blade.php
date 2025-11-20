@extends('layouts.app')

@section('content')

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
                {{-- Theme Toggle Button --}}
                <button id="theme-toggle" class="flex items-center gap-2 bg-black/30 backdrop-blur-xl p-2 rounded-lg hover:bg-red-600/40 transition-all duration-300 cursor-pointer">
                    <img id="theme-icon" src="{{ asset('assets/moon.png') }}" class="w-6 h-6 transition-transform duration-500">
                    <span class="font-medium text-white">Dark Mode</span>
                </button>

                {{-- Logout Button with icon --}}
                <form method="POST" action="/logout">
                    @csrf
                    <button class="cursor-pointer flex items-center gap-2 px-5 py-2 bg-red-600 hover:bg-red-500 rounded-lg shadow-md transition-all duration-200 hover:scale-105 text-white">
                        <img src="{{ asset('assets/logout.png') }}" class="w-6 h-6">
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- DASHBOARD CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            @php
                $cards = [
                    ['title' => 'Total Admins', 'value' => 2],
                    ['title' => 'Companies', 'value' => 1],
                    ['title' => 'Active Sessions', 'value' => 21],
                    ['title' => 'System Logs', 'value' => 105],
                ];
            @endphp

            @foreach($cards as $card)
            <div class="backdrop-blur-xl rounded-2xl p-6 shadow-2xl hover:scale-105 transition-all duration-300 cursor-pointer card-text"
                 style="background-color: rgba(38,43,50,0.85);">
                <h3 class="text-xl font-semibold mb-2 transition-colors duration-500">{{ $card['title'] }}</h3>
                <p class="text-3xl font-bold text-red-500 transition-colors duration-500">{{ $card['value'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- DYNAMIC PAGE CONTENT --}}
        <div class="backdrop-blur-xl p-8 rounded-2xl shadow-xl card-text" style="background-color: rgba(38,43,50,0.85);">
            @yield('dashboard-content')
        </div>

    </main>
</div>

{{-- Theme Toggle Script --}}
<script>
const toggleBtn = document.getElementById('theme-toggle');
const wrapper = document.getElementById('dashboard-wrapper');
const icon = document.getElementById('theme-icon');
const btnText = toggleBtn.querySelector('span');

const cardTexts = document.querySelectorAll('.card-text');

// Helper function to set card background and text color
function setCardTheme(mode) {
    cardTexts.forEach(el => {
        if(mode === 'light') {
            el.style.backgroundColor = 'rgba(255,255,255,0.3)';
            el.style.color = 'black';
        } else {
            el.style.backgroundColor = 'rgba(38,43,50,0.85)';
            el.style.color = 'white';
        }
    });
}

// Load saved theme on page load
if(localStorage.getItem('theme') === 'light') {
    wrapper.classList.add('bg-white', 'text-black');
    wrapper.classList.remove('bg-[#1A1F24]', 'text-white');
    icon.src = "{{ asset('assets/sun.png') }}";
    btnText.textContent = 'Light Mode';
    setCardTheme('light');
} else {
    setCardTheme('dark');
}

toggleBtn.addEventListener('click', () => {
    // Spin the icon
    icon.classList.add('rotate-180');
    setTimeout(() => icon.classList.remove('rotate-180'), 500);

    if(wrapper.classList.contains('bg-[#1A1F24]')) {
        // Switch to Light
        wrapper.classList.remove('bg-[#1A1F24]', 'text-white');
        wrapper.classList.add('bg-white', 'text-black');
        icon.src = "{{ asset('assets/sun.png') }}";
        btnText.textContent = 'Light Mode';
        localStorage.setItem('theme', 'light');
        setCardTheme('light');
    } else {
        // Switch to Dark
        wrapper.classList.remove('bg-white', 'text-black');
        wrapper.classList.add('bg-[#1A1F24]', 'text-white');
        icon.src = "{{ asset('assets/moon.png') }}";
        btnText.textContent = 'Dark Mode';
        localStorage.setItem('theme', 'dark');
        setCardTheme('dark');
    }
});
</script>

@endsection
