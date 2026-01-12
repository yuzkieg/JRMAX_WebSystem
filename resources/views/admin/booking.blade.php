@extends('layouts.app')

@section('content')
@vite('resources/css/app.css')

<style>
:root {
    --action-edit: #2563EB;
    --action-edit-hover: #1E40AF;
    --action-delete: #B91C1C;
    --action-delete-hover: #991B1B;
    --status-pending: #F59E0B;
    --status-confirmed: #3B82F6;
    --status-ongoing: #8B5CF6;
    --status-completed: #10B981;
    --status-cancelled: #EF4444;
    --nav-tab-text: #ffffff;
}

.actions-menu.dropup {
    bottom: 100%;
    top: auto;
    margin-bottom: 0.5rem;
    margin-top: 0;
}

.actions { position: relative; display: inline-block; }
.actions-toggle { background: transparent; border: none; color: inherit; padding: .5rem; border-radius: .5rem; cursor: pointer; }
.actions-toggle:hover { background: rgba(255, 255, 255, 0.1); }
.actions-menu { position: absolute; right: 0; top: 100%; margin-top: 0.5rem; min-width: 10rem; background: #262B32; border: 1px solid rgba(255,255,255,0.1); border-radius: .75rem; box-shadow: 0 6px 18px rgba(0,0,0,0.6); z-index: 40; display: none; overflow: hidden; }
.actions-menu.show { display: block; }
.actions-menu button { display: flex; align-items: center; gap: .5rem; width: 100%; text-align: left; padding: .5rem .75rem; background: transparent; border: none; color: #e5e7eb; cursor: pointer; }
.actions-menu button:hover { background-color: rgba(255,255,255,0.05); }
.actions-menu.dropup { bottom: 100%; top: auto; margin-top: 0; margin-bottom: 0.5rem; }
.actions-menu button:hover { background: rgba(255,255,255,0.05); }

#sidebar nav a {
    color: var(--nav-tab-text) !important;
}

.action-edit {
    background-color: var(--action-edit) !important;
    color: #fff !important;
    padding: .5rem .9rem;
    border-radius: .5rem;
}
.action-edit:hover { background-color: var(--action-edit-hover) !important; }

.action-delete {
    background-color: var(--action-delete) !important;
    color: #fff !important;
    padding: .5rem .9rem;
    border-radius: .5rem;
}
.action-delete:hover { background-color: var(--action-delete-hover) !important; }


    .status-pill {
        display: inline-block;
        border-radius: 0.5rem;
        font-weight: 700;
        font-size: medium;
    }
    .status-pill.pending { background: transparent; color: #FFFF00 ; }
    .status-pill.confirmed { background: transparent; color: #0FC2A7; }
    .status-pill.ongoing { background: transparent; color: #ADD8E6  ; }
    .status-pill.completed { background: transparent; color: #93FF54   ; }
    .status-pill.cancelled { background: transparent; color: #FF0000 ; }

.stat-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.02));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 0.75rem;
    padding: 1.5rem;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.stat-card:hover {
    border-color: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

.stat-card .stat-value {
    font-size: 1.875rem;
    font-weight: bold;
    color: #fff;
}

.stat-card .stat-label {
    font-size: 0.875rem;
    color: #9CA3AF;
    margin-top: 0.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #ffffff;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border-radius: 0.75rem;
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #ffffff;
    outline: none;

/* Modal-like view sections (create/edit/show) */
.view-section { display: none; }
.view-section.active {
    display: flex;
    position: fixed;
    inset: 0;
    z-index: 60;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,0.6);
    padding: 2rem;
}
/* keep index view as normal page content */
#indexView.view-section.active {
    display: block;
    position: static;
    inset: auto;
    z-index: auto;
    align-items: stretch;
    justify-content: stretch;
    background: transparent;
    padding: 0;
}
.view-section .modal-card {
    width: 100%;
    max-width: 1100px;
    max-height: 90vh;
    overflow: auto;
    border-radius: 1rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, rgba(38,43,50,0.95), rgba(22,24,27,0.95));
    border: 1px solid rgba(255,255,255,0.06);
    box-shadow: 0 10px 30px rgba(2,6,23,0.6);
}

/* Pagination styling: more standard buttons */
.pagination { display: flex; gap: .5rem; list-style: none; padding: 0; }
.pagination .page-item .page-link {
    display: inline-block;
    padding: .5rem .75rem;
    border-radius: .5rem;
    background: rgba(255,255,255,0.03);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.05);
}
.pagination .page-item.active .page-link {
    background: #ef4444; color: #fff; border-color: rgba(0,0,0,0.2);
}
.pagination .page-link:hover { transform: translateY(-1px); }
    transition: all 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: rgba(255, 255, 255, 0.3);
    box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
}

.form-group input::placeholder,
.form-group textarea::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

.vehicle-checkbox {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 0.5rem;
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.vehicle-checkbox:hover { background: rgba(255, 255, 255, 0.1); }

.vehicle-checkbox input[type="checkbox"] {
    width: auto;
    margin-right: 0.5rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 0.75rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.btn-primary {
    background: #EF4444;
    color: #fff;
}

.btn-primary:hover {
    background: #DC2626;
    transform: scale(1.05);
}

.btn-secondary {
    background: #3B82F6;
    color: #fff;
}

.btn-secondary:hover {
    background: #2563EB;
}

.btn-group {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}

.error-message {
    background: rgba(239, 68, 68, 0.2);
    border: 1px solid #EF4444;
    color: #FCA5A5;
    padding: 0.75rem;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
}

.detail-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 0.75rem;
    padding: 1.5rem;
}

.detail-label {
    font-size: 0.875rem;
    color: #9CA3AF;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
}

.detail-value {
    font-size: 1rem;
    color: #fff;
    font-weight: 600;
}

.vehicle-item {
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.3);
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 0.75rem;
}

.view-section { display: none; }
.view-section.active { display: block; }

td .action-edit, td .action-delete { 
    display: inline-flex; 
    align-items: center; 
    justify-content: center; 
    gap: .35rem; 
}
/* Enhanced Pagination Styles */
.pagination-wrapper {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 0.75rem;
    padding: 0.75rem 1rem;
    backdrop-filter: blur(10px);
}

.pagination .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 0.75rem;
    min-width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    background: rgba(255, 255, 255, 0.05);
    color: #fff;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.pagination .page-link:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.15);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #fff;
    border-color: rgba(0, 0, 0, 0.2);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    font-weight: 600;
}

