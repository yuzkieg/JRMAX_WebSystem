@extends('layouts.app')

@section('content')
@vite('resources/css/app.css')

<div class="flex min-h-screen bg-[#1A1F24] text-white transition-colors duration-500" id="dashboard-wrapper">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-black/80 h-screen fixed top-0 left-0 shadow-xl border-r border-white/10 backdrop-blur-xl transition-all duration-300 hover:w-72">
        <div class="p-6 flex flex-col items-center">
            <img src="{{ asset('assets/logo.png') }}" class="w-20 h-20 mb-4 transition-all duration-300 hover:scale-105">
            <h2 class="text-xl font-bold tracking-wide text-red-500">FLEET</h2>
        </div>

        @php
            $menuItems = [
                ['name' => 'Vehicle Management', 'url' => '/employee/fleet/vehicles'],
                ['name' => 'Vehicle Maintenance', 'url' => '/employee/fleet/maintenance'],
            ];
        @endphp

        <nav class="mt-10 space-y-2 px-4">
            @foreach ($menuItems as $item)
                <a href="{{ $item['url'] }}"
                   class="block py-3 px-4 rounded-lg hover:bg-red-600/40 hover:translate-x-2 transition-all duration-300 text-white">
                    {{ $item['name'] }}
                </a>
            @endforeach
        </nav>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="ml-64 w-full min-h-screen p-8 transition-all duration-300">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-red-500 drop-shadow-lg">Vehicle Management</h1>

            <div class="flex items-center space-x-4">
                {{-- Theme Toggle --}}
                <button id="theme-toggle" class="flex items-center gap-2 bg-black/30 backdrop-blur-xl p-2 rounded-lg hover:bg-[#998282] transition-all duration-300 cursor-pointer">
                    <img id="theme-icon" src="{{ asset('assets/moon.png') }}" class="w-6 h-6 transition-transform duration-500">
                    <span class="font-medium text-white">Dark Mode</span>
                </button>

                {{-- Logout --}}
                <form method="POST" action="/logout">
                    @csrf
                    <button class="flex items-center gap-2 px-5 py-2 bg-[#742121] hover:bg-red-500 rounded-lg shadow-md transition-all duration-200 hover:scale-105 text-white">
                        <img src="{{ asset('assets/logout.png') }}" class="w-6 h-6">
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- SUCCESS MESSAGES --}}
        <div id="successMessageContainer"></div>

        {{-- SEARCH --}}
        <div class="flex justify-between items-center mb-6">
            <input type="text" placeholder="Search plate, model or brand..."
                   class="w-80 p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none 
                   focus:ring-2 focus:ring-red-500 transition-all duration-300"
                   id="searchInput">

            {{-- Note: Fleet users can only view, not add vehicles --}}
            <div class="text-gray-400 text-sm">
                <i>View-only mode - Contact admin for vehicle additions</i>
            </div>
        </div>

        {{-- VEHICLE TABLE --}}
        <div class="overflow-hidden rounded-2xl shadow-2xl backdrop-blur-xl card-text dark-card">
            <table class="w-full text-left">
                <thead class="bg-black/30 text-white uppercase text-sm tracking-wide">
                    <tr>
                        <th class="p-4">Plate No.</th>
                        <th class="p-4">Type</th>
                        <th class="p-4">Brand / Model</th>
                        <th class="p-4">Color</th>
                        <th class="p-4">Transmission</th>
                        <th class="p-4">Capacity</th>
                        <th class="p-4">Rate</th>
                        <th class="p-4">Driver</th>
                        <th class="p-4">Status</th>
                    </tr>
                </thead>
                <tbody id="vehiclesTable" class="text-white">
                    @foreach($vehicles as $vehicle)
                    <tr class="border-b border-white/10 hover:bg-white/10 transition-all">
                        <td class="p-4 font-semibold">{{ $vehicle->plate_num }}</td>
                        <td class="p-4">{{ $vehicle->body_type }}</td>
                        <td class="p-4">{{ $vehicle->brand }} {{ $vehicle->model }}</td>
                        <td class="p-4">{{ $vehicle->color }}</td>
                        <td class="p-4">{{ $vehicle->transmission }}</td>
                        <td class="p-4">{{ $vehicle->seat_cap }}</td>
                        <td class="p-4">₱{{ number_format($vehicle->price_rate, 2) }}</td>
                        <td class="p-4">
                            @if($vehicle->driverInfo)
                                {{ $vehicle->driverInfo->name }}
                            @else
                                <span class="text-gray-400">No Driver</span>
                            @endif
                        </td>
                        <td class="p-4">
                            @php
                                $status = $vehicle->status ?? 'available';
                                $statusColors = [
                                    'available' => 'bg-green-900/30 text-green-300',
                                    'in_use' => 'bg-blue-900/30 text-blue-300',
                                    'maintenance' => 'bg-yellow-900/30 text-yellow-300',
                                    'unavailable' => 'bg-red-900/30 text-red-300',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$status] ?? 'bg-gray-700 text-gray-300' }}">
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </main>
</div>

<script>
const csrfToken = '{{ csrf_token() }}';

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const term = e.target.value.toLowerCase();
    
    document.querySelectorAll('#vehiclesTable tr').forEach(row => {
        const plate = row.cells[0]?.textContent.toLowerCase() || '';
        const brand = row.cells[2]?.textContent.toLowerCase() || '';
        const driver = row.cells[7]?.textContent.toLowerCase() || '';
        
        const match = plate.includes(term) || brand.includes(term) || driver.includes(term);
        row.style.display = match ? '' : 'none';
    });
});

// Success message function (reuse from your admin page)
function showSuccessMessage(message, type = 'success') {
    const container = document.getElementById('successMessageContainer') || document.body;
    const existing = document.getElementById('dynamicSuccessMessage');
    
    if (existing) existing.remove();
    
    const bgColor = type === 'success' ? 'bg-green-600/20 border-green-500' : 
                    type === 'error' ? 'bg-red-600/20 border-red-500' : 
                    'bg-blue-600/20 border-blue-500';
    
    const textColor = type === 'success' ? 'text-green-300' : 
                     type === 'error' ? 'text-red-300' : 
                     'text-blue-300';
    
    const icon = type === 'success' ? `
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>` : 
        type === 'error' ? `
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>` : `
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>`;
    
    const div = document.createElement('div');
    div.id = 'dynamicSuccessMessage';
    div.className = `mb-6 p-4 ${bgColor} border rounded-xl ${textColor} backdrop-blur-sm transition-all duration-300 animate-fadeIn`;
    div.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                ${icon}
                <span>${message}</span>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="${textColor} hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>`;
    
    container.insertBefore(div, container.firstChild);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (div.parentNode) {
            div.classList.add('opacity-0');
            setTimeout(() => div.remove(), 300);
        }
    }, 5000);
}

// Add animation CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
`;
document.head.appendChild(style);

// Check for session messages
@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        showSuccessMessage('{{ session('success') }}', 'success');
    });
@endif

@if(session('error'))
    document.addEventListener('DOMContentLoaded', function() {
        showSuccessMessage('{{ session('error') }}', 'error');
    });
@endif
</script>
@endsection