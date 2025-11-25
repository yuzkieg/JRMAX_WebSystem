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
                <a href="#vehicles" class="hover:text-red-500 transition">FLEET</a>
                <a href="#requirements" class="hover:text-red-500 transition">REQUIREMENTS</a>
                <a href="#contact" class="hover:text-red-500 transition">CONTACT US</a>

            </nav>

            {{-- Sign In --}}
            <a href="/login" class="border border-white px-4 py-2 rounded-md hover:bg-white hover:text-black transition flex items-center space-x-2">
                <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="User Icon" class="w-6 h-6" />
                <span class="font-medium">SIGN IN</span>
            </a>
        </div>
    </header>

    <div class="h-20"></div>



    {{-- HOME SECTION --}}
    <section id="home" class="relative w-full min-h-[750px] lg:min-h-[910px] overflow-hidden">

        <img src="{{ asset('assets/homepage.jpg') }}" alt="Background"
            class="absolute inset-0 w-full h-full object-cover" />

        <div class="absolute inset-0 bg-black/45"></div>

        <div class="relative z-10 flex flex-col items-center text-center text-white pt-28 space-y-6 max-w-4xl mx-auto px-6">
            <p class="tracking-widest text-2xl lg:text-3xl font-light">Rent. Drive. Explore.</p>

            <h1 class="text-red-600 text-[70px] sm:text-[90px] lg:text-[120px] font-black italic drop-shadow-xl leading-[1]">
                JRMAX
            </h1>

            <p class="text-2xl lg:text-3xl drop-shadow-xl">Car Rentals & Tour Services</p>
        </div>


        {{-- Booking Box --}}
        <div class="absolute bottom-25 left-1/2 -translate-x-1/2 w-full max-w-[950px] 
                    bg-black/70 backdrop-blur-xl p-8 rounded-2xl shadow-2xl text-white mx-4 book-now-blur">

            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">

                {{-- Date --}}
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <img src="assets/calendar.png" class="w-6 h-6" />
                        <span class="text-xl font-medium">Date</span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">From</label>
                            <input type="date"
                                class="w-full px-4 py-3 rounded-lg bg-white/10 border border-gray-500
                                        text-white focus:outline-none focus:ring-2 focus:ring-red-500" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">To</label>
                            <input type="date"
                                class="w-full px-4 py-3 rounded-lg bg-white/10 border border-gray-500
                                        text-white focus:outline-none focus:ring-2 focus:ring-red-500" />
                        </div>
                    </div>
                </div>


                {{-- Time --}}
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <img src="assets/fast-time.png" class="w-6 h-6" />
                        <span class="text-xl font-medium">Time</span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Pick-Up</label>
                            <input type="time"
                                class="w-full px-4 py-3 rounded-lg bg-white/10 border border-gray-500
                                        text-white focus:outline-none focus:ring-2 focus:ring-red-500" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Drop-Off</label>
                            <input type="time"
                                class="w-full px-4 py-3 rounded-lg bg-white/10 border border-gray-500
                                        text-white focus:outline-none focus:ring-2 focus:ring-red-500" />
                        </div>
                    </div>
                </div>


                <div class="flex items-end">
    <a href="/login" class="w-full">
        <button class="booknowbtn w-full py-4 rounded-md bg-gradient-to-r from-red-700 to-red-500
            drop-shadow-[0_6px_12px_rgba(255,255,255,0.45)]
            hover:drop-shadow-[0_10px_20px_rgba(255,255,255,0.65)]
            hover:scale-[1.03] active:scale-95
            transition-all duration-300 cursor-pointer">
            Book Now
        </button>
    </a>
</div>

                </div>

            </div>
        </div>
    </section>



    {{-- SERVICES --}}
    <section id="services" class="bg-[#1A1F24] text-white py-24 relative w-full min-h-[750px] lg:min-h-[910px]">
        <h2 class="text-5xl text-center font-black tracking-wide mb-14">Services</h2>

        <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10 px-6">

            {{-- CARD TEMPLATE --}}
            @php
                $services = [
                    ['img' => 'selfdrive.jpg', 'title' => 'Self Drive', 'desc' => 'Rent a car and drive yourself', 'sub' => 'Flexible & Convenient'],
                    ['img' => 'citydrive.png', 'title' => 'City Drive', 'desc' => 'Professional driver service', 'sub' => 'Relax & Enjoy the Ride'],
                    ['img' => 'tourservice.png', 'title' => 'Tour Service', 'desc' => 'Guided tours with expert drivers', 'sub' => 'Explore & Discover']
                ];
            @endphp

            @foreach ($services as $s)
            <div class="service-card group relative rounded-2xl overflow-hidden shadow-xl
                        cursor-pointer hover:scale-[1.03] transition-all duration-500">

                <img src="{{ asset('assets/' . $s['img']) }}" class="w-full h-[450px] lg:h-[500px] object-cover">

                <div class="absolute inset-0 bg-black/30 group-hover:bg-black/60 transition-all duration-500"></div>

                <div class="absolute inset-0 flex flex-col items-center justify-center
                            opacity-0 translate-y-5
                            group-hover:opacity-100 group-hover:translate-y-0
                            transition-all duration-500">

                    <h3 class="text-3xl font-bold mb-3 drop-shadow-lg">{{ $s['title'] }}</h3>
                    <p class="text-lg text-gray-200">{{ $s['desc'] }}</p>
                    <p class="text-gray-300 mt-2">{{ $s['sub'] }}</p>
                </div>
            </div>
            @endforeach

        </div>
    </section>



    {{-- FLEET --}}
    <section id="vehicles" class="bg-gradient-to-r from-red-600 to-red-500 py-24">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-5xl text-center text-white font-black mb-12">Our Fleet</h2>
            <p class="text-center text-white">Vehicle section content goes here...</p>
        </div>
    </section>



    {{-- REQUIREMENTS --}}
    <section id="requirements" class="bg-gray-100 py-24">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-5xl text-center font-black mb-12">Requirements</h2>
            <p class="text-center text-gray-600">Requirements section content goes here...</p>
        </div>
    </section>

    {{-- CONTACT US --}}
    <section id="contact" class="bg-[#1A1F24] text-white py-24">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-5xl text-center font-black mb-12">Contact Us</h2>
            <p class="text-center text-white">Contact us section content goes here...</p>
        </div>
    </section>


    @endsection
