@extends('layouts.app')

@section('content')

<div class="relative min-h-screen flex items-center justify-center bg-cover bg-center px-6 py-20"
     style="background-image: url('{{ asset('assets/homepage.jpg') }}');">

    <div class="absolute inset-0 backdrop-blur-xl bg-white/10"></div>

    <div class="w-full max-w-md bg-[#1A1F24] backdrop-blur-xl rounded-2xl shadow-2xl p-10 text-white
            transform transition-all duration-500 hover:scale-[1.03] hover:shadow-3xl hover:-translate-y-1">

        <a href="{{ url('/') }}"
           class="absolute top-5 left-5 flex items-center gap-2 px-4 py-2 text-white text-sm font-medium rounded-lg shadow-md 
          transition-all duration-200 border-b-2 border-transparent hover:border-red-600/80">
            <img src="{{ asset('assets/home.png') }}" class="w-6 h-6" alt="Home Icon" />
        </a>

        <div class="flex flex-col items-center mb-5">
            <img src="{{ asset('assets/logo.png') }}" class="w-30 h-30 mb-3" />
        </div>

        <h4 class="text-xl font-semibold mb-2 text-center">Be part of our travel journey.</h4>

        {{-- SIMPLE ERROR MESSAGES --}}
        @if($errors->any())
        <div class="mb-6 p-4 bg-red-600/20 border border-red-500 rounded-xl text-red-300 text-center">
            ❌ Invalid login credentials. Please try again.
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 p-4 bg-red-600/20 border border-red-500 rounded-xl text-red-300 text-center">
            ❌ {{ session('error') }}
        </div>
        @endif

        @if(session('success'))
        <div class="mb-6 p-4 bg-green-600/20 border border-green-500 rounded-xl text-green-300 text-center">
            ✅ {{ session('success') }}
        </div>
        @endif

        {{-- LOGIN FORM --}}
        <form method="POST" action="/login">
            @csrf

            <div class="mb-5">
                <label class="block text-sm text-gray-300 mb-1">Email</label>
                <input type="email" name="email"
                       class="w-full px-4 py-3 rounded-lg bg-white/10 border border-gray-600
                              text-white focus:outline-none focus:ring-2 focus:ring-red-600"
                       placeholder="Enter your email" 
                       value="{{ old('email') }}"
                       required>
            </div>

            <div class="mb-3 relative">
                <label class="block text-sm text-gray-300 mb-1">Password</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    class="w-full px-4 py-3 pr-12 rounded-lg bg-white/10 border border-gray-600
                        text-white focus:outline-none focus:ring-2 focus:ring-red-600"
                    placeholder="Enter your password"
                    required
                >

                <!-- Eye toggle -->
                <button
                    type="button"
                    onclick="togglePassword()"
                    class="absolute right-4 top-9 text-gray-400 hover:text-white transition"
                    aria-label="Toggle password visibility"
                >
                    👁️
                </button>
            </div>

            <button type="submit"
                class="w-full py-3 rounded-lg text-lg font-semibold
                       bg-gradient-to-r from-red-700 to-red-500
                       hover:from-red-600 hover:to-red-400
                       hover:shadow-xl active:scale-95 transition-all duration-300 cursor-pointer">
                LOG IN
            </button>
        </form>

    </div>

</div>

<script>
// Simple auto-hide messages after 5 seconds
setTimeout(() => {
    const messages = document.querySelectorAll('[class*="bg-red-600"], [class*="bg-green-600"]');
    messages.forEach(message => {
        message.style.opacity = '0';
        setTimeout(() => message.remove(), 300);
    });
}, 5000);
</script>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>


@endsection