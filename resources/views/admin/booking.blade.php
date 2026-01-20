@extends('layouts.app')

@section('content')
@vite('resources/css/app.css')

<style>
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
        background-color: rgba(220, 38, 38, 0.4) !important;
    }

    /* Active state - keep same red shade */
    #sidebar nav a.bg-red-600\/60 {
        background-color: rgba(220, 38, 38) !important;
    }

    /* Logo styling - smooth transition */
    #sidebar img[src*="logo.png"] {
        transition: filter 0.3s ease;
    }

    /* Darken logo in light mode while keeping red tones */
    .dark #sidebar img[src*="logo.png"] {
        filter: brightness(0.3) saturate(1.5);
    }

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
    }

    /* Actions dropdown styling */
    .actions-menu.dropup {
        bottom: 100%;
        top: auto;
        margin-bottom: 0.5rem;
        margin-top: 0;
    }

    /* Allow dropdowns to escape table bounds */
    table { overflow: visible !important; }
    tbody { overflow: visible !important; }
    .actions-menu { z-index: 9999; }

    /* Status Pill Styles (match other admin pages) */
    .status-pill {
        display: inline-block;
        padding: 0.35rem 0.85rem;
        border-radius: 9999px;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: capitalize;
        background: transparent;
        color: #fff;
    }
    .status-pill.pending { background: #facc15; }
    .status-pill.confirmed { background: #2563EB; }
    .status-pill.ongoing { background: #0D9488; }
    .status-pill.completed { background: #22c55e; }
    .status-pill.cancelled { background: #ef4444; }

    /* View details cards (consistent with other pages that use detail cards) */
    .detail-card {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 0.75rem;
        padding: 1rem;
    }
    .detail-label {
        font-size: 0.75rem;
        color: #9CA3AF;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }
    .detail-value {
        font-size: 0.95rem;
        color: #fff;
        font-weight: 600;
        word-break: break-word;
    }

    #searchInput {
        background-color: rgba(62, 61, 61, 0.2) !important;
        color: #ffffff !important;
    }
    .dark #searchInput {
        background-color: rgba(119, 119, 119, 0.2) !important;
        color: #000000 !important;
        border-color: #000000 !important;
    }
    .dark #searchInput::placeholder {
        color: #4a4a4a !important;
        opacity: 1;
    }
</style>

<div class="flex min-h-screen text-white transition-colors duration-500">
    @include('admin.layout.sidebar')

    <main class="min-h-screen transition-all duration-300 p-8" style="margin-left: 18rem; width: calc(100% - 18rem);">
        <div class="flex justify-between items-center mb-6">
            <h1 id="pageTitle" class="text-3xl font-bold text-red-500 drop-shadow-lg">Booking Management</h1>

            <div class="flex items-center space-x-4">
                {{-- Theme Toggle --}}
                <button id="theme-toggle" class="flex items-center gap-2 bg-black/30 backdrop-blur-xl p-2 rounded-lg hover:bg-[#998282] transition-all duration-300 cursor-pointer">
                    <img id="theme-icon" src="{{ asset('assets/moon.png') }}" class="w-6 h-6 transition-transform duration-500">
                    <span class="font-medium text-white">Dark Mode</span>
                </button>

                <button id="newBookingBtn" type="button"
                        onclick="switchView('createView')"
                        class="cursor-pointer px-5 py-2 bg-green-700 hover:bg-green-500 rounded-xl text-white shadow-lg transition-all duration-300 hover:scale-105">
                    + New Booking
                </button>
            </div>
        </div>

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

        {{-- INDEX --}}
        <div id="indexView" class="view-section active">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="stat-card-booking">
                    <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
                    <div class="stat-label">Total Bookings</div>
                </div>
                <div class="stat-card-booking">
                    <div class="stat-value" style="color:#F59E0B">{{ $stats['pending'] ?? 0 }}</div>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-card-booking">
                    <div class="stat-value" style="color:#3B82F6">{{ $stats['active'] ?? 0 }}</div>
                    <div class="stat-label">Active</div>
                </div>
                <div class="stat-card-booking">
                    <div class="stat-value" style="color:#10B981">₱{{ number_format($stats['todayRevenue'] ?? 0, 2) }}</div>
                    <div class="stat-label">Today's Revenue</div>
                </div>
            </div>

            <div class="flex justify-between items-center mb-6">
                <input id="searchInput" type="text" placeholder="Search booking ID, client name..."
                       class="w-80 p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" />
            </div>

            <div class="rounded-2xl shadow-2xl backdrop-blur-xl overflow-visible">
                <table class="w-full text-left dark-table overflow-visible">
                    <thead class="bg-black/30 text-white uppercase text-sm tracking-wide text-center">
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
                            @php
                                $statusClass = 'pending';
                                if ($booking->status_id == 2) $statusClass = 'confirmed';
                                elseif ($booking->status_id == 3) $statusClass = 'ongoing';
                                elseif ($booking->status_id == 4) $statusClass = 'completed';
                                elseif ($booking->status_id == 5) $statusClass = 'cancelled';
                            @endphp
                            <tr class="border-b border-white/10 hover:bg-white/10 transition-all booking-row text-center">
                                <td class="p-4 font-semibold">#{{ str_pad($booking->boarding_id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td class="p-4">{{ $booking->client->first_name ?? 'N/A' }} {{ $booking->client->last_name ?? '' }}</td>
                                <td class="p-4">
                                    @foreach($booking->vehicles as $bv)
                                        <span class="inline-block px-2 py-1 text-sm bg-white/5 rounded-lg mr-1 mb-1">
                                            {{ $bv->vehicle->plate_num ?? ($bv->vehicle->brand ?? 'Vehicle') }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="p-4 text-sm">{{ $booking->start_datetime ? $booking->start_datetime->format('M d H:i') : 'N/A' }}</td>
                                <td class="p-4 text-sm">{{ $booking->end_datetime ? $booking->end_datetime->format('M d H:i') : 'N/A' }}</td>
                                <td class="p-4 font-bold text-green-300">₱{{ number_format($booking->total_price, 2) }}</td>
                                <td class="p-4">
                                    <span class="status-pill {{ $statusClass }}">{{ $booking->status->status_name ?? 'Unknown' }}</span>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="relative inline-block actions">
                                        <button type="button" class="actions-toggle p-2 rounded-full hover:bg-white/10 focus:outline-none" aria-expanded="false">
                                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="5" cy="12" r="1.5" />
                                                <circle cx="12" cy="12" r="1.5" />
                                                <circle cx="19" cy="12" r="1.5" />
                                            </svg>
                                        </button>

                                        <div class="actions-menu hidden absolute right-0 mt-2 w-44 bg-[#262B32] rounded-lg shadow-xl z-50 border border-white/10" style="transform: translateZ(0); pointer-events: auto;" role="menu">
                                            <button type="button" class="view-booking-btn flex items-center gap-3 w-full px-3 py-2 text-white hover:bg-white/5" data-id="{{ $booking->boarding_id }}" role="menuitem">
                                                <img src="{{ asset('assets/file.png') }}" alt="View" class="w-5 h-5">
                                                <span>View Details</span>
                                            </button>
                                            <button type="button" class="edit-booking-btn flex items-center gap-3 w-full px-3 py-2 text-white hover:bg-white/5" data-id="{{ $booking->boarding_id }}" role="menuitem">
                                                <img src="{{ asset('assets/edit.png') }}" alt="Edit" class="w-5 h-5">
                                                <span>Edit</span>
                                            </button>
                                            <button type="button" class="process-payment-btn flex items-center gap-3 w-full px-3 py-2 text-white hover:bg-white/5" data-id="{{ $booking->boarding_id }}" role="menuitem">
                                                <img src="{{ asset('assets/file.png') }}" alt="Payment" class="w-5 h-5">
                                                <span>Process Payment</span>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-gray-400">No bookings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $bookings->links() }}
            </div>
        </div>

        {{-- CREATE --}}
        <div id="createView" class="view-section">
            <div class="bg-[#262B32] rounded-2xl shadow-2xl p-8 backdrop-blur-xl border border-white/10 modal-card">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-red-500">New Booking</h2>
                    <button type="button" onclick="switchView('indexView')" class="text-white text-xl font-semibold hover:text-gray-300">✕</button>
                </div>

                <form method="POST" action="{{ route('admin.booking.store') }}">
                    @csrf

                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-red-500 mb-4">Client Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="font-semibold mb-2 block" for="client_first_name">First Name *</label>
                                <input class="w-full p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" type="text" name="client_first_name" id="client_first_name" required />
                            </div>
                            <div>
                                <label class="font-semibold mb-2 block" for="client_last_name">Last Name *</label>
                                <input class="w-full p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" type="text" name="client_last_name" id="client_last_name" required />
                            </div>
                            <div>
                                <label class="font-semibold mb-2 block" for="client_contact">Contact Number *</label>
                                <input class="w-full p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" type="tel" name="client_contact" id="client_contact" required />
                            </div>
                            <div>
                                <label class="font-semibold mb-2 block" for="client_email">Email *</label>
                                <input class="w-full p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" type="email" name="client_email" id="client_email" required />
                            </div>
                            <div>
                                <label class="font-semibold mb-2 block" for="client_license">License Number</label>
                                <input class="w-full p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" type="text" name="client_license" id="client_license" />
                            </div>
                            <div>
                                <label class="font-semibold mb-2 block" for="client_address">Address</label>
                                <textarea class="w-full p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" name="client_address" id="client_address" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-red-500 mb-4">Booking Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="font-semibold mb-2 block" for="start_datetime">Check-in *</label>
                                <input class="w-full p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" type="datetime-local" name="start_datetime" id="start_datetime" required />
                            </div>
                            <div>
                                <label class="font-semibold mb-2 block" for="end_datetime">Check-out *</label>
                                <input class="w-full p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" type="datetime-local" name="end_datetime" id="end_datetime" required />
                            </div>
                            <div>
                                <label class="font-semibold mb-2 block" for="pickup_location">Pick-up Location *</label>
                                <input class="w-full p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" type="text" name="pickup_location" id="pickup_location" required />
                            </div>
                            <div>
                                <label class="font-semibold mb-2 block" for="dropoff_location">Drop-off Location *</label>
                                <input class="w-full p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" type="text" name="dropoff_location" id="dropoff_location" required />
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-red-500 mb-4">Vehicles & Driver</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="font-semibold mb-2 block">Select Vehicle(s) *</label>
                                <div style="max-height: 260px; overflow-y: auto; background: rgba(0,0,0,0.2); padding: 1rem; border-radius: 0.75rem;">
                                    @foreach($vehicles as $vehicle)
                                        <label class="flex items-center gap-2 mb-2">
                                            <input type="checkbox" name="vehicle_ids[]" value="{{ $vehicle->vehicle_id }}" />
                                            <span>{{ $vehicle->brand }} {{ $vehicle->model }} - {{ $vehicle->plate_num }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <label class="font-semibold mb-2 block" for="driver_id">Assign Driver (Optional)</label>
                                <select class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" name="driver_id" id="driver_id">
                                    <option value="">-- No Driver --</option>
                                    @foreach($drivers as $driver)
                                        <option value="{{ $driver->id }}">{{ $driver->full_name ?? $driver->name ?? ('Driver #' . $driver->id) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-red-500 mb-4">Pricing & Status</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="font-semibold mb-2 block" for="total_price">Total Price (₱) *</label>
                                <input class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" type="number" name="total_price" id="total_price" required step="0.01" min="0" />
                            </div>
                            <div>
                                <label class="font-semibold mb-2 block" for="status_id">Booking Status *</label>
                                <select class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" name="status_id" id="status_id" required>
                                    <option value="">-- Select Status --</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->status_id }}">{{ $status->status_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="font-semibold mb-2 block" for="payment_method">Payment Method</label>
                                <select class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" name="payment_method" id="payment_method">
                                    <option value="">-- Select Payment --</option>
                                    <option value="cash">Cash</option>
                                    <option value="credit_card">Credit Card</option>
                                    <option value="online_transfer">Online Transfer</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="font-semibold mb-2 block" for="special_requests">Special Requests / Notes</label>
                        <textarea class="w-full p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" name="special_requests" id="special_requests" rows="4"></textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="switchView('indexView')" class="cursor-pointer px-5 py-2 bg-blue-700 hover:bg-blue-500 rounded-xl text-white shadow-lg transition-all duration-300">Cancel</button>
                        <button type="submit" class="cursor-pointer px-5 py-2 bg-green-700 hover:bg-green-500 rounded-xl text-white shadow-lg transition-all duration-300 hover:scale-105">Create Booking</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- SHOW (read-only) --}}
        <div id="showView" class="view-section">
            <div class="bg-[#262B32] rounded-2xl shadow-2xl p-8 backdrop-blur-xl border border-white/10 modal-card">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-red-500">Booking Details</h2>
                    <button type="button" onclick="switchView('indexView')" class="text-white text-xl font-semibold hover:text-gray-300">✕</button>
                </div>
                <div id="showContent" class="space-y-4"></div>
            </div>
        </div>

        {{-- EDIT (only 4 fields editable) --}}
        <div id="editView" class="view-section">
            <div class="bg-[#262B32] rounded-2xl shadow-2xl p-8 backdrop-blur-xl border border-white/10 modal-card">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-red-500">Edit Booking</h2>
                    <button type="button" onclick="switchView('indexView')" class="text-white text-xl font-semibold hover:text-gray-300">✕</button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="detail-card">
                        <div class="detail-label">Booking ID</div>
                        <div class="detail-value" id="edit_display_booking_id">—</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Client</div>
                        <div class="detail-value" id="edit_display_client">—</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Contact</div>
                        <div class="detail-value" id="edit_display_contact">—</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Email</div>
                        <div class="detail-value" id="edit_display_email">—</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Schedule</div>
                        <div class="detail-value" id="edit_display_schedule">—</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Route</div>
                        <div class="detail-value" id="edit_display_route">—</div>
                    </div>
                    <div class="detail-card md:col-span-2">
                        <div class="detail-label">Vehicles</div>
                        <div class="detail-value" id="edit_display_vehicles">—</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Total Price</div>
                        <div class="detail-value" id="edit_display_total">—</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Current Status</div>
                        <div class="detail-value" id="edit_display_status">—</div>
                    </div>
                </div>

                <form id="editBookingForm" method="POST" action="">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="font-semibold mb-2 block" for="edit_driver_id">Assign Driver (Editable)</label>
                            <select class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" name="driver_id" id="edit_driver_id">
                                <option value="">-- No Driver --</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}">{{ $driver->full_name ?? $driver->name ?? ('Driver #' . $driver->id) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="font-semibold mb-2 block" for="edit_status_id">Booking Status (Editable) *</label>
                            <select class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" name="status_id" id="edit_status_id" required>
                                <option value="">-- Select Status --</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->status_id }}">{{ $status->status_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="font-semibold mb-2 block" for="edit_payment_method">Payment Method (Editable)</label>
                            <select class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" name="payment_method" id="edit_payment_method">
                                <option value="">-- Select Payment --</option>
                                <option value="cash">Cash</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="online_transfer">Online Transfer</option>
                            </select>
                        </div>
                        <div></div>
                    </div>

                    {{-- Fleet Assistant Pickup/Dropoff Fields (only for self-drive bookings) --}}
                    <div id="fleetAssistantFields" class="hidden mt-6 border-t border-white/10 pt-6">
                        <h3 class="text-lg font-semibold text-red-500 mb-4">Vehicle Handover (Self-Drive Only)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="font-semibold mb-2 block" for="edit_sent_by">Sent By (Fleet Assistant)</label>
                                <select class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" name="sent_by" id="edit_sent_by">
                                    <option value="">-- Select Fleet Assistant --</option>
                                    @foreach(\App\Models\User::where('role', 'fleet_assistant')->orWhere('role', 'admin')->get() as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="font-semibold mb-2 block" for="edit_received_by">Received By (Client Name/Signature)</label>
                                <input type="text" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" name="received_by" id="edit_received_by" placeholder="Client name or signature">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="font-semibold mb-2 block" for="edit_collected_by">Collected By (Fleet Assistant)</label>
                                <select class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" name="collected_by" id="edit_collected_by">
                                    <option value="">-- Select Fleet Assistant --</option>
                                    @foreach(\App\Models\User::where('role', 'fleet_assistant')->orWhere('role', 'admin')->get() as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="font-semibold mb-2 block" for="edit_returned_by">Returned By (Client Name/Signature)</label>
                                <input type="text" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" name="returned_by" id="edit_returned_by" placeholder="Client name or signature">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="font-semibold mb-2 block" for="edit_special_requests">Notes (Editable)</label>
                        <textarea class="w-full p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" name="special_requests" id="edit_special_requests" rows="4"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" onclick="switchView('indexView')" class="cursor-pointer px-5 py-2 bg-blue-700 hover:bg-blue-500 rounded-xl text-white shadow-lg transition-all duration-300">Cancel</button>
                        <button type="submit" class="cursor-pointer px-5 py-2 bg-yellow-700 hover:bg-yellow-500 rounded-xl text-white shadow-lg transition-all duration-300 hover:scale-105">Update Booking</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- PROCESS PAYMENT MODAL --}}
        <div id="paymentModal" class="view-section">
            <div class="bg-[#262B32] rounded-2xl shadow-2xl p-8 backdrop-blur-xl border border-white/10 modal-card">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-red-500">Process Payment</h2>
                    <button type="button" onclick="closePaymentModal()" class="text-white text-xl font-semibold hover:text-gray-300">✕</button>
                </div>
                
                <div id="paymentBookingInfo" class="mb-6 p-4 bg-black/20 rounded-xl">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-400">Booking ID:</span>
                            <span class="text-white font-semibold" id="payment_booking_id">—</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Client:</span>
                            <span class="text-white font-semibold" id="payment_client">—</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Total Amount:</span>
                            <span class="text-white font-semibold" id="payment_total">—</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Remaining Balance:</span>
                            <span class="text-white font-semibold" id="payment_balance">—</span>
                        </div>
                    </div>
                </div>

                <form id="paymentForm" method="POST">
                    @csrf
                    <input type="hidden" id="payment_booking_id_hidden" name="booking_id">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="font-semibold mb-2 block" for="payment_amount">Payment Amount *</label>
                            <input type="number" step="0.01" min="0" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" name="amount" id="payment_amount" required placeholder="0.00">
                        </div>
                        <div>
                            <label class="font-semibold mb-2 block" for="payment_method_select">Payment Method *</label>
                            <select class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" name="payment_method" id="payment_method_select" required>
                                <option value="">-- Select Payment Method --</option>
                                <option value="cash">Cash</option>
                                <option value="gcash">GCash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="online_transfer">Online Transfer</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="font-semibold mb-2 block" for="payment_reference">Reference Number (Optional)</label>
                        <input type="text" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500 transition-all duration-300" name="reference_number" id="payment_reference" placeholder="Transaction reference number">
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closePaymentModal()" class="cursor-pointer px-5 py-2 bg-gray-600 hover:bg-gray-500 rounded-xl text-white shadow-lg transition-all duration-300">Cancel</button>
                        <button type="submit" class="cursor-pointer px-5 py-2 bg-green-700 hover:bg-green-500 rounded-xl text-white shadow-lg transition-all duration-300 hover:scale-105">Process Payment & Generate Receipt</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
    function switchView(viewId) {
        document.querySelectorAll('.view-section').forEach(v => v.classList.remove('active'));
        document.getElementById(viewId)?.classList.add('active');
    }

    function escapeHtml(str) {
        return String(str ?? '').replace(/[&<>"']/g, (m) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        }[m]));
    }

    function formatDateTime(dateStr) {
        if (!dateStr) return 'N/A';
        const d = new Date(dateStr);
        if (Number.isNaN(d.getTime())) return 'N/A';
        return d.toLocaleString('en-US', { year:'numeric', month:'short', day:'numeric', hour:'2-digit', minute:'2-digit' });
    }

    async function fetchBooking(id) {
        const r = await fetch(`/admin/booking/${id}`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        if (!r.ok) {
            const t = await r.text().catch(() => '');
            throw new Error(t || `Failed to load booking (${r.status})`);
        }
        const json = await r.json();
        return json.data || json.booking || json;
    }

    function renderShowView(booking) {
        const client = booking.client || {};
        const vehicles = Array.isArray(booking.vehicles) ? booking.vehicles : [];
        const driver = booking.driver || null;
        const status = booking.status || {};

        const vehiclesHtml = vehicles.length
            ? vehicles.map(v => `<li class="mb-1">${escapeHtml(`${v.brand ?? ''} ${v.model ?? ''}`.trim())} <span class="opacity-80">(${escapeHtml(v.plate_num ?? v.vehicle_id ?? '')})</span></li>`).join('')
            : '<li class="opacity-80">N/A</li>';

        const html = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="detail-card">
                    <div class="detail-label">Booking ID</div>
                    <div class="detail-value">#${escapeHtml(String(booking.boarding_id ?? ''))}</div>
                </div>
                <div class="detail-card">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">${escapeHtml(status.name ?? '')}</div>
                </div>

                <div class="detail-card">
                    <div class="detail-label">Client</div>
                    <div class="detail-value">${escapeHtml(`${client.first_name ?? ''} ${client.last_name ?? ''}`.trim() || 'N/A')}</div>
                </div>
                <div class="detail-card">
                    <div class="detail-label">Contact</div>
                    <div class="detail-value">${escapeHtml(client.contact_number ?? 'N/A')}</div>
                </div>
                <div class="detail-card">
                    <div class="detail-label">Email</div>
                    <div class="detail-value">${escapeHtml(client.email ?? 'N/A')}</div>
                </div>
                <div class="detail-card">
                    <div class="detail-label">Payment Method</div>
                    <div class="detail-value">${escapeHtml(booking.payment_method ?? 'N/A')}</div>
                </div>

                <div class="detail-card">
                    <div class="detail-label">Check-in</div>
                    <div class="detail-value">${escapeHtml(formatDateTime(booking.start_datetime))}</div>
                </div>
                <div class="detail-card">
                    <div class="detail-label">Check-out</div>
                    <div class="detail-value">${escapeHtml(formatDateTime(booking.end_datetime))}</div>
                </div>
                <div class="detail-card">
                    <div class="detail-label">Pick-up</div>
                    <div class="detail-value">${escapeHtml(booking.pickup_location ?? 'N/A')}</div>
                </div>
                <div class="detail-card">
                    <div class="detail-label">Drop-off</div>
                    <div class="detail-value">${escapeHtml(booking.dropoff_location ?? 'N/A')}</div>
                </div>

                <div class="detail-card">
                    <div class="detail-label">Assigned Driver</div>
                    <div class="detail-value">${escapeHtml(driver?.full_name ?? 'N/A')}</div>
                </div>
                <div class="detail-card">
                    <div class="detail-label">Total Price</div>
                    <div class="detail-value">₱${escapeHtml(Number(booking.total_price ?? 0).toFixed(2))}</div>
                </div>

                <div class="detail-card md:col-span-2">
                    <div class="detail-label">Vehicles</div>
                    <ul class="mt-2">${vehiclesHtml}</ul>
                </div>

                <div class="detail-card md:col-span-2">
                    <div class="detail-label">Notes</div>
                    <div class="detail-value">${escapeHtml(booking.special_requests ?? 'N/A')}</div>
                </div>
            </div>
        `;

        document.getElementById('showContent').innerHTML = html;
    }

    function populateEditView(booking) {
        const client = booking.client || {};
        const vehicles = Array.isArray(booking.vehicles) ? booking.vehicles : [];
        const status = booking.status || {};

        const bookingId = booking.boarding_id ?? booking.id;

        document.getElementById('edit_display_booking_id').textContent = bookingId ? `#${String(bookingId)}` : '—';
        document.getElementById('edit_display_client').textContent = (`${client.first_name ?? ''} ${client.last_name ?? ''}`.trim()) || '—';
        document.getElementById('edit_display_contact').textContent = client.contact_number ?? '—';
        document.getElementById('edit_display_email').textContent = client.email ?? '—';
        document.getElementById('edit_display_schedule').textContent = `${formatDateTime(booking.start_datetime)} → ${formatDateTime(booking.end_datetime)}`;
        document.getElementById('edit_display_route').textContent = `${booking.pickup_location ?? 'N/A'} → ${booking.dropoff_location ?? 'N/A'}`;
        document.getElementById('edit_display_total').textContent = `₱${Number(booking.total_price ?? 0).toFixed(2)}`;
        document.getElementById('edit_display_status').textContent = status.name ?? (booking.status_id ?? '—');

        document.getElementById('edit_display_vehicles').textContent = vehicles.length
            ? vehicles.map(v => `${v.plate_num ?? v.vehicle_id ?? ''}`).filter(Boolean).join(', ')
            : '—';

        document.getElementById('edit_driver_id').value = booking.driver_id ?? (booking.driver?.id ?? '');
        document.getElementById('edit_status_id').value = booking.status_id ?? '';
        document.getElementById('edit_payment_method').value = booking.payment_method ?? '';
        document.getElementById('edit_special_requests').value = booking.special_requests ?? '';
        
        // Populate Fleet Assistant fields
        document.getElementById('edit_sent_by').value = booking.sent_by ?? '';
        document.getElementById('edit_received_by').value = booking.received_by ?? '';
        document.getElementById('edit_collected_by').value = booking.collected_by ?? '';
        document.getElementById('edit_returned_by').value = booking.returned_by ?? '';
        
        // Show Fleet Assistant fields only for self-drive bookings and if user is Fleet Assistant or Admin
        const userRole = '{{ auth()->user()->role ?? "" }}';
        const isFleetAssistant = userRole === 'fleet_assistant' || userRole === 'admin';
        const isSelfDrive = booking.pickup_type === 'self_drive';
        const fleetFields = document.getElementById('fleetAssistantFields');
        if (fleetFields) {
            fleetFields.classList.toggle('hidden', !(isFleetAssistant && isSelfDrive));
        }

        document.getElementById('editBookingForm').action = `/admin/booking/${bookingId}`;
    }

    // Actions dropdown
    document.addEventListener('click', function(e) {
        const toggle = e.target.closest('.actions-toggle');

        if (toggle) {
            const actions = toggle.closest('.actions');
            const menu = actions.querySelector('.actions-menu');

            // Close other open menus
            document.querySelectorAll('.actions-menu').forEach(m => {
                if (m !== menu) {
                    m.classList.add('hidden');
                    m.classList.remove('dropup');
                }
            });

            // Toggle this menu
            menu.classList.toggle('hidden');
            toggle.setAttribute('aria-expanded', (!menu.classList.contains('hidden')).toString());

            // Dropup check when opened
            if (!menu.classList.contains('hidden')) {
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

        // If clicking a menu item, close the menu shortly after (but allow other listeners)
        if (e.target.closest('.actions-menu button')) {
            const menu = e.target.closest('.actions-menu');
            setTimeout(() => menu.classList.add('hidden'), 100);
            return;
        }

        // Click outside: close all menus
        document.querySelectorAll('.actions-menu').forEach(m => m.classList.add('hidden'));
    });

    // View/Edit handlers
    document.addEventListener('click', async (e) => {
        const viewBtn = e.target.closest('.view-booking-btn');
        if (viewBtn) {
            const id = viewBtn.dataset.id;
            try {
                const booking = await fetchBooking(id);
                renderShowView(booking);
                switchView('showView');
            } catch (err) {
                console.error(err);
                alert(err.message || 'Failed to load booking details.');
            }
            return;
        }

        const editBtn = e.target.closest('.edit-booking-btn');
        if (editBtn) {
            const id = editBtn.dataset.id;
            try {
                const booking = await fetchBooking(id);
                populateEditView(booking);
                switchView('editView');
            } catch (err) {
                console.error(err);
                alert(err.message || 'Failed to load booking details.');
            }
            return;
        }

        const paymentBtn = e.target.closest('.process-payment-btn');
        if (paymentBtn) {
            const id = paymentBtn.dataset.id;
            try {
                const booking = await fetchBooking(id);
                openPaymentModal(booking);
            } catch (err) {
                console.error(err);
                alert(err.message || 'Failed to load booking details.');
            }
            return;
        }
    });

    function openPaymentModal(booking) {
        const client = booking.client || {};
        const totalPrice = parseFloat(booking.total_price || 0);
        
        // Calculate remaining balance (assuming we track paid amount in payments table)
        // For now, we'll show the total as remaining balance
        const remainingBalance = totalPrice; // TODO: Calculate from payment history
        
        document.getElementById('payment_booking_id').textContent = `#${String(booking.boarding_id ?? '').padStart(6, '0')}`;
        document.getElementById('payment_client').textContent = `${client.first_name ?? ''} ${client.last_name ?? ''}`.trim() || 'N/A';
        document.getElementById('payment_total').textContent = `₱${totalPrice.toFixed(2)}`;
        document.getElementById('payment_balance').textContent = `₱${remainingBalance.toFixed(2)}`;
        document.getElementById('payment_booking_id_hidden').value = booking.boarding_id ?? booking.id;
        document.getElementById('payment_amount').value = remainingBalance.toFixed(2);
        document.getElementById('payment_amount').max = remainingBalance;
        
        switchView('paymentModal');
    }

    function closePaymentModal() {
        switchView('indexView');
        document.getElementById('paymentForm').reset();
    }

    // Handle payment form submission
    document.getElementById('paymentForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const bookingId = formData.get('booking_id');
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        try {
            submitBtn.textContent = 'Processing...';
            submitBtn.disabled = true;
            
            const response = await fetch(`/admin/booking/${bookingId}/process-payment`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const result = await response.json();
            
            if (response.ok && result.success) {
                alert('Payment processed successfully! Receipt generated.');
                if (result.receipt_url) {
                    window.open(result.receipt_url, '_blank');
                }
                closePaymentModal();
                // Reload bookings list
                location.reload();
            } else {
                alert(result.message || 'Error processing payment');
            }
        } catch (error) {
            console.error('Payment error:', error);
            alert('Error processing payment: ' + error.message);
        } finally {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    });

    // Search filtering
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('.booking-row').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });
    }
</script>

<script>
// Theme toggle functionality
document.getElementById('theme-toggle')?.addEventListener('click', function() {
    const html = document.documentElement;
    const body = document.body;
    const themeIcon = document.getElementById('theme-icon');
    const themeText = this.querySelector('span');
    
    html.classList.toggle('dark');
    body.classList.toggle('dark');
    
    if (html.classList.contains('dark')) {
        if (themeIcon) themeIcon.src = '{{ asset('assets/sun.png') }}';
        if (themeText) themeText.textContent = 'Light Mode';
        themeIcon?.classList.add('rotate-360');
        setTimeout(() => themeIcon?.classList.remove('rotate-360'), 500);
    } else {
        if (themeIcon) themeIcon.src = '{{ asset('assets/moon.png') }}';
        if (themeText) themeText.textContent = 'Dark Mode';
        themeIcon?.classList.add('rotate-360');
        setTimeout(() => themeIcon?.classList.remove('rotate-360'), 500);
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

// Theme switching function
function updateTheme() {
    const isDark = document.documentElement.classList.contains('dark');
    const themeIcon = document.getElementById('theme-icon');
    const themeToggle = document.getElementById('theme-toggle');
    const themeText = themeToggle?.querySelector('span');

    if (isDark) {
        if (themeIcon) themeIcon.src = '{{ asset('assets/sun.png') }}';
        if (themeText) themeText.textContent = 'Light Mode';
    } else {
        if (themeIcon) themeIcon.src = '{{ asset('assets/moon.png') }}';
        if (themeText) themeText.textContent = 'Dark Mode';
    }
}
</script>

<script>
    function confirmLogout(event) {
        event.preventDefault();
        if (confirm('Are you sure you want to logout?')) {
            document.getElementById('logoutForm')?.submit();
        }
    }
</script>

@endsection

