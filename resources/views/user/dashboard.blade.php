@extends('layouts.app')

@section('content')

@vite(['resources/js/app.js', 'resources/js/theme', 'resources/css/app.css'])

{{-- HEADER --}}
<header class="fixed top-0 left-0 w-full bg-black/70 text-white shadow-lg z-50 backdrop-blur-md">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 h-20">

        {{-- Logo --}}
        <div class="flex items-center space-x-3">
            <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="w-16 h-16" />
        </div>

        {{-- Navigation --}}
        <nav class="hidden md:flex items-center space-x-10 text-base font-medium">
            <a href="#" class="hover:text-red-500 transition">HOME</a>
            <a href="#services" class="hover:text-red-500 transition">SERVICES</a>
            <a href="#requirements" class="hover:text-red-500 transition">REQUIREMENTS</a>
            <a href="#contact" class="hover:text-red-500 transition">CONTACT US</a>
        </nav>

        {{-- User / Sign In --}}
        @auth
            <div class="relative group">
                <button class="cursor-pointer flex items-center gap-2 px-4 py-2 rounded-md hover:bg-white/10 transition">
                    <img src="{{ asset('assets/user.png') }}" alt="User" class="w-8 h-8 rounded-full" />
                    <span class="font-medium">{{ auth()->user()->name }}</span>
                </button>

                <div class="absolute right-0 mt-0 w-40 bg-black/80 text-white rounded-md shadow-lg p-3 z-50 backdrop-blur-sm
                            opacity-0 pointer-events-none transform translate-y-2 scale-95
                            transition-all duration-200 ease-out
                            group-hover:opacity-100 group-hover:pointer-events-auto
                            group-hover:translate-y-0 group-hover:scale-100">
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 rounded hover:bg-red-600/30 transition">
                            <img src="{{ asset('assets/logout.png') }}" class="inline w-6 h-6 mr-2" />
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        @else
            <a href="/login" class="border border-white px-4 py-2 rounded-md hover:bg-white hover:text-black transition flex items-center space-x-2">
                <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" class="w-6 h-6" />
                <span class="font-medium">SIGN IN</span>
            </a>
        @endauth
    </div>
</header>

<div class="h-20"></div>

{{-- HOME --}}
<section id="home" class="relative w-full min-h-[750px] lg:min-h-[910px] overflow-hidden">
    <img src="{{ asset('assets/homepage.jpg') }}" class="absolute inset-0 w-full h-full object-cover" />
    <div class="absolute inset-0 bg-black/45"></div>

    <div class="relative z-10 flex flex-col items-center text-center text-white pt-28 space-y-6 max-w-4xl mx-auto px-6">
        <p class="tracking-widest text-2xl lg:text-3xl font-light">Rent. Drive. Explore.</p>
        <h1 class="text-red-600 text-[70px] sm:text-[90px] lg:text-[170px] font-black italic leading-[1]">JRMAX</h1>
        <p class="text-2xl lg:text-3xl">Car Rentals & Driver Services</p>
    </div>

    {{-- BOOK NOW --}}
    <div class="absolute bottom-25 left-1/2 -translate-x-1/2 w-full max-w-[950px]
                bg-black/70 backdrop-blur-xl p-8 rounded-2xl shadow-2xl text-white mx-4">

        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">

            <div class="md:col-span-2">
                <label class="block mb-2">Date</label>
                <div class="grid grid-cols-2 gap-4">
                    <input type="date" class="input-style" />
                    <input type="date" class="input-style" />
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block mb-2">Time</label>
                <div class="grid grid-cols-2 gap-4">
                    <input type="time" class="input-style" />
                    <input type="time" class="input-style" />
                </div>
            </div>

            <div class="flex items-end">
                @auth
                    <a href="" class="w-full">
                        <button class="w-full py-4 rounded-md bg-gradient-to-r from-red-700 to-red-500 hover:scale-[1.03] transition-all duration-300">
                            Book Now
                        </button>
                    </a>
                @else
                    <a href="/login" class="w-full">
                        <button class="w-full py-4 rounded-md bg-gradient-to-r from-red-700 to-red-500 hover:scale-[1.03] transition-all duration-300">
                            Book Now
                        </button>
                    </a>
                @endauth
            </div>

        </div>
    </div>
</section>

{{-- SERVICES --}}
<section id="services" class="bg-[#1A1F24] text-white py-24 min-h-[750px] lg:min-h-[910px]">
    <h2 class="text-5xl text-center font-black tracking-wide mb-14">Services</h2>

    <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10 px-6">

        @php
            $services = [
                ['img'=>'selfdrive.jpg','title'=>'Self Drive','desc'=>'Rent a car and drive yourself','sub'=>'Flexible & Convenient'],
                ['img'=>'citydrive.png','title'=>'City Drive','desc'=>'Professional driver service','sub'=>'Relax & Enjoy the Ride'],
                ['img'=>'tourservice.png','title'=>'With Driver','desc'=>'Car rental with a professional driver','sub'=>'Sit Back & Enjoy the Journey']
            ];
        @endphp

        @foreach ($services as $s)
            <div class="group relative rounded-2xl overflow-hidden shadow-xl cursor-pointer hover:scale-[1.03] transition-all duration-500">
                <img src="{{ asset('assets/' . $s['img']) }}" class="w-full h-[450px] lg:h-[500px] object-cover">
                <div class="absolute inset-0 bg-black/30 group-hover:bg-black/60 transition"></div>

                <div class="absolute inset-0 flex flex-col items-center justify-center
                            opacity-0 translate-y-5
                            group-hover:opacity-100 group-hover:translate-y-0
                            transition-all duration-500">
                    <h3 class="text-3xl font-bold mb-3">{{ $s['title'] }}</h3>
                    <p class="text-lg text-gray-200">{{ $s['desc'] }}</p>
                    <p class="text-gray-300 mt-2">{{ $s['sub'] }}</p>
                </div>
            </div>
        @endforeach

    </div>
</section>

{{-- REQUIREMENTS --}}
<section id="requirements" class="bg-gradient-to-r from-red-600 to-red-500 py-24">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-5xl text-center text-white font-black mb-12">Requirements</h2>
        <div class="text-center text-white space-y-6">
            <p>To rent a vehicle or book a service, ensure you meet the following requirements:</p>
            <ul class="list-disc list-inside space-y-4">
                <li><strong>Age:</strong> Minimum age of 21 years old</li>
                <li><strong>Driver’s License:</strong> Valid license (1 year minimum for self-drive)</li>
                <li><strong>Identification:</strong> Government-issued ID</li>
                <li><strong>Deposit:</strong> Refundable security deposit</li>
                <li><strong>Payment:</strong> Valid credit/debit card</li>
                <li><strong>Insurance:</strong> Optional coverage available</li>
            </ul>
        </div>
    </div>
</section>

{{-- CONTACT --}}
<section id="contact" class="bg-gray-100 py-24">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-5xl text-center font-black mb-12">Contact Us</h2>
        <div class="text-center space-y-6">
            <p>Need help? Get in touch with us anytime.</p>
            <p>Email: <span class="text-red-600">support@jrmax.com</span></p>
            <p>Phone: +1 (800) 123-4567</p>
            <p>Address: 1234 Main Street, Davao City</p>
        </div>
    </div>
</section>

@endsection
