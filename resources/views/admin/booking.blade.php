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
}

/* Nav links - base styles */
#sidebar nav a {
    color: #ffffff; /* Always white by default */
    transition: background-color 0.3s ease, transform 0.3s ease;
}

/* Light mode - non-active links become dark */
.dark #sidebar nav a:not(.bg-red-600\/60) {
    color: #1e293b;
}

/* Active link - ALWAYS white in both themes */
#sidebar nav a.bg-red-600\/60 {
    color: #ffffff !important;
}

/* Hover states - keep same red shade */
#sidebar nav a:hover {
    background-color: rgba(220, 38, 38, 0.4) !important; /* Same red in both themes */
}

/* Active state - keep same red shade */
#sidebar nav a.bg-red-600\/60 {
    background-color: rgba(220, 38, 38) !important; /* Same red in both themes */
}

/* Logo styling - smooth transition */
#sidebar img[src*="logo.png"] {
    transition: filter 0.3s ease;
}

/* Darken logo in light mode while keeping red tones */
.dark #sidebar img[src*="logo.png"] {
    filter: brightness(0.3) saturate(1.5);
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


    /* Enhanced Status Pill Styles - Match Vehicle Page */
.status-pill {
    display: inline-block;
    padding: 0.375rem 0.75rem;
    border-radius: 9999px; /* Full rounded pill shape */
    font-weight: 600;
    font-size: 0.875rem;
    text-align: center;
    transition: all 0.3s ease;
}

/* Pending Status - Yellow/Amber Pill */
.status-pill.pending {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #ffffff;
    box-shadow: 0 2px 4px rgba(245, 158, 11, 0.3);
}

/* Confirmed Status - Blue Pill */
.status-pill.confirmed {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: #ffffff;
    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
}

/* Ongoing Status - Purple Pill */
.status-pill.ongoing {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    color: #ffffff;
    box-shadow: 0 2px 4px rgba(139, 92, 246, 0.3);
}

/* Completed Status - Green Pill */
.status-pill.completed {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #ffffff;
    box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
}

/* Cancelled Status - Red Pill */
.status-pill.cancelled {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: #ffffff;
    box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
}

/* Light mode adjustments */
.dark .status-pill.pending {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    box-shadow: 0 2px 4px rgba(217, 119, 6, 0.4);
}

.dark .status-pill.confirmed {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    box-shadow: 0 2px 4px rgba(37, 99, 235, 0.4);
}

.dark .status-pill.ongoing {
    background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
    box-shadow: 0 2px 4px rgba(124, 58, 237, 0.4);
}

.dark .status-pill.completed {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    box-shadow: 0 2px 4px rgba(5, 150, 105, 0.4);
}

.dark .status-pill.cancelled {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    box-shadow: 0 2px 4px rgba(220, 38, 38, 0.4);
}

/* Hover effect for pills */
.status-pill:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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

#searchInput {
    background-color: rgba(62, 61, 61, 0.2) !important; /* Semi-transparent black */
    color: #ffffff !important; /* White text */
}

.dark #searchInput {
    background-color: rgba(119, 119, 119, 0.2) !important; /* Semi-transparent white */
    color: #000000 !important; /* Dark text */
    border-color: #000000 !important; /* Force border to black */
}

/* Specific rule for placeholder in light mode */
.dark #searchInput::placeholder {
    color: #4a4a4a !important; /* Dark gray for placeholder */
    opacity: 1; /* Ensure full opacity if default is lower */
}

/* Allow dropdowns to escape table bounds */
table {
    overflow: visible !important;
}

tbody {
    overflow: visible !important;
}

.actions-menu {
    z-index: 9999;
}
</style>

