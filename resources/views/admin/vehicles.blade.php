@extends('layouts.app')

@section('content')
@vite('resources/css/app.css')

<style>
/* Theme-driven UI helpers (variables are synced from theme.js) */
:root {
    --action-edit: #2563EB;
    --action-edit-hover: #1E40AF;
    --action-delete: #B91C1C;
    --action-delete-hover: #991B1B;
    --status-green: #16A34A;
    --nav-tab-text: #ffffff;
    --type-pill-bg: rgba(255,255,255,0.04);
    --type-pill-color: #ffffff;
}

#sidebar nav a {
    color: var(--nav-tab-text) !important;
}

.action-edit {
    background-color: var(--action-edit) !important;
    color: #fff !important;
    padding: .5rem .9rem;
    border-radius: .5rem;
}
.action-edit img { filter: brightness(0) invert(1); }
.action-edit:hover { background-color: var(--action-edit-hover) !important; }

.action-delete {
    background-color: var(--action-delete) !important;
    color: #fff !important;
    padding: .5rem .9rem;
    border-radius: .5rem;
}
.action-delete img { filter: brightness(0) invert(1); }
.action-delete:hover { background-color: var(--action-delete-hover) !important; }

.type-pill {
    display: inline-block;
    padding: .18rem .6rem;
    border-radius: 9999px;
    background: var(--type-pill-bg);
    color: var(--type-pill-color);
    font-weight: 600;
    font-size: .9rem;
}

.status-pill {
    display: inline-block;
    padding: .18rem .6rem;
    border-radius: 9999px;
    background: var(--status-green);
    color: #fff;
    font-weight: 700;
    font-size: .85rem;
}

td .action-edit, td .action-delete { display:inline-flex; align-items:center; justify-content:center; gap:.35rem; }