.pagination .page-item.disabled .page-link {
    background: rgba(255, 255, 255, 0.02);
    color: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.05);
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.pagination .prev-next-btn {
    padding: 0.5rem 1rem;
    gap: 0.5rem;
    background: rgba(239, 68, 68, 0.1);
    border-color: rgba(239, 68, 68, 0.2);
}

.pagination .prev-next-btn:hover {
    background: rgba(239, 68, 68, 0.2);
    border-color: rgba(239, 68, 68, 0.3);
}

/* Mobile responsive adjustments */
@media (max-width: 640px) {
    .pagination-wrapper {
        padding: 0.5rem;
    }
    
    .pagination .page-link {
        min-width: 2.25rem;
        height: 2.25rem;
        padding: 0.5rem;
        font-size: 0.8125rem;
    }
    
    .pagination .prev-next-btn {
        padding: 0.5rem;
    }
    
    .pagination .prev-next-btn span {
        display: none;
    }
}

/* Success and Error Message Animations */
.success-message, .error-message {
    animation: slidedown 0.5s ease-out;
}

@keyframes slidedown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Ensure messages respect theme */
.success-message {
    background: rgba(16, 185, 129, 0.2) !important;
    border-color: #10B981 !important;
    color: #6EE7B7 !important;
}

.error-message {
    background: rgba(239, 68, 68, 0.2) !important;
    border-color: #EF4444 !important;
    color: #FCA5A5 !important;
}
</style>

