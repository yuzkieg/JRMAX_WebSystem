@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Super Admin Dashboard</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#1A1F24] text-white flex">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-black/80 h-screen fixed top-0 left-0 shadow-xl border-r border-white/10 backdrop-blur-xl">
        <div class="p-6 flex flex-col items-center">
            <img src="{{ asset('assets/logo.png') }}" class="w-20 h-20 mb-4">

            <h2 class="text-xl font-bold tracking-wide text-red-500">SUPER ADMIN</h2>
        </div>

        <nav class="mt-10 space-y-2 px-4">
            <a href="/superadmin/dashboard"
               class="block py-3 px-4 rounded-lg hover:bg-red-600/40 transition">Dashboard</a>

            <a href="/superadmin/admins"
               class="block py-3 px-4 rounded-lg hover:bg-red-600/40 transition">Manage Admins</a>

            <a href="/superadmin/companies"
               class="block py-3 px-4 rounded-lg hover:bg-red-600/40 transition">Companies</a>

            <a href="/superadmin/settings"
               class="block py-3 px-4 rounded-lg hover:bg-red-600/40 transition">System Settings</a>

            <a href="/superadmin/logs"
               class="block py-3 px-4 rounded-lg hover:bg-red-600/40 transition">Activity Logs</a>
        </nav>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="ml-64 w-full min-h-screen p-8">

        {{-- TOP HEADER --}}
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">Super Admin Dashboard</h1>

            <form method="POST" action="/logout">
                @csrf
                <button class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded-lg">
                    Logout
                </button>
            </form>
        </div>

        {{-- DYNAMIC PAGE CONTENT --}}
        <div>
            @yield('content')
        </div>

    </main>

</body>
</html>

@endsection
