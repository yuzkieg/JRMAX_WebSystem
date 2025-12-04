@extends('layouts.app')

@section('content')
@vite('resources/css/app.css')

<style>
/* Theme-driven UI helpers */
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
</style>

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

        {{-- SEARCH and ADD VEHICLE --}}
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
                        <th class="p-4">Plate No.</th>
                        <th class="p-4">Type</th>
                        <th class="p-4">Brand / Model</th>
                        <th class="p-4">Color</th>
                        <th class="p-4">Transmission</th>
                        <th class="p-4">Capacity</th>
                        <th class="p-4">Rate</th>
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

            <div id="vehicleModalCard" class="modal-content relative w-96 p-6 rounded-2xl shadow-2xl bg-[#262B32] transform scale-90 opacity-0 transition-all duration-300">
                <h2 class="text-2xl font-bold text-red-500 mb-4" id="vehicleModalTitle">Add Vehicle</h2>

                <form id="vehicleForm">
                    <input type="hidden" id="vehicle_id">

                    <div class="mb-3">
                        <label class="block font-semibold mb-1">Plate No.</label>
                        <input type="text" id="plate_num" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none 
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

                    <div class="flex justify-end mt-4 gap-3">
                        <button type="button" id="closeVehicleModalBtn" class="cursor-pointer px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-lg text-white">Cancel</button>
                        <button type="submit" id="saveVehicleBtn" class="cursor-pointer px-4 py-2 bg-red-700 hover:bg-red-500 rounded-lg text-white">Save</button>
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

// Render vehicles table
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
        
        const tr = document.createElement('tr');
        tr.className = 'border-b border-white/10 hover:bg-white/10 transition-all';
        tr.innerHTML = `
            <td class="p-4">${vehicle.plate_num}</td>
            <td class="p-4">${vehicle.body_type}</td>
            <td class="p-4">${vehicle.brand} ${vehicle.model}</td>
            <td class="p-4">${vehicle.color}</td>
            <td class="p-4">${vehicle.transmission}</td>
            <td class="p-4">${vehicle.seat_cap}</td>
            <td class="p-4">₱${Number(vehicle.price_rate).toLocaleString()}</td>
            <td class="p-4 text-center flex justify-center gap-3">
                <button type="button" class="cursor-pointer px-5 py-2 bg-blue-600 hover:bg-blue-500 rounded-lg text-white shadow edit-vehicle-btn"
                    data-id="${vehicle.vehicle_id}">
                    <img src="{{ asset('assets/edit.png') }}" class="w-5 h-5">
                </button>

                <button type="button" class="cursor-pointer px-5 py-2 bg-[#742121] hover:bg-red-500 rounded-lg text-white shadow delete-vehicle-btn"
                    data-id="${vehicle.vehicle_id}"
                    data-name="${vehicle.plate_num}">
                    <img src="{{ asset('assets/delete.png') }}" class="w-5 h-5">
                </button>
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

            // Find vehicle in current data
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

        document.getElementById('vehicle_id').value = vehicle.vehicle_id;
        document.getElementById('plate_num').value = vehicle.plate_num;
        document.getElementById('brand').value = vehicle.brand;
        document.getElementById('model').value = vehicle.model;
        document.getElementById('year').value = vehicle.year;
        document.getElementById('body_type').value = vehicle.body_type;
        document.getElementById('seat_cap').value = vehicle.seat_cap;
        document.getElementById('transmission').value = vehicle.transmission;
        document.getElementById('fuel_type').value = vehicle.fuel_type;
        document.getElementById('color').value = vehicle.color;
        document.getElementById('price_rate').value = vehicle.price_rate;

        // Driver select (null or number)
        document.getElementById('driver').value = vehicle.driver ?? "";

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
        document.getElementById('transmission').value = 'Automatic';
        document.getElementById('fuel_type').value = 'Gasoline';
    }

    async handleSave(e) {
        e.preventDefault();

        const id = document.getElementById('vehicle_id').value;
        const submitBtn = this.form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;

        const payload = {
            plate_num: document.getElementById('plate_num').value.trim(),
            brand: document.getElementById('brand').value.trim(),
            model: document.getElementById('model').value.trim(),
            year: Number(document.getElementById('year').value),
            body_type: document.getElementById('body_type').value.trim(),
            seat_cap: Number(document.getElementById('seat_cap').value),
            transmission: document.getElementById('transmission').value,
            fuel_type: document.getElementById('fuel_type').value,
            color: document.getElementById('color').value.trim(),
            price_rate: Number(document.getElementById('price_rate').value),
            driver: document.getElementById('driver').value ? 
                    Number(document.getElementById('driver').value) : 
                    null,
        };

        try {
            submitBtn.textContent = 'Saving...';
            submitBtn.disabled = true;

            let res;
            let url;

            if (!id) {
                // Create new vehicle
                url = '/employee/fleet/vehicles';
                res = await fetch(url, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': csrfToken 
                    },
                    body: JSON.stringify(payload)
                });
            } else {
                // Update existing vehicle
                url = `/employee/fleet/vehicles/${id}`;
                res = await fetch(url, {
                    method: 'PUT',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': csrfToken 
                    },
                    body: JSON.stringify(payload)
                });
            }

            const result = await res.json();

            if (res.ok) {
                this.showSuccessMessage(result.message || 'Vehicle saved successfully');
                
                // Reload vehicles data
                await loadVehicles();
                this.closeModal();
            } else {
                // Handle validation errors
                if (res.status === 422 && result.errors) {
                    let errorMessages = [];
                    for (const [field, messages] of Object.entries(result.errors)) {
                        errorMessages.push(`${field}: ${messages.join(', ')}`);
                    }
                    alert('Validation errors:\n' + errorMessages.join('\n'));
                } else {
                    alert(result.message || 'Error saving vehicle');
                }
            }
        } catch (error) {
            alert('Error: ' + error.message);
        } finally {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    }

    showSuccessMessage(message) {
        showSuccessMessage(message, 'success');
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

            const res = await fetch(`/employee/fleet/vehicles/${this.currentId}`, {
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
                // Refresh the vehicles list
                await loadVehicles();
                this.closeModal();
            } else {
                // Handle different error statuses
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
        showSuccessMessage(message, 'success');
    }
}

/* -------------------------------
   LOAD VEHICLES FROM DB
--------------------------------*/
async function loadVehicles() {
    try {
        const response = await fetch('/employee/fleet/vehicles/data');
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        vehicles = data;
        renderVehiclesTable();
    } catch (error) {
        console.error('Error loading vehicles:', error);
        // Fallback to initial data
        vehicles = vehiclesData || [];
        renderVehiclesTable();
        
        // Show error message to user
        showSuccessMessage('Error loading vehicles. Please refresh the page.', 'error');
    }
}

/* -------------------------------
   SUCCESS MESSAGE FUNCTION
--------------------------------*/
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
            const plate = row.cells[0]?.textContent.toLowerCase() || '';
            const brand = row.cells[2]?.textContent.toLowerCase() || '';
            row.style.display = (plate.includes(term) || brand.includes(term)) ? '' : 'none';
        });
    });

    // Check for session messages
    @if(session('success'))
        showSuccessMessage('{{ session('success') }}', 'success');
    @endif

    @if(session('error'))
        showSuccessMessage('{{ session('error') }}', 'error');
    @endif
});
</script>
@endsection