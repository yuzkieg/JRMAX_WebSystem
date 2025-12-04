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
            <h1 class="text-3xl font-bold text-red-500 drop-shadow-lg">Vehicle Maintenance</h1>

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

        {{-- STATUS FILTERS --}}
        <div class="flex flex-wrap gap-3 mb-6">
            <button class="filter-btn px-4 py-2 rounded-lg transition-all duration-200 bg-red-700 text-white hover:bg-red-500" data-status="all">All</button>
            <button class="filter-btn px-4 py-2 rounded-lg transition-all duration-200 bg-gray-700 hover:bg-gray-600" data-status="scheduled">Scheduled</button>
            <button class="filter-btn px-4 py-2 rounded-lg transition-all duration-200 bg-blue-700 hover:bg-blue-600" data-status="in progress">In Progress</button>
            <button class="filter-btn px-4 py-2 rounded-lg transition-all duration-200 bg-green-700 hover:bg-green-600" data-status="completed">Completed</button>
            <button class="filter-btn px-4 py-2 rounded-lg transition-all duration-200 bg-yellow-700 hover:bg-yellow-600" data-status="cancelled">Cancelled</button>
        </div>

        {{-- SEARCH --}}
        <div class="flex justify-between items-center mb-6">
            <input type="text" placeholder="Search by plate, type, or description..."
                   class="w-80 p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none 
                   focus:ring-2 focus:ring-red-500 transition-all duration-300"
                   id="searchInput">

            {{-- Note: Fleet users can only update status, not add maintenance --}}
            <div class="text-gray-400 text-sm">
                <i>Contact admin to add new maintenance records</i>
            </div>
        </div>

        {{-- MAINTENANCE TABLE --}}
        <div class="overflow-hidden rounded-2xl shadow-2xl backdrop-blur-xl card-text dark-card">
            <table class="w-full text-left">
                <thead class="bg-black/30 text-white uppercase text-sm tracking-wide">
                    <tr>
                        <th class="p-4">Vehicle</th>
                        <th class="p-4">Type</th>
                        <th class="p-4">Description</th>
                        <th class="p-4">Scheduled Date</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Cost</th>
                        <th class="p-4">Reported By</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="maintenanceTable" class="text-white">
                    @foreach($maintenances as $maintenance)
                    <tr class="border-b border-white/10 hover:bg-white/10 transition-all" data-status="{{ $maintenance->status }}">
                        <td class="p-4">
                            <div class="font-semibold">{{ $maintenance->vehicle->plate_num ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-400">{{ $maintenance->vehicle->brand ?? '' }} {{ $maintenance->vehicle->model ?? '' }}</div>
                        </td>
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium 
                                @if(in_array($maintenance->maintenance_type, ['repair', 'engine service'])) bg-red-900/30 text-red-300
                                @elseif(in_array($maintenance->maintenance_type, ['oil change', 'tire replacement'])) bg-blue-900/30 text-blue-300
                                @elseif($maintenance->maintenance_type === 'check-up') bg-green-900/30 text-green-300
                                @else bg-gray-700 text-gray-300 @endif">
                                {{ ucwords(str_replace('-', ' ', $maintenance->maintenance_type)) }}
                            </span>
                        </td>
                        <td class="p-4 max-w-xs truncate" title="{{ $maintenance->description }}">
                            {{ Str::limit($maintenance->description, 50) }}
                            @if($maintenance->odometer_reading)
                            <div class="text-sm text-gray-400 mt-1">
                                Odometer: {{ number_format($maintenance->odometer_reading) }} km
                            </div>
                            @endif
                        </td>
                        <td class="p-4">
                            {{ $maintenance->scheduled_date ? \Carbon\Carbon::parse($maintenance->scheduled_date)->format('M d, Y') : 'N/A' }}
                            @if($maintenance->started_at)
                            <div class="text-xs text-gray-400">
                                Started: {{ \Carbon\Carbon::parse($maintenance->started_at)->format('M d') }}
                            </div>
                            @endif
                        </td>
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium 
                                @if($maintenance->status === 'scheduled') bg-gray-700 text-gray-300
                                @elseif($maintenance->status === 'in progress') bg-blue-900/30 text-blue-300
                                @elseif($maintenance->status === 'completed') bg-green-900/30 text-green-300
                                @else bg-red-900/30 text-red-300 @endif">
                                {{ ucfirst($maintenance->status) }}
                            </span>
                        </td>
                        <td class="p-4 font-semibold">
                            ₱{{ number_format($maintenance->cost, 2) }}
                        </td>
                        <td class="p-4">
                            {{ $maintenance->reporter->name ?? 'N/A' }}
                        </td>
                        <td class="p-4 text-center">
                            @if($maintenance->status !== 'completed' && $maintenance->status !== 'cancelled')
                            <button class="status-btn cursor-pointer px-3 py-1 bg-blue-600 hover:bg-blue-500 rounded-lg text-white text-sm transition-all duration-200"
                                    data-id="{{ $maintenance->maintenance_ID }}"
                                    data-action="next">
                                @if($maintenance->status === 'scheduled') Start
                                @elseif($maintenance->status === 'in progress') Complete
                                @endif
                            </button>
                            @else
                            <span class="text-gray-400 text-sm">No actions</span>
                            @endif
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

// Filter by status
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const status = this.dataset.status;
        
        // Update active button
        document.querySelectorAll('.filter-btn').forEach(b => {
            b.classList.remove('bg-red-700', 'text-white');
            b.classList.add('bg-gray-700', 'hover:bg-gray-600');
        });
        this.classList.remove('bg-gray-700', 'hover:bg-gray-600');
        this.classList.add('bg-red-700', 'text-white');
        
        // Filter rows
        document.querySelectorAll('#maintenanceTable tr').forEach(row => {
            if (status === 'all') {
                row.style.display = '';
            } else {
                row.style.display = row.dataset.status === status ? '' : 'none';
            }
        });
    });
});

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const term = e.target.value.toLowerCase();
    
    document.querySelectorAll('#maintenanceTable tr').forEach(row => {
        const plate = row.cells[0]?.textContent.toLowerCase() || '';
        const type = row.cells[1]?.textContent.toLowerCase() || '';
        const desc = row.cells[2]?.textContent.toLowerCase() || '';
        
        const match = plate.includes(term) || type.includes(term) || desc.includes(term);
        row.style.display = match ? '' : 'none';
    });
});

