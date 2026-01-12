{{-- SIDEBAR --}}
   <aside id="sidebar" class="w-72 bg-black/80 h-screen fixed top-0 left-0 shadow-xl border-r border-white/10 backdrop-blur-xl transition-all duration-300 flex flex-col">
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
               ['name' => 'Booking Management', 'url' => '/admin/booking'],
           ];
       @endphp

       <nav class="mt-10 space-y-2 px-6 flex-1 overflow-y-auto">
           @foreach ($menuItems as $item)
               @php
                   // Check if current URL matches menu item URL
                   $isActive = request()->is(ltrim($item['url'],'/'));
               @endphp
               <a href="{{ $item['url'] }}"
                   class="block py-3 px-4 rounded-lg transition-all duration-300 text-white 
                   {{ $isActive ? 'bg-red-600/60 translate-x-2' : 'hover:bg-red-600/40 hover:translate-x-2' }}">
                   {{ $item['name'] }}
               </a>
           @endforeach
       </nav>

       {{-- Logout Button at bottom --}}
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