<div class="flex min-h-screen bg-[#1A1F24] text-white transition-colors duration-500" id="dashboard-wrapper">

    @include('admin.layout.sidebar')

    {{-- MAIN CONTENT --}}
    <main class="ml-64 w-full min-h-screen p-8 transition-all duration-300">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <h1 id="pageTitle" class="text-3xl font-bold text-red-500 drop-shadow-lg">Booking Management</h1>

            <div class="flex items-center space-x-4">
                {{-- Theme Toggle --}}
                <button id="theme-toggle" class="flex items-center gap-2 bg-black/30 backdrop-blur-xl p-2 rounded-lg hover:bg-[#998282] transition-all duration-300 cursor-pointer">
                    <img id="theme-icon" src="{{ asset('assets/moon.png') }}" class="w-6 h-6 transition-transform duration-500">
                    <span class="font-medium text-white">Dark Mode</span>
                </button>
            </div>
        </div>

        {{-- SUCCESS MESSAGE --}}
        @if ($message = Session::get('success'))
            <div class="success-message border-l-4 rounded-lg p-4 mb-6 flex justify-between items-center bg-green-600/20 text-green-300 border-green-500 animate-slidedown" role="alert">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ $message }}</span>
                </div>
                <button class="close-alert text-green-300 hover:text-green-100" onclick="this.parentElement.style.display='none';">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- ERROR MESSAGE --}}
        @if ($message = Session::get('error'))
            <div class="error-message border-l-4 rounded-lg p-4 mb-6 flex justify-between items-center bg-red-600/20 text-red-300 border-red-500 animate-slidedown" role="alert">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ $message }}</span>
                </div>
                <button class="close-alert text-red-300 hover:text-red-100" onclick="this.parentElement.style.display='none';">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- VALIDATION ERRORS --}}
        @if ($errors->any())
            <div class="error-message border-l-4 rounded-lg p-4 mb-6 bg-red-600/20 text-red-300 border-red-500" role="alert">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-semibold">Validation Errors:</span>
                </div>
                <ul class="list-disc list-inside ml-8 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-red-200">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ========== INDEX VIEW ========== --}}
        <div id="indexView" class="view-section active">
            {{-- STATISTICS CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="stat-card">
                    <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
                    <div class="stat-label">Total Bookings</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color: #F59E0B;">{{ $stats['pending'] ?? 0 }}</div>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color: #3B82F6;">{{ $stats['active'] ?? 0 }}</div>
                    <div class="stat-label">Active</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color: #10B981;">{{ $stats['completedBookings'] ?? 0 }}</div>
                    <div class="stat-label">Completed</div>
                </div>
            </div>

            {{-- SEARCH AND ADD BOOKING --}}
            <div class="flex justify-between items-center mb-6">
                <input type="text" placeholder="Search booking ID, client name..."
                       class="w-80 p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none 
                       focus:ring-2 focus:ring-red-500 transition-all duration-300"
                       id="searchInput">

                <button id="newBookingBtn" onclick="switchView('createView')" class="cursor-pointer px-5 py-2 bg-red-700 hover:bg-red-500 rounded-xl text-white shadow-lg transition-all duration-300 hover:scale-105">
                    + New Booking
                </button>
            </div>

            {{-- BOOKINGS TABLE --}}
            <div class=" rounded-2xl shadow-2xl backdrop-blur-xl card-text dark-card">
                <table class="w-full text-left">
                    <thead class="bg-black/30 text-white uppercase text-sm tracking-wide">
                        <tr>
                            <th class="p-4">Booking ID</th>
                            <th class="p-4">Client</th>
                            <th class="p-4">Vehicles</th>
                            <th class="p-4">Check-in</th>
                            <th class="p-4">Check-out</th>
                            <th class="p-4">Total</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="bookingsTable" class="text-white">
                        @forelse($bookings as $booking)
                            <tr class="border-b border-white/10 hover:bg-white/10 transition-all booking-row">
                                <td class="p-4 font-semibold">#{{ str_pad($booking->boarding_id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td class="p-4 booking-client">{{ $booking->client->first_name ?? 'N/A' }} {{ $booking->client->last_name ?? '' }}</td>
                                <td class="p-4 !border-0">
                                    @foreach($booking->vehicles as $bv)
                                        <span class="inline-block px-2 py-1 text-sm text-white mr-1">
                                            {{ $bv->vehicle->brand ?? 'N/A' }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="p-4 text-sm">{{ $booking->start_datetime ? $booking->start_datetime->format('M d H:i') : 'N/A' }}</td>
                                <td class="p-4 text-sm">{{ $booking->end_datetime ? $booking->end_datetime->format('M d H:i') : 'N/A' }}</td>
                                <td class="p-4 font-bold text-green-400">₱{{ number_format($booking->total_price, 2) }}</td>
                                <td class="p-4 !border-0">
                                    @php
                                        $statusClass = 'pending';
                                        if ($booking->status_id == 2) $statusClass = 'confirmed';
                                        elseif ($booking->status_id == 3) $statusClass = 'ongoing';
                                        elseif ($booking->status_id == 4) $statusClass = 'completed';
                                        elseif ($booking->status_id == 5) $statusClass = 'cancelled';
                                    @endphp
                                    <span class="status-pill {{ $statusClass }}">{{ $booking->status->status_name ?? 'Unknown' }}</span>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="actions">
                                        <button type="button" class="actions-toggle" aria-expanded="false">
                                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="5" cy="12" r="1.5" />
                                                <circle cx="12" cy="12" r="1.5" />
                                                <circle cx="19" cy="12" r="1.5" />
                                            </svg>
                                        </button>

                                        <div class="actions-menu" role="menu">
                                            <button class="view-booking-btn" data-id="{{ $booking->boarding_id }}" role="menuitem">
                                                <img src="{{ asset('assets/file.png') }}" alt="View" class="w-5 h-5">
                                                <span>View Details</span>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="p-8 text-center text-gray-400">No bookings found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($bookings->hasPages())
    <div class="mt-8 flex justify-center">
        <nav class="pagination-wrapper">
            <ul class="pagination flex items-center gap-2">
                {{-- Previous Page Link --}}
                @if ($bookings->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link prev-next-btn opacity-50 cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            <span class="hidden sm:inline">Previous</span>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a href="{{ $bookings->previousPageUrl() }}" class="page-link prev-next-btn hover:bg-red-600/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            <span class="hidden sm:inline">Previous</span>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($bookings->links()->elements[0] as $page => $url)
                    @if (is_string($page))
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @else
                        <li class="page-item {{ $bookings->currentPage() == $page ? 'active' : '' }}">
                            <a href="{{ $url }}" class="page-link {{ $bookings->currentPage() == $page ? 'bg-red-600 text-white' : 'hover:bg-white/10' }}">
                                {{ $page }}
                            </a>
                        </li>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($bookings->hasMorePages())
                    <li class="page-item">
                        <a href="{{ $bookings->nextPageUrl() }}" class="page-link prev-next-btn hover:bg-red-600/20">
                            <span class="hidden sm:inline">Next</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link prev-next-btn opacity-50 cursor-not-allowed">
                            <span class="hidden sm:inline">Next</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@endif
        </div>

        {{-- ========== CREATE/EDIT VIEW ========== --}}
        <div id="createView" class="view-section">
            @if ($errors->any())
                <div class="error-message mb-6">
                    <strong>Validation errors:</strong>
                    <ul class="mt-2 ml-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-[#262B32] rounded-2xl shadow-2xl p-8 backdrop-blur-xl border border-white/10 modal-card">
                <form id="bookingForm" method="POST">
                    @csrf
                    <input type="hidden" id="formMethod" name="_method" value="POST">

                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-red-500 mb-4">Client Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="client_id">Select Client *</label>
                                <select name="client_id" id="client_id" required class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                                    <option value="">-- Choose Client --</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->Editor_id }}">{{ $client->first_name }} {{ $client->last_name }} ({{ $client->email }})</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-red-500 mb-4">Booking Details</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="start_datetime">Check-in *</label>
                                <input type="datetime-local" name="start_datetime" id="start_datetime" required class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                            </div>

                            <div class="form-group">
                                <label for="end_datetime">Check-out *</label>
                                <input type="datetime-local" name="end_datetime" id="end_datetime" required class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                            </div>

                            <div class="form-group">
                                <label for="pickup_location">Pick-up Location *</label>
                                <input type="text" name="pickup_location" id="pickup_location" required placeholder="e.g., Airport Terminal 1" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                            </div>

                            <div class="form-group">
                                <label for="dropoff_location">Drop-off Location *</label>
                                <input type="text" name="dropoff_location" id="dropoff_location" required placeholder="e.g., Hotel Downtown" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-red-500 mb-4">Vehicles & Driver</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label>Select Vehicle(s) *</label>
                                <div style="max-height: 300px; overflow-y: auto; background: rgba(0,0,0,0.2); padding: 1rem; border-radius: 0.75rem;">
                                    @foreach($vehicles as $vehicle)
                                        <label class="vehicle-checkbox">
                                            <input type="checkbox" name="vehicle_ids[]" value="{{ $vehicle->vehicle_id }}">
                                            <span>{{ $vehicle->brand }} {{ $vehicle->model }} - {{ $vehicle->plate_num }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="driver_id">Assign Driver (Optional)</label>
                                <select name="driver_id" id="driver_id" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                                    <option value="">-- No Driver --</option>
                                    @foreach($drivers as $driver)
                                        <option value="{{ $driver->id }}">{{ $driver->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-red-500 mb-4">Pricing & Status</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="form-group">
                                <label for="total_price">Total Price (₱) *</label>
                                <input type="number" name="total_price" id="total_price" required step="0.01" min="0" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                            </div>

                            <div class="form-group">
                                <label for="status_id">Booking Status *</label>
                                <select name="status_id" id="status_id" required class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                                    <option value="">-- Select Status --</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->status_id }}">{{ $status->status_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="payment_method">Payment Method</label>
                                <select name="payment_method" id="payment_method" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                                    <option value="cash">Cash</option>
                                    <option value="credit_card">Credit Card</option>
                                    <option value="online_transfer">Online Transfer</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <div class="form-group">
                            <label for="special_requests">Special Requests / Notes</label>
                            <textarea name="special_requests" id="special_requests" rows="4" placeholder="Any special requests or notes about this booking..." class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="switchView('indexView')" class="btn btn-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Create Booking</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========== SHOW VIEW ========== --}}
        <div id="showView" class="view-section">
            <div id="showContent"></div>
        </div>

    </main>
</div>

<script>
function switchView(view) {
    document.querySelectorAll('.view-section').forEach(v => v.classList.remove('active'));
    document.getElementById(view).classList.add('active');
}

function showBooking(id) {
    fetch(`/admin/booking/${id}`, { 
        headers: { 
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        } 
    })
    .then(r => {
        if (!r.ok) {
            if (r.status === 401 || r.status === 302) { 
                window.location.href = '/login'; 
            }
            return r.text().then(t => { 
                throw new Error('Failed to load booking: ' + t); 
            });
        }
        const ct = r.headers.get('content-type') || '';
        if (ct.includes('application/json')) return r.json();
        return r.text().then(t => { 
            throw new Error('Unexpected response: ' + t); 
        });
    })
    .then(response => {
        // Handle different possible response formats
        const booking = response.booking || response.data || response;
        
        const statusMap = {
            1: ['Pending', 'status-pill pending'],
            2: ['Confirmed', 'status-pill confirmed'],
            3: ['Ongoing', 'status-pill ongoing'],
            4: ['Completed', 'status-pill completed'],
            5: ['Cancelled', 'status-pill cancelled']
        };
        
        const status = booking.status_id || booking.status?.status_id;
        const [statusText, statusClass] = statusMap[status] || ['Unknown', 'status-pill pending'];
        
        // Format dates
        const formatDate = (dateStr) => {
            if (!dateStr) return 'N/A';
            const date = new Date(dateStr);
            return date.toLocaleString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        };
        
        // Get client information
        const client = booking.client || booking.Client || {};
        const clientName = `${client.first_name || ''} ${client.last_name || ''}`.trim() || 'N/A';
        const clientEmail = client.email || 'N/A';
        const clientPhone = client.phone_number || 'N/A';
        
        // Get driver information
        const driver = booking.driver || booking.Driver || {};
        const driverName = driver.full_name || driver.name || 'Unassigned';
        const driverLicense = driver.license_number || 'N/A';
        const driverEmail = driver.email || 'N/A';
        const driverPhone = driver.phone_number || 'N/A';
        
        // Get vehicles
        const vehicles = booking.vehicles || booking.Vehicles || [];
        
        // Calculate duration
        const startDate = booking.start_datetime ? new Date(booking.start_datetime) : null;
        const endDate = booking.end_datetime ? new Date(booking.end_datetime) : null;
        let duration = { days: 0, hours: 0, totalHours: 0 };
        
        if (startDate && endDate) {
            const diffMs = endDate - startDate;
            const diffHours = diffMs / (1000 * 60 * 60);
            duration.totalHours = Math.round(diffHours);
            duration.days = Math.floor(diffHours / 24);
            duration.hours = Math.round(diffHours % 24);
        }
        
        const html = `
            <div class="modal-card">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-3xl font-bold text-white">#${String(booking.boarding_id).padStart(6,'0')}</h2>
                        <div class="text-sm text-gray-400 mt-1">
                            Created: ${formatDate(booking.created_at)}
                        </div>
                    </div>
                    <div>
                        <button onclick="switchView('indexView')" class="px-4 py-2 bg-gray-700 rounded text-white hover:bg-gray-600">Close</button>
                    </div>
                </div>

                <!-- CLIENT & DRIVER INFORMATION -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b border-gray-700">
                    <div class="detail-card">
                        <h3 class="detail-label">Client Information</h3>
                        <div class="space-y-2">
                            <div class="detail-value">${clientName}</div>
                            <div class="text-sm text-gray-400">${clientEmail}</div>
                            <div class="text-sm text-gray-400">${clientPhone}</div>
                            ${client.identification_type ? `
                                <div class="text-xs text-gray-500 mt-2">
                                    ID: ${client.identification_type} - ${client.identification_number || 'N/A'}
                                </div>
                            ` : ''}
                        </div>
                    </div>
                    <div class="detail-card">
                        <h3 class="detail-label">Driver Assignment</h3>
                        <div class="space-y-2">
                            <div class="detail-value">${driverName}</div>
                            ${driverName !== 'Unassigned' ? `
                                <div class="text-sm text-gray-400">License: ${driverLicense}</div>
                                <div class="text-sm text-gray-400">${driverEmail}</div>
                                <div class="text-sm text-gray-400">Phone: ${driverPhone}</div>
                            ` : '<div class="text-sm text-gray-500">No driver assigned to this booking</div>'}
                        </div>
                    </div>
                </div>

                <!-- LOCATION & TIMING INFORMATION -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b border-gray-700">
                    <div>
                        <div class="detail-card mb-4">
                            <h3 class="detail-label">Pick-up Location</h3>
                            <div class="detail-value">${booking.pickup_location || 'N/A'}</div>
                        </div>
                        <div class="detail-card">
                            <h3 class="detail-label">Check-in Time</h3>
                            <div class="detail-value">${formatDate(booking.start_datetime)}</div>
                        </div>
                    </div>
                    <div>
                        <div class="detail-card mb-4">
                            <h3 class="detail-label">Drop-off Location</h3>
                            <div class="detail-value">${booking.dropoff_location || 'N/A'}</div>
                        </div>
                        <div class="detail-card">
                            <h3 class="detail-label">Check-out Time</h3>
                            <div class="detail-value">${formatDate(booking.end_datetime)}</div>
                        </div>
                    </div>
                </div>

                <!-- DURATION INFORMATION -->
                <div class="detail-card mb-6 pb-6 border-b border-gray-700">
                    <h3 class="detail-label">Booking Duration</h3>
                    <div class="grid grid-cols-3 gap-4 mt-3">
                        <div class="bg-blue-900/30 rounded p-3 border border-blue-700/50">
                            <div class="text-2xl font-bold text-blue-400">${duration.days}</div>
                            <div class="text-xs text-gray-400">Days</div>
                        </div>
                        <div class="bg-blue-900/30 rounded p-3 border border-blue-700/50">
                            <div class="text-2xl font-bold text-blue-400">${duration.hours}</div>
                            <div class="text-xs text-gray-400">Hours</div>
                        </div>
                        <div class="bg-blue-900/30 rounded p-3 border border-blue-700/50">
                            <div class="text-2xl font-bold text-blue-400">${duration.totalHours}</div>
                            <div class="text-xs text-gray-400">Total Hours</div>
                        </div>
                    </div>
                </div>

                <!-- VEHICLES ASSIGNED -->
                <div class="mb-6 pb-6 border-b border-gray-700">
                    <h3 class="detail-label">Assigned Vehicles (${vehicles.length})</h3>
                    <div class="mt-3 space-y-3">
                        ${vehicles.length > 0 ? vehicles.map(vehicle => {
                            const v = vehicle.vehicle || vehicle;
                            const plateNum = v.plate_num || v.plate_number || 'N/A';
                            const brand = v.brand || 'N/A';
                            const model = v.model || 'N/A';
                            const bodyType = v.body_type || v.type || 'N/A';
                            const priceRate = v.price_rate || v.daily_rate || 0;
                            
                            return `
                                <div class="vehicle-item">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        <div>
                                            <div class="text-xs text-gray-500">Plate Number</div>
                                            <div class="font-semibold text-white">${plateNum}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">Vehicle</div>
                                            <div class="font-semibold text-white">${brand} ${model}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">Type</div>
                                            <div class="font-semibold text-white">${bodyType}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">Rate (Daily)</div>
                                            <div class="font-semibold text-green-400">₱${Number(priceRate).toFixed(2)}</div>
                                        </div>
                                    </div>
                                    ${vehicle.remarks ? `
                                        <div class="mt-2 pt-2 border-t border-blue-600/30 text-xs text-gray-400">
                                            <div>Remarks: ${vehicle.remarks}</div>
                                        </div>
                                    ` : ''}
                                </div>
                            `;
                        }).join('') : '<div class="text-gray-400 py-3">No vehicles assigned</div>'}
                    </div>
                </div>

                <!-- PRICING INFORMATION -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 pb-6 border-b border-gray-700">
                    <div class="bg-green-900/30 rounded p-4 border border-green-700/50">
                        <h3 class="detail-label">Total Price</h3>
                        <div class="text-3xl font-bold text-green-400 mt-2">
                            ₱${Number(booking.total_price || 0).toFixed(2)}
                        </div>
                    </div>
                    <div class="bg-purple-900/30 rounded p-4 border border-purple-700/50">
                        <h3 class="detail-label">Payment Method</h3>
                        <div class="text-xl font-bold text-purple-400 mt-2">
                            ${booking.payment_method ? booking.payment_method.replace('_', ' ').toUpperCase() : 'N/A'}
                        </div>
                    </div>
                    <div class="bg-indigo-900/30 rounded p-4 border border-indigo-700/50">
                        <h3 class="detail-label">Status</h3>
                        <div class="mt-2">
                            <span class="${statusClass} px-3 py-1 text-sm">${statusText}</span>
                        </div>
                    </div>
                </div>

                <!-- SPECIAL REQUESTS & NOTES -->
                <div class="mb-6 pb-6 border-b border-gray-700">
                    <h3 class="detail-label">Special Requests</h3>
                    <div class="detail-card mt-2">
                        <div class="text-gray-300">
                            ${booking.special_requests || '<span class="text-gray-500">No special requests</span>'}
                        </div>
                    </div>
                </div>

                <!-- BOOKING NOTES -->
                ${booking.notes ? `
                    <div class="mb-6 pb-6 border-b border-gray-700">
                        <h3 class="detail-label">Admin Notes</h3>
                        <div class="detail-card mt-2">
                            <div class="text-gray-300">${booking.notes}</div>
                        </div>
                    </div>
                ` : ''}

                <!-- AUDIT TRAIL -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div>
                        <h3 class="detail-label">Created</h3>
                        <div class="text-gray-300">${booking.created_by || 'System'}</div>
                        <div class="text-gray-500">${formatDate(booking.created_at)}</div>
                    </div>
                    <div>
                        <h3 class="detail-label">Last Updated</h3>
                        <div class="text-gray-300">${booking.updated_by || 'System'}</div>
                        <div class="text-gray-500">${formatDate(booking.updated_at)}</div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('showContent').innerHTML = html;
        switchView('showView');
    })
    .catch(err => {
        console.error('Error loading booking:', err);
        alert('Failed to load booking details: ' + (err.message || 'Please check console for details'));
    });
}

// Search
document.getElementById('searchInput').addEventListener('input', (e) => {
    const term = e.target.value.toLowerCase();
    document.querySelectorAll('.booking-row').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
    });
});

// Form submission
document.getElementById('bookingForm').addEventListener('submit', (e) => {
    e.preventDefault();
    const formEl = e.currentTarget;
    const formData = new FormData(formEl);
    const method = document.getElementById('formMethod').value;
    const isUpdate = method === 'PUT';
    const url = isUpdate ? formEl.action : '/admin/booking';
    // Use POST for form-data updates and rely on _method spoofing so Laravel parses FormData correctly
    const fetchMethod = isUpdate ? 'POST' : method;

    fetch(url, {
        method: fetchMethod,
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: formData
    })
    .then(async r => {
        if (!r.ok) {
            const ct = r.headers.get('content-type') || '';
            const payload = ct.includes('application/json') ? await r.json().catch(() => ({})) : { message: await r.text() };
            return { ok: false, data: payload, status: r.status };
        }
        const json = await r.json().catch(() => ({}));
        return { ok: true, data: json };
    })
    .then(res => {
        if (res.ok) {
            alert(isUpdate ? 'Booking updated successfully!' : 'Booking created successfully!');
            location.reload();
        } else {
            alert('Error: ' + (res.data.message || 'Unknown error occurred'));
        }
    })
    .catch(err => {
        console.error('Form error:', err);
        alert('Error submitting form: ' + err.message);
    });
});

// Reset form when clicking 'New Booking'
const createBtn = document.getElementById('newBookingBtn');
if (createBtn) {
    createBtn.addEventListener('click', () => {
        setTimeout(() => {
            document.getElementById('bookingForm').reset();
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('submitBtn').textContent = 'Create Booking';
            document.getElementById('bookingForm').action = '/admin/booking';
            document.querySelectorAll('input[name="vehicle_ids[]"]').forEach(cb => cb.checked = false);
        }, 50);
    });
}

// Actions dropdown toggle
document.addEventListener('click', function(e) {
    const toggle = e.target.closest('.actions-toggle');

    if (toggle) {
        const actions = toggle.closest('.actions');
        const menu = actions.querySelector('.actions-menu');

        // Close other open menus
        document.querySelectorAll('.actions-menu.show').forEach(m => {
            if (m !== menu) m.classList.remove('show');
        });

        // Toggle this menu
        menu.classList.toggle('show');
        toggle.setAttribute('aria-expanded', menu.classList.contains('show'));

        // Check for dropup
        if (menu.classList.contains('show')) {
            const rect = menu.getBoundingClientRect();
            if (rect.bottom > window.innerHeight) {
                menu.classList.add('dropup');
            } else {
                menu.classList.remove('dropup');
            }
        }

        e.stopPropagation();
        return;
    }

    // If clicking a menu item, close the menu shortly after
    if (e.target.closest('.actions-menu button')) {
        const menu = e.target.closest('.actions-menu');
        setTimeout(() => menu.classList.remove('show'), 100);
        return;
    }

    // Click outside: close all menus
    document.querySelectorAll('.actions-menu.show').forEach(m => m.classList.remove('show'));
});

// View booking handler
document.addEventListener('click', function(e) {
    if (e.target.closest('.view-booking-btn')) {
        const id = e.target.closest('.view-booking-btn').dataset.id;
        showBooking(id);
    }
});
</script>

<script>
    function confirmLogout(event) {
        event.preventDefault();
        if (confirm('Are you sure you want to logout?')) {
            document.getElementById('logoutForm').submit();
        }
    }
</script>

@endsection