<div class="flex min-h-screen text-white transition-colors duration-500">

    @include('admin.layout.sidebar')

    {{-- MAIN CONTENT --}}
    <main class="min-h-screen transition-all duration-300 p-8" style="margin-left: 18rem; width: calc(100% - 18rem);">

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
                <div class="stat-card-booking">
                    <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
                    <div class="stat-label">Total Bookings</div>
                </div>
                <div class="stat-card-booking">
                    <div class="stat-value" style="color: #F59E0B;">{{ $stats['pending'] ?? 0 }}</div>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-card-booking">
                    <div class="stat-value" style="color: #3B82F6;">{{ $stats['active'] ?? 0 }}</div>
                    <div class="stat-label">Active</div>
                </div>
                <div class="stat-card-booking">
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
            <div class="rounded-2xl shadow-2xl backdrop-blur-xl overflow-visible">
                <table class="w-full text-left dark-table overflow-visible">
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
                                            <button
                                                class="edit-booking-btn"
                                                data-booking='@json($booking)'
                                            >
                                                Edit
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
        {{-- New Booking Form (copied content) --}}
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
                <form id="bookingForm" method="POST" action="/admin/booking">
                    @csrf
                    <input type="hidden" id="formMethod" name="_method" value="POST">

                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-red-500 mb-4">Client Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="client_first_name">First Name *</label>
                                <input type="text" name="client_first_name" id="client_first_name" required class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" placeholder="First Name">
                            </div>
                            <div class="form-group">
                                <label for="client_last_name">Last Name *</label>
                                <input type="text" name="client_last_name" id="client_last_name" required class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" placeholder="Last Name">
                            </div>
                            <div class="form-group">
                                <label for="client_contact">Contact Number *</label>
                                <input type="tel" name="client_contact" id="client_contact" required class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" placeholder="Contact Number">
                            </div>
                            <div class="form-group">
                                <label for="client_email">Email *</label>
                                <input type="email" name="client_email" id="client_email" required class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label for="client_license">License Number</label>
                                <input type="text" name="client_license" id="client_license" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" placeholder="License Number">
                            </div>
                            <div class="form-group">
                                <label for="client_address">Address</label>
                                <textarea name="client_address" id="client_address" rows="3" placeholder="Address" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Booking Details --}}
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

                    {{-- Vehicles & Driver --}}
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

                    {{-- Pricing & Status --}}
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-red-500 mb-4">Pricing</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="form-group">
                                <label for="total_price">Total Price (₱) *</label>
                                <input type="number" name="total_price" id="total_price" required step="0.01" min="0" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                            </div>

                            {{-- <div class="form-group">
                                <label for="status_id">Booking Status *</label>
                                <select name="status_id" id="status_id" required class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                                    <option value="">-- Select Status --</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->status_id }}">{{ $status->status_name }}</option>
                                    @endforeach
                                </select>
                            </div> --}}

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

                    {{-- Special Requests & Notes --}}
                    <div class="mb-8">
                        <div class="form-group">
                            <label for="special_requests">Special Requests / Notes</label>
                            <textarea name="special_requests" id="special_requests" rows="4" placeholder="Any special requests or notes about this booking..." class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500"></textarea>
                        </div>
                    </div>

                    {{-- Buttons --}}
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

        {{-- ========== EDIT VIEW ========== --}}
        {{-- Copy the New Booking form content here with adjustments for editing --}}
        <div id="editView" class="view-section">
<<<<<<< HEAD
            {{-- Copy of the form with adjustments for editing --}}
            <div class="bg-[#262B32] rounded-2xl shadow-2xl p-8 backdrop-blur-xl border border-white/10 modal-card">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-red-500">Edit Booking</h2>
                    <button onclick="switchView('indexView')" class="text-white text-xl font-semibold hover:text-gray-300">✕</button>
=======
            <div class="modal-container">
                <div class="modal-header">
                    <h2>Edit Booking</h2>
                    <button type="button" onclick="switchView('indexView')">✕</button>
>>>>>>> 6f4b8a783c7792c1561cbb254d37cb4ca9a4fd87
                </div>

                <form id="editBookingForm" method="POST" action="">
                    @csrf
                    @method('PUT')