/* Image styling */
.vehicle-image {
    width: 64px;
    height: 64px;
    object-fit: cover;
    border-radius: 0.5rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.image-preview {
    width: 128px;
    height: 128px;
    object-fit: cover;
    border-radius: 0.5rem;
    border: 2px dashed rgba(255, 255, 255, 0.2);
}

.status-available {
    background-color: #16A34A !important;
}

.status-unavailable {
    background-color: #DC2626 !important;
}
</style>

<div class="flex min-h-screen bg-[#1A1F24] text-white transition-colors duration-500" id="dashboard-wrapper">

    {{-- SIDEBAR --}}
    <aside id="sidebar" class="w-64 bg-black/80 h-screen fixed top-0 left-0 shadow-xl border-r border-white/10 backdrop-blur-xl transition-all duration-300 hover:w-72">
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
                ['name' => 'Reports', 'url' => '/admin/reports'],
                ['name' => 'Booking', 'url' => '/admin/booking'],
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

        {{-- SEARCH AND ADD VEHICLE --}}
        <div class="flex justify-between items-center mb-6">
            <input type="text" placeholder="Search plate, model or brand..."
                   class="w-80 p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none 
                   focus:ring-2 focus:ring-red-500 transition-all duration-300"
                   id="searchInput">

            <button id="addVehicleBtn" class="cursor-pointer px-5 py-2 bg-red-700 hover:bg-red-500 rounded-xl text-white shadow-lg transition-all duration-300 hover:scale-105">
                + Add Vehicle
            </button>
        </div>

        {{-- VEHICLE TABLE --}}
        <div class="overflow-hidden rounded-2xl shadow-2xl backdrop-blur-xl card-text dark-card">
            <table class="w-full text-left">
                <thead class="bg-black/30 text-white uppercase text-sm tracking-wide">
                    <tr>
                        <th class="p-4">Image</th>
                        <th class="p-4">Plate No.</th>
                        <th class="p-4">Type</th>
                        <th class="p-4">Brand / Model</th>
                        <th class="p-4">Color</th>
                        <th class="p-4">Transmission</th>
                        <th class="p-4">Capacity</th>
                        <th class="p-4">Rate</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="vehiclesTable" class="text-white">
                </tbody>
            </table>
        </div>

        {{-- ADD / EDIT VEHICLE MODAL --}}
        <div id="vehicleModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" id="vehicleBackdrop"></div>

            <div id="vehicleModalCard" class="modal-content relative w-[500px] p-6 rounded-2xl shadow-2xl bg-[#262B32] transform scale-90 opacity-0 transition-all duration-300">
                <h2 class="text-2xl font-bold text-red-500 mb-4" id="vehicleModalTitle">Add Vehicle</h2>

                <form id="vehicleForm">
                    <div id="vehicleFormError" class="hidden mb-4 p-3 bg-red-600/20 border border-red-500 rounded-lg text-red-300 text-sm"></div>
                    <input type="hidden" id="vehicle_id">
                    
                    {{-- IMAGE UPLOAD SECTION --}}
                    <div class="mb-4 p-4 border border-dashed border-white/20 rounded-xl">
                        <label class="block font-semibold mb-3">Vehicle Image</label>
                        <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                            <div class="relative">
                                <img id="vehicleImagePreview" src="{{ asset('assets/default-vehicle.jpg') }}" 
                                     class="image-preview hover:opacity-90 transition-opacity cursor-pointer"
                                     onclick="document.getElementById('image').click()">
                                <input type="file" id="image" accept="image/*" 
                                       class="hidden" 
                                       onchange="previewImage(event)">
                            </div>
                            <div class="flex-1">
                                <div class="mb-3">
                                    <label for="image" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-lg text-white transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Choose Image
                                    </label>
                                </div>
                                <p class="text-sm text-gray-400 mb-1">JPG, PNG, GIF up to 2MB</p>
                                <p class="text-xs text-gray-500">Leave empty to keep current image</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-3">
                            <label class="block font-semibold mb-1">Plate No.</label>
                            <input type="text" id="plate_no" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none 
                            focus:ring-2 focus:ring-red-500" placeholder="ABC123" maxlength="7" required>
                        </div>

                        <div class="mb-3">
                            <label class="block font-semibold mb-1">Brand</label>
                            <input type="text" id="brand" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none 
                            focus:ring-2 focus:ring-red-500" placeholder="Toyota" maxlength="20" required>
                        </div>

                        <div class="mb-3">
                            <label class="block font-semibold mb-1">Model</label>
                            <input type="text" id="model" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none 
                            focus:ring-2 focus:ring-red-500" placeholder="Fortuner" maxlength="20" required>
                        </div>

                        <div class="mb-3">
                            <label class="block font-semibold mb-1">Year</label>
                            <input type="number" id="year" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none 
                            focus:ring-2 focus:ring-red-500" min="1980" max="2099" required>
                        </div>

                        <div class="mb-3">
                            <label class="block font-semibold mb-1">Body Type</label>
                            <input type="text" id="body_type" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none 
                            focus:ring-2 focus:ring-red-500" placeholder="SUV" maxlength="10" required>
                        </div>

                        <div class="mb-3">
                            <label class="block font-semibold mb-1">Seat Capacity</label>
                            <input type="number" id="seat_cap" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none 
                            focus:ring-2 focus:ring-red-500" min="1" required>
                        </div>

                        <div class="mb-3">
                            <label class="block font-semibold mb-1">Transmission</label>
                            <select id="transmission" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none 
                            focus:ring-2 focus:ring-red-500">
                                <option value="Automatic">Automatic</option>
                                <option value="Manual">Manual</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="block font-semibold mb-1">Fuel Type</label>
                            <select id="fuel_type" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none 
                            focus:ring-2 focus:ring-red-500">
                                <option value="Gasoline">Gasoline</option>
                                <option value="Diesel">Diesel</option>
                                <option value="Electric">Electric</option>
                                <option value="Hybrid">Hybrid</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="block font-semibold mb-1">Color</label>
                            <input type="text" id="color" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none 
                            focus:ring-2 focus:ring-red-500" placeholder="White" maxlength="10" required>
                        </div>

                        <div class="mb-3">
                            <label class="block font-semibold mb-1">Rate (₱)</label>
                            <input type="number" id="price_rate" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none 
                            focus:ring-2 focus:ring-red-500" min="0" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block font-semibold mb-1">Driver (Optional)</label>
                        <select id="driver" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none 
                        focus:ring-2 focus:ring-red-500">
                            <option value="">No Driver</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Availability</label>
                        <div class="flex items-center">
                            <input type="checkbox" id="is_available" class="mr-3 w-5 h-5 rounded bg-black/30 border-white/20 focus:ring-red-500" checked>
                            <label for="is_available" class="text-gray-300">Vehicle is available for booking</label>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 gap-3">
                        <button type="button" id="closeVehicleModalBtn" class="cursor-pointer px-5 py-2.5 bg-gray-700 hover:bg-gray-600 rounded-lg text-white transition-all">Cancel</button>
                        <button type="submit" id="saveVehicleBtn" class="cursor-pointer px-5 py-2.5 bg-red-700 hover:bg-red-600 rounded-lg text-white transition-all">Save Vehicle</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- DELETE VEHICLE MODAL --}}
        <div id="deleteVehicleModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" id="deleteVehicleBackdrop"></div>
            <div class="modal-content relative w-96 p-6 rounded-2xl shadow-2xl bg-[#262B32] transform scale-90 opacity-0 transition-all duration-300">
                <h2 class="text-2xl font-bold text-red-500 mb-4">Confirm Delete</h2>
                <p class="mb-4 text-gray-300">Enter your password to confirm deletion of <span id="deleteVehicleName" class="font-semibold text-white"></span>.</p>
                
                {{-- DELETE ERROR MESSAGE --}}
                <div id="deleteVehicleError" class="hidden mb-4 p-3 bg-red-600/20 border border-red-500 rounded-lg text-red-300 text-sm" style="min-height: 2.5rem; display: none;">
                    <span id="deleteVehicleErrorText"></span>
                </div>
                
                <form id="deleteVehicleForm">
                    <input type="password" id="deleteConfirmPassword" required placeholder="Enter your password" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500 mb-4">
                    <div class="flex justify-end gap-3">
                        <button type="button" id="cancelDeleteVehicleBtn" class="cursor-pointer px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-lg text-white">Cancel</button>
                        <button type="submit" class="cursor-pointer px-4 py-2 bg-red-700 hover:bg-red-500 rounded-lg text-white">Delete</button>
                    </div>
                </form>
            </div>
        </div>

    </main>