// Status update functionality
document.addEventListener('click', async function(e) {
    if (e.target.closest('.status-btn')) {
        const btn = e.target.closest('.status-btn');
        const id = btn.dataset.id;
        const action = btn.dataset.action;
        
        let newStatus;
        if (action === 'next') {
            const btnText = btn.textContent.trim();
            if (btnText.includes('Start')) newStatus = 'in progress';
            else if (btnText.includes('Complete')) newStatus = 'completed';
        }
        
        if (!newStatus) return;
        
        try {
            btn.disabled = true;
            btn.innerHTML = 'Updating...';
            
            const res = await fetch(`/employee/fleet/maintenance/${id}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            });
            
            const result = await res.json();
            
            if (res.ok) {
                // Show success message
                showSuccessMessage(result.message || 'Status updated successfully', 'success');
                
                // Reload page after showing message
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showSuccessMessage(result.message || 'Error updating status', 'error');
                btn.disabled = false;
                btn.innerHTML = action === 'next' ? (newStatus === 'in progress' ? 'Start' : 'Complete') : 'Update';
            }
        } catch (error) {
            showSuccessMessage('Network error: ' + error.message, 'error');
            btn.disabled = false;
            btn.innerHTML = action === 'next' ? (newStatus === 'in progress' ? 'Start' : 'Complete') : 'Update';
        }
    }
});

// Success message function
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