<<<<<<< HEAD
                    <input type="hidden" id="edit_booking_id" name="booking_id">

                    {{-- Client Information --}}
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-red-500 mb-4">Client Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="edit_client_first_name">First Name *</label>
                                <input type="text" name="client_first_name" id="edit_client_first_name" required class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" placeholder="First Name">
                            </div>
                            <div class="form-group">
                                <label for="edit_client_last_name">Last Name *</label>
                                <input type="text" name="client_last_name" id="edit_client_last_name" required class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" placeholder="Last Name">
                            </div>
                            <div class="form-group">
                                <label for="edit_client_contact">Contact Number *</label>
                                <input type="tel" name="client_contact" id="edit_client_contact" required class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" placeholder="Contact Number">
                            </div>
                            <div class="form-group">
                                <label for="edit_client_email">Email *</label>
                                <input type="email" name="client_email" id="edit_client_email" required class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label for="edit_client_license">License Number</label>
                                <input type="text" name="client_license" id="edit_client_license" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" placeholder="License Number">
                            </div>
                            <div class="form-group">
                                <label for="edit_client_address">Address</label>
                                <textarea name="client_address" id="edit_client_address" rows="3" placeholder="Address" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Booking Details --}}
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-red-500 mb-4">Booking Details</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="edit_start_datetime">Check-in *</label>
                                <input type="datetime-local" name="start_datetime" id="edit_start_datetime" required class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div class="form-group">
                                <label for="edit_end_datetime">Check-out *</label>
                                <input type="datetime-local" name="end_datetime" id="edit_end_datetime" required class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div class="form-group">
                                <label for="edit_pickup_location">Pick-up Location *</label>
                                <input type="text" name="pickup_location" id="edit_pickup_location" required placeholder="e.g., Airport Terminal 1" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div class="form-group">
                                <label for="edit_dropoff_location">Drop-off Location *</label>
                                <input type="text" name="dropoff_location" id="edit_dropoff_location" required placeholder="e.g., Hotel Downtown" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                        </div>
                    </div>

                    {{-- Vehicles & Driver --}}
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-red-500 mb-4">Vehicles & Driver</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label>Select Vehicle(s) *</label>
                                <div style="max-height: 300px; overflow-y: auto; background: rgba(0,0,0,0.2); padding: 1rem; border-radius: 0.75rem;">
                                    @foreach($vehicles as $vehicle)
                                        <label class="vehicle-checkbox">
                                            <input type="checkbox" name="vehicle_ids[]" value="{{ $vehicle->vehicle_id }}" class="edit-vehicle-checkbox">
                                            <span>{{ $vehicle->brand }} {{ $vehicle->model }} - {{ $vehicle->plate_num }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="edit_driver_id">Assign Driver (Optional)</label>
                                <select name="driver_id" id="edit_driver_id" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                                    <option value="">-- No Driver --</option>
                                    @foreach($drivers as $driver)
                                        <option value="{{ $driver->id }}">{{ $driver->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Pricing & Status --}}
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-red-500 mb-4">Pricing & Status</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="form-group">
                                <label for="edit_total_price">Total Price (₱) *</label>
                                <input type="number" name="total_price" id="edit_total_price" required step="0.01" min="0" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div class="form-group">
                                <label for="edit_status_id">Booking Status *</label>
                                <select name="status_id" id="edit_status_id" required class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                                    <option value="">-- Select Status --</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->status_id }}">{{ $status->status_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_payment_method">Payment Method</label>
                                <select name="payment_method" id="edit_payment_method" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                                    <option value="cash">Cash</option>
                                    <option value="credit_card">Credit Card</option>
                                    <option value="online_transfer">Online Transfer</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Special Requests & Notes --}}
                    <div class="mb-8">
                        <div class="form-group">
                            <label for="edit_special_requests">Special Requests / Notes</label>
                            <textarea name="special_requests" id="edit_special_requests" rows="4" placeholder="Any special requests or notes about this booking..." class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500"></textarea>
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="switchView('indexView')" class="btn btn-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="updateBtn">Update Booking</button>
=======
                    {{-- READ ONLY --}}
                    <div class="form-group">
                        <label>Client Name</label>
                        <input type="text"
                            value="{{ $booking->client->full_name }}"
                            disabled>
                    </div>

                    <div class="form-group">
                        <label>Vehicles</label>
                        <input type="text"
                            value="{{ $booking->vehicles->pluck('vehicle.plate_num')->implode(', ') }}"
                            disabled>
                    </div>

                    <div class="form-group">
                        <label>Booking Period</label>
                        <input type="text"
                            value="{{ $booking->start_datetime->format('M d, Y h:i A') }}
                            → {{ $booking->end_datetime->format('M d, Y h:i A') }}"
                            disabled>
                    </div>

                    <div class="form-group">
                        <label>Total Price</label>
                        <input type="text"
                            value="₱{{ number_format($booking->total_price, 2) }}"
                            disabled>
                    </div>

                    {{-- EDITABLE --}}
                    <div class="form-group">
                        <label>Driver</label>
                        <select name="driver_id">
                            <option value="">No Driver</option>
                            @foreach ($drivers as $driver)
                                <option value="{{ $driver->driver_id }}"
                                    @selected($booking->driver_id == $driver->driver_id)>
                                    {{ $driver->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status_id" required>
                            @foreach ($statuses as $status)
                                <option value="{{ $status->status_id }}"
                                    @selected($booking->status_id == $status->status_id)>
                                    {{ $status->status_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Payment Method</label>
                        <input type="text"
                            name="payment_method"
                            value="{{ old('payment_method', $booking->payment_method) }}">
                    </div>

                    <div class="form-group">
                        <label>Notes / Special Requests</label>
                        <textarea name="special_requests" rows="4">{{ old('special_requests', $booking->special_requests) }}</textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn-primary">
                            Update Booking
                        </button>
>>>>>>> 6f4b8a783c7792c1561cbb254d37cb4ca9a4fd87
                    </div>
                </form>
            </div>
        </div>

    </main>
</div>

<script>
function switchView(view) {
    document.querySelectorAll('.view-section').forEach(v => v.classList.remove('active'));
    document.getElementById(view).classList.add('active');
}

// Show booking details (existing code)
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
        const client = booking.client || booking.Client || {};
        const clientName = `${client.first_name || ''} ${client.last_name || ''}`.trim() || 'N/A';

        // Set form fields for editing
        document.getElementById('edit_booking_id').value = booking.id || booking.boarding_id;
        document.getElementById('edit_client_first_name').value = client.first_name || '';
        document.getElementById('edit_client_last_name').value = client.last_name || '';
        document.getElementById('edit_client_contact').value = booking.client_contact || '';
        document.getElementById('edit_client_email').value = booking.client_email || '';
        document.getElementById('edit_client_license').value = booking.client_license || '';
        document.getElementById('edit_client_address').value = booking.client_address || '';
        document.getElementById('edit_start_datetime').value = booking.start_datetime ? new Date(booking.start_datetime).toISOString().slice(0,16) : '';
        document.getElementById('edit_end_datetime').value = booking.end_datetime ? new Date(booking.end_datetime).toISOString().slice(0,16) : '';
        document.getElementById('edit_pickup_location').value = booking.pickup_location || '';
        document.getElementById('edit_dropoff_location').value = booking.dropoff_location || '';
        document.getElementById('edit_total_price').value = booking.total_price || '';
        document.getElementById('edit_status_id').value = booking.status_id || '';
        document.getElementById('edit_payment_method').value = booking.payment_method || '';
        document.getElementById('edit_special_requests').value = booking.special_requests || '';

        // Set vehicle checkboxes
        document.querySelectorAll('.edit-vehicle-checkbox').forEach(cb => cb.checked = false);
        (booking.vehicles || []).forEach(vehicle => {
            const vId = vehicle.vehicle_id || vehicle.id;
            document.querySelector(`input.edit-vehicle-checkbox[value="${vId}"]`)?.setAttribute('checked', 'checked');
        });

        // Set form action dynamically
        document.getElementById('editBookingForm').action = `/admin/booking/${booking.id || booking.boarding_id}`;

        // Switch to edit view
        switchView('editView');
    })
    .catch(err => {
        console.error('Error loading booking:', err);
        alert('Failed to load booking details: ' + (err.message || 'Please check console for details'));
    });
}

// Search filtering
document.getElementById('searchInput').addEventListener('input', (e) => {
    const term = e.target.value.toLowerCase();
    document.querySelectorAll('.booking-row').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
    });
});