</div>

<script>
const csrfToken = '{{ csrf_token() }}';
let vehicles = [];

// Global vehicles data from PHP
const vehiclesData = @json($vehicles ?? []);

// Image preview function
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('vehicleImagePreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Render vehicles table with image support
function renderVehiclesTable() {
    const tbody = document.getElementById('vehiclesTable');
    tbody.innerHTML = '';

    vehicles.forEach(vehicle => {
        // Handle driver name safely
        let driverName = 'No Driver';
        if (vehicle.driver_info && vehicle.driver_info.name) {
            driverName = vehicle.driver_info.name;
        } else if (vehicle.driver && typeof vehicle.driver === 'object' && vehicle.driver.name) {
            driverName = vehicle.driver.name;
        }
        
        // Determine availability class
        const isAvailable = vehicle.is_available !== false;
        const statusClass = isAvailable ? 'status-available' : 'status-unavailable';
        const statusText = isAvailable ? 'Available' : 'Unavailable';
        
        const tr = document.createElement('tr');
        tr.className = 'border-b border-white/10 hover:bg-white/10 transition-all';
        tr.innerHTML = `
            <td class="p-4">
                <img src="${vehicle.image_url || "{{ asset('assets/default-vehicle.jpg') }}"}"
                     alt="${vehicle.brand} ${vehicle.model}"
                     class="vehicle-image hover:scale-105 transition-transform duration-200 cursor-pointer"
                     onclick="this.classList.toggle('scale-150'); setTimeout(() => this.classList.remove('scale-150'), 2000)">
            </td>
            <td class="p-4 font-semibold plate-cell">${vehicle.plate_num}</td>
            <td class="p-4 type-cell">
                <span class="type-pill">${vehicle.body_type}</span>
            </td>
            <td class="p-4 brand-cell">
                <div class="font-bold">${vehicle.brand}</div>
                <div class="text-sm text-gray-300">${vehicle.model} (${vehicle.year})</div>
            </td>
            <td class="p-4 driver-cell">${driverName}</td>
            <td class="p-4 color-cell">${vehicle.color}</td>
            <td class="p-4">${vehicle.transmission}</td>
            <td class="p-4">${vehicle.seat_cap} seats</td>
            <td class="p-4 font-bold text-green-400">₱${Number(vehicle.price_rate).toLocaleString()}</td>
            <td class="p-4">
                <span class="status-pill ${statusClass}">
                    ${statusText}
                </span>
            </td>
            <td class="p-4 text-center">
                <div class="flex justify-center gap-3">
                    <button type="button" class="cursor-pointer px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-lg text-white shadow edit-vehicle-btn"
                        data-id="${vehicle.vehicle_id}">
                        <img src="{{ asset('assets/edit.png') }}" class="w-4 h-4 inline mr-2">
                        Edit
                    </button>

                    <button type="button" class="cursor-pointer px-4 py-2 bg-[#742121] hover:bg-red-500 rounded-lg text-white shadow delete-vehicle-btn"
                        data-id="${vehicle.vehicle_id}"
                        data-name="${vehicle.plate_num}">
                        <img src="{{ asset('assets/delete.png') }}" class="w-4 h-4 inline mr-2">
                        Delete
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

/* -------------------------------
   VEHICLE MODAL LOGIC
--------------------------------*/
class VehicleModal {
    constructor() {
        this.modal = document.getElementById('vehicleModal');
        this.modalCard = document.getElementById('vehicleModalCard');
        this.backdrop = document.getElementById('vehicleBackdrop');
        this.form = document.getElementById('vehicleForm');

        this.initializeEvents();
    }

    initializeEvents() {
        document.getElementById('addVehicleBtn').addEventListener('click', () => this.openModal());
        document.getElementById('closeVehicleModalBtn').addEventListener('click', () => this.closeModal());
        this.backdrop.addEventListener('click', () => this.closeModal());

        document.addEventListener('click', async (e) => {
            const editBtn = e.target.closest('.edit-vehicle-btn');
            if (!editBtn) return;

            const id = editBtn.dataset.id;
            const vehicle = vehicles.find(v => v.vehicle_id == id);
            if (!vehicle) return;

            this.openEditModal(vehicle);
        });

        this.form.addEventListener('submit', (e) => this.handleSave(e));
    }

    openModal() {
        this.resetForm();
        document.getElementById('vehicleModalTitle').textContent = 'Add Vehicle';
        this.showModal();
    }

    openEditModal(vehicle) {
        this.resetForm();
        document.getElementById('vehicleModalTitle').textContent = 'Edit Vehicle';

        // Set form values
        document.getElementById('vehicle_id').value = vehicle.vehicle_id;
        document.getElementById('plate_no').value = vehicle.plate_num;
        document.getElementById('brand').value = vehicle.brand;
        document.getElementById('model').value = vehicle.model;
        document.getElementById('year').value = vehicle.year;
        document.getElementById('body_type').value = vehicle.body_type;
        document.getElementById('seat_cap').value = vehicle.seat_cap;
        document.getElementById('transmission').value = vehicle.transmission;
        document.getElementById('fuel_type').value = vehicle.fuel_type;
        document.getElementById('color').value = vehicle.color;
        document.getElementById('price_rate').value = vehicle.price_rate;
        document.getElementById('driver').value = vehicle.driver?.id || vehicle.driver || "";
        document.getElementById('is_available').checked = vehicle.is_available !== false;
        
        // Set image preview
        const preview = document.getElementById('vehicleImagePreview');
        preview.src = vehicle.image_url || "{{ asset('assets/default-vehicle.jpg') }}";
        
        this.showModal();
    }

    showModal() {
        this.modal.classList.remove('hidden');
        setTimeout(() => {
            this.modalCard.classList.remove('scale-90', 'opacity-0');
            this.modalCard.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    closeModal() {
        this.modalCard.classList.remove('scale-100', 'opacity-100');
        this.modalCard.classList.add('scale-90', 'opacity-0');
        setTimeout(() => this.modal.classList.add('hidden'), 300);
    }

    resetForm() {
        this.form.reset();
        document.getElementById('vehicle_id').value = '';
        document.getElementById('vehicleImagePreview').src = "{{ asset('assets/default-vehicle.jpg') }}";
        document.getElementById('is_available').checked = true;
    }

    async handleSave(e) {
        e.preventDefault();

        const id = document.getElementById('vehicle_id').value;
        const submitBtn = this.form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        const formErrorDiv = document.getElementById('vehicleFormError');
        formErrorDiv.classList.add('hidden');
        formErrorDiv.textContent = '';

        // Client-side required validation to avoid "required" server errors
        const requiredChecks = [
            { id: 'plate_no', label: 'Plate No.' },
            { id: 'brand', label: 'Brand' },
            { id: 'model', label: 'Model' },
            { id: 'year', label: 'Year' },
            { id: 'body_type', label: 'Body Type' },
            { id: 'seat_cap', label: 'Seat Capacity' },
            { id: 'color', label: 'Color' },
            { id: 'price_rate', label: 'Rate' },
        ];

        const missing = [];
        requiredChecks.forEach(ch => {
            const el = document.getElementById(ch.id);
            if (!el) return;
            const val = (el.type === 'checkbox') ? (el.checked ? '1' : '') : (el.value || '').toString().trim();
            if (!val) missing.push(ch.label);
        });

        if (missing.length) {
            formErrorDiv.textContent = 'Please fill required fields: ' + missing.join(', ');
            formErrorDiv.classList.remove('hidden');
            formErrorDiv.style.display = 'block';
            return;
        }

        const formData = new FormData();
        
        // Add all form data
        formData.append('plate_num', document.getElementById('plate_no').value.trim());
        formData.append('brand', document.getElementById('brand').value.trim());
        formData.append('model', document.getElementById('model').value.trim());
        formData.append('year', document.getElementById('year').value);
        formData.append('body_type', document.getElementById('body_type').value.trim());
        formData.append('seat_cap', document.getElementById('seat_cap').value);
        formData.append('transmission', document.getElementById('transmission').value);
        formData.append('fuel_type', document.getElementById('fuel_type').value);
        formData.append('color', document.getElementById('color').value.trim());
        formData.append('price_rate', document.getElementById('price_rate').value);
        formData.append('driver', document.getElementById('driver').value);
        formData.append('is_available', document.getElementById('is_available').checked ? '1' : '0');
        
        // Append image if selected
        const imageInput = document.getElementById('image');
        if (imageInput.files[0]) {
            formData.append('image', imageInput.files[0]);
        }

        try {
            submitBtn.textContent = 'Saving...';
            submitBtn.disabled = true;

            let url = '/admin/vehicles';
            let method = 'POST';

            // Use POST with _method=PUT when editing so Laravel correctly parses FormData (including files)
            if (id) {
                url = `/admin/vehicles/${id}`;
                method = 'POST';
                formData.append('_method', 'PUT');
            }

            const res = await fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    // Don't set Content-Type for FormData, let browser set it
                },
                body: formData
            });

            const result = await res.json();

            if (result.success) {
                this.showSuccessMessage(result.message || 'Vehicle saved successfully');
                await loadVehicles();
                this.closeModal();
            } else {
                // show validation errors inline if available
                if (res.status === 422 && result.errors) {
                    const msgs = [];
                    for (const [field, messages] of Object.entries(result.errors)) msgs.push(`${field}: ${messages.join(', ')}`);
                    formErrorDiv.textContent = msgs.join('\n');
                    formErrorDiv.classList.remove('hidden');
                    formErrorDiv.style.display = 'block';
                } else {
                    formErrorDiv.textContent = result.message || 'Error saving vehicle';
                    formErrorDiv.classList.remove('hidden');
                    formErrorDiv.style.display = 'block';
                }
            }
        } catch (error) {
            console.error('Save error:', error);
            alert('Error: ' + error.message);
        } finally {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    }

    showSuccessMessage(message) {
        const main = document.querySelector('main') || document.body;
        const existing = document.getElementById('successMessageDynamic');
        if (existing) existing.remove();

        const div = document.createElement('div');
        div.id = 'successMessageDynamic';
        div.className = 'mb-6 p-4 bg-green-600/20 border border-green-500 rounded-xl text-green-300 backdrop-blur-sm transition-all duration-300';
        div.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>${message}</span>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-green-300 hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>`;

        const firstChild = main.firstElementChild;
        if (firstChild) main.insertBefore(div, firstChild);
        else main.appendChild(div);
        
        setTimeout(() => div.remove(), 5000);
    }
}

/* -------------------------------
   DELETE MODAL LOGIC
--------------------------------*/
class DeleteVehicleModal {
    constructor() {
        this.modal = document.getElementById('deleteVehicleModal');
        this.modalCard = this.modal.querySelector('.modal-content');
        this.backdrop = document.getElementById('deleteVehicleBackdrop');
        this.nameSpan = document.getElementById('deleteVehicleName');
        this.form = document.getElementById('deleteVehicleForm');
        this.errorDiv = document.getElementById('deleteVehicleError');
        this.errorText = document.getElementById('deleteVehicleErrorText');
        this.currentId = null;

        this.initializeEvents();
    }

    initializeEvents() {
        document.addEventListener('click', (e) => {
            const delBtn = e.target.closest('.delete-vehicle-btn');
            if (!delBtn) return;

            this.openModal(delBtn.dataset.id, delBtn.dataset.name);
        });

        document.getElementById('cancelDeleteVehicleBtn').addEventListener('click', () => this.closeModal());
        this.backdrop.addEventListener('click', () => this.closeModal());

        this.form.addEventListener('submit', (e) => this.handleDelete(e));
    }

    openModal(id, name) {
        this.currentId = id;
        this.nameSpan.textContent = name;
        this.hideError();

        this.modal.classList.remove('hidden');
        setTimeout(() => {
            this.modalCard.classList.remove('scale-90', 'opacity-0');
            this.modalCard.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    closeModal() {
        this.modalCard.classList.remove('scale-100', 'opacity-100');
        this.modalCard.classList.add('scale-90', 'opacity-0');

        setTimeout(() => {
            this.modal.classList.add('hidden');
            this.currentId = null;
            this.form.reset();
            this.hideError();
        }, 300);
    }

    hideError() {
        this.errorDiv.style.display = 'none';
        this.errorDiv.classList.add('hidden');
    }

    showError(message) {
        this.errorText.textContent = message;
        this.errorDiv.style.display = 'flex';
        this.errorDiv.classList.remove('hidden');
    }

    async handleDelete(e) {
        e.preventDefault();

        const password = document.getElementById('deleteConfirmPassword').value;
        
        if (!password) {
            this.showError('Password is required');
            return;
        }

        const submitBtn = this.form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;

        try {
            submitBtn.textContent = 'Deleting...';
            submitBtn.disabled = true;

            const res = await fetch(`/admin/vehicles/${this.currentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    admin_password: password
                })
            });

            const result = await res.json();

            if (res.ok) {
                this.showSuccessMessage(result.message || 'Vehicle deleted successfully');
                await loadVehicles();
                this.closeModal();
            } else {
                if (res.status === 422) {
                    this.showError(result.message || result.errors?.admin_password?.[0] || 'Validation error');
                } else if (res.status === 404) {
                    this.showError('Vehicle not found');
                    await loadVehicles();
                } else if (res.status === 401) {
                    this.showError('Authentication required. Please login again.');
                } else {
                    this.showError(result.message || 'An error occurred while deleting');
                }
            }
        } catch (error) {
            console.error('Delete error:', error);
            this.showError('Network error: ' + error.message);
        } finally {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    }

    showSuccessMessage(message) {
        const main = document.querySelector('main') || document.body;
        const existing = document.getElementById('successMessageDynamic');
        if (existing) existing.remove();

        const div = document.createElement('div');
        div.id = 'successMessageDynamic';
        div.className = 'mb-6 p-4 bg-green-600/20 border border-green-500 rounded-xl text-green-300 backdrop-blur-sm transition-all duration-300';
        div.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>${message}</span>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-green-300 hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>`;

        const firstChild = main.firstElementChild;
        if (firstChild) main.insertBefore(div, firstChild);
        else main.appendChild(div);
        
        setTimeout(() => div.remove(), 5000);
    }
}

/* -------------------------------
   LOAD VEHICLES FROM DB
--------------------------------*/
async function loadVehicles() {
    try {
        console.log('Loading vehicles...');
        const response = await fetch('/admin/vehicles/data');
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Loaded vehicles:', data.length);
        
        vehicles = data;
        renderVehiclesTable();
    } catch (error) {
        console.error('Error loading vehicles:', error);
        vehicles = vehiclesData || [];
        renderVehiclesTable();
        
        const main = document.querySelector('main');
        const errorDiv = document.createElement('div');
        errorDiv.className = 'mb-6 p-4 bg-red-600/20 border border-red-500 rounded-xl text-red-300';
        errorDiv.textContent = 'Error loading vehicles. Please refresh the page.';
        main.insertBefore(errorDiv, main.firstChild);
        
        setTimeout(() => errorDiv.remove(), 5000);
    }
}

/* -------------------------------
   INIT PAGE
--------------------------------*/
document.addEventListener('DOMContentLoaded', async () => {
    new VehicleModal();
    new DeleteVehicleModal();

    // Load initial data
    await loadVehicles();

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();

        document.querySelectorAll('#vehiclesTable tr').forEach(row => {
            const plate = row.cells[1]?.textContent.toLowerCase() || '';
            const brandModel = row.cells[3]?.textContent.toLowerCase() || '';
            const color = row.cells[4]?.textContent.toLowerCase() || '';
            row.style.display = (plate.includes(term) || brandModel.includes(term) || color.includes(term)) ? '' : 'none';
        });
    });
});
</script>
@endsection