// Form submit handling
document.getElementById('bookingForm').addEventListener('submit', (e) => {
    e.preventDefault();
    const formEl = e.currentTarget;
    const formData = new FormData(formEl);
    const method = document.getElementById('formMethod').value;
    const isUpdate = method === 'PUT';
    const url = isUpdate ? formEl.action : '/admin/booking';

    fetch(url, {
        method: 'POST',
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

// Reset form for new booking
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

// Theme toggle functionality
document.getElementById('theme-toggle').addEventListener('click', function() {
    const html = document.documentElement;
    const body = document.body;
    const themeIcon = document.getElementById('theme-icon');
    const themeText = this.querySelector('span');
    
    html.classList.toggle('dark');
    body.classList.toggle('dark');
    
    if (html.classList.contains('dark')) {
        themeIcon.src = '{{ asset('assets/sun.png') }}';
        themeText.textContent = 'Light Mode';
        themeIcon.classList.add('rotate-360');
        setTimeout(() => themeIcon.classList.remove('rotate-360'), 500);
    } else {
        themeIcon.src = '{{ asset('assets/moon.png') }}';
        themeText.textContent = 'Dark Mode';
        themeIcon.classList.add('rotate-360');
        setTimeout(() => themeIcon.classList.remove('rotate-360'), 500);
    }
});

// Run on page load and watch for changes
document.addEventListener('DOMContentLoaded', function() {
    updateTheme();
    
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'class') {
                updateTheme();
            }
        });
    });
    
    observer.observe(document.documentElement, {
        attributes: true
    });
});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.edit-booking-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const booking = JSON.parse(btn.dataset.booking);

            document.getElementById('edit_booking_id').value = booking.id;
            document.getElementById('edit_client_first_name').value = booking.client?.first_name || '';
            document.getElementById('edit_client_last_name').value = booking.client?.last_name || '';
            document.getElementById('edit_client_contact').value = booking.client_contact || '';
            document.getElementById('edit_client_email').value = booking.client_email || '';
            document.getElementById('edit_client_license').value = booking.client_license || '';
            document.getElementById('edit_client_address').value = booking.client_address || '';
            document.getElementById('edit_start_datetime').value = booking.start_datetime ? new Date(booking.start_datetime).toISOString().slice(0,16) : '';
            document.getElementById('edit_end_datetime').value = booking.end_datetime ? new Date(booking.end_datetime).toISOString().slice(0,16) : '';
            document.getElementById('edit_pickup_location').value = booking.pickup_location || '';
            document.getElementById('edit_dropoff_location').value = booking.dropoff_location || '';
            document.getElementById('edit_total_price').value = booking.total_price || '';
            document.getElementById('edit_status_id').value = booking.status_id || '';
            document.getElementById('edit_payment_method').value = booking.payment_method || '';
            document.getElementById('edit_special_requests').value = booking.special_requests || '';

            // Set vehicle checkboxes
            document.querySelectorAll('.edit-vehicle-checkbox').forEach(cb => cb.checked = false);
            (booking.vehicles || []).forEach(vehicle => {
                const vId = vehicle.vehicle_id || vehicle.id;
                document.querySelector(`input.edit-vehicle-checkbox[value="${vId}"]`)?.setAttribute('checked', 'checked');
            });

            // Set form action dynamically
            document.getElementById('editBookingForm').action = `/admin/booking/${booking.id || booking.boarding_id}`;

            // Switch to edit view
            switchView('editView');
        });
    });
});
</script>
<script>
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.edit-booking-btn');
    if (!btn) return;

    const booking = JSON.parse(btn.dataset.booking);

    // Switch view
    switchView('editView');

    // Set form action
    const form = document.getElementById('editBookingForm');
    form.action = `/admin/booking/${booking.boarding_id}`;

    // Populate fields
    form.querySelector('[name="driver_id"]').value = booking.driver_id ?? '';
    form.querySelector('[name="status_id"]').value = booking.status_id;
    form.querySelector('[name="payment_method"]').value = booking.payment_method ?? '';
    form.querySelector('[name="special_requests"]').value = booking.special_requests ?? '';

    // Read-only fields
    form.querySelector('#edit-client-name').value =
        `${booking.client.first_name} ${booking.client.last_name}`;

    form.querySelector('#edit-vehicles').value =
        booking.vehicles.map(v => v.vehicle.plate_num).join(', ');

    form.querySelector('#edit-period').value =
        `${booking.start_datetime} → ${booking.end_datetime}`;

    form.querySelector('#edit-price').value =
        `₱${Number(booking.total_price).toLocaleString()}`;
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