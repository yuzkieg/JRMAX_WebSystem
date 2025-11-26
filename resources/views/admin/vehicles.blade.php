@extends('layouts.app')

@section('content')
@vite('resources/css/app.css')

<div class="flex min-h-screen bg-[#1A1F24] text-white transition-colors duration-500" id="dashboard-wrapper">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-black/80 h-screen fixed top-0 left-0 shadow-xl border-r border-white/10 backdrop-blur-xl transition-all duration-300 hover:w-72">
        <div class="p-6 flex flex-col items-center">
            <img src="{{ asset('assets/logo.png') }}" class="w-20 h-20 mb-4 transition-all duration-300 hover:scale-105">
            <h2 class="text-xl font-bold tracking-wide text-red-500">ADMIN</h2>
        </div>

        @php
            $menuItems = [
                ['name' => 'Analysis', 'url' => '/admin/adminanalysis'],
                ['name' => 'HR Management', 'url' => '/admin/adminhr'],
                ['name' => 'Vehicle Management', 'url' => '/admin/vehicles'],
                ['name' => 'Vehicle Maintenance', 'url' => '/admin/vehiclemaintenance'],
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
                        <input type="text" id="plate_no" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" placeholder="ABC-123" required>
                    </div>

                    <div class="mb-3">
                        <label class="block font-semibold mb-1">Type</label>
                        <input type="text" id="vehicle_type" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" placeholder="SUV" required>
                    </div>

                    <div class="mb-3">
                        <label class="block font-semibold mb-1">Brand / Model</label>
                        <input type="text" id="brand_model" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" placeholder="Toyota Fortuner" required>
                    </div>

                    <div class="mb-3">
                        <label class="block font-semibold mb-1">Color</label>
                        <input type="text" id="color" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" placeholder="White" required>
                    </div>

                    <div class="mb-3">
                        <label class="block font-semibold mb-1">Transmission</label>
                        <select id="transmission" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                            <option value="Auto">Auto</option>
                            <option value="Manual">Manual</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="block font-semibold mb-1">Capacity</label>
                        <input type="number" id="capacity" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" placeholder="7" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label class="block font-semibold mb-1">Rate (₱)</label>
                        <input type="number" id="rate" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" placeholder="3500" min="0" required>
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
                <p class="mb-4 text-white">Enter your password to confirm deletion of <span id="deleteVehicleName" class="font-semibold"></span>.</p>
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
// Temporary vehicles data
const tempVehicles = [
    {id: 1, plate: 'ABC-123', type: 'SUV', brand_model: 'Toyota Fortuner', color: 'White', transmission: 'Auto', capacity: 7, rate: 3500},
    {id: 2, plate: 'DEF-456', type: 'Sedan', brand_model: 'Honda Civic', color: 'Black', transmission: 'Manual', capacity: 5, rate: 2500},
    {id: 3, plate: 'GHI-789', type: 'Van', brand_model: 'Nissan Urvan', color: 'Silver', transmission: 'Manual', capacity: 12, rate: 4500},
];

function renderVehiclesTable() {
    const tbody = document.getElementById('vehiclesTable');
    tbody.innerHTML = '';

    tempVehicles.forEach(vehicle => {
        const tr = document.createElement('tr');
        tr.className = 'border-b border-white/10 hover:bg-white/10 transition-all';
        tr.innerHTML = `
            <td class="p-4">${vehicle.plate}</td>
            <td class="p-4">${vehicle.type}</td>
            <td class="p-4">${vehicle.brand_model}</td>
            <td class="p-4">${vehicle.color}</td>
            <td class="p-4">${vehicle.transmission}</td>
            <td class="p-4">${vehicle.capacity}</td>
            <td class="p-4">₱${Number(vehicle.rate).toLocaleString()}</td>
            <td class="p-4 text-center flex justify-center gap-3">
                <button class="cursor-pointer px-5 py-2 bg-blue-600 hover:bg-blue-500 rounded-lg text-white shadow transition-all duration-200 hover:scale-105 edit-vehicle-btn"
                        data-id="${vehicle.id}"
                        data-plate="${vehicle.plate}"
                        data-type="${vehicle.type}"
                        data-brand_model="${vehicle.brand_model}"
                        data-color="${vehicle.color}"
                        data-transmission="${vehicle.transmission}"
                        data-capacity="${vehicle.capacity}"
                        data-rate="${vehicle.rate}">
                        <img src="{{ asset('assets/edit.png') }}" class="w-5 h-5">

                <button class="cursor-pointer px-5 py-2 bg-[#742121] hover:bg-red-500 rounded-lg text-white shadow transition-all duration-200 hover:scale-105 delete-vehicle-btn"
                        data-id="${vehicle.id}"
                        data-name="${vehicle.plate}">
                        <img src="{{ asset('assets/delete.png') }}" class="w-5 h-5">

                        </button>
            </td>`;

        tbody.appendChild(tr);
    });
}

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

        // Delegate edit clicks
        document.addEventListener('click', (e) => {
            const editBtn = e.target.closest('.edit-vehicle-btn');
            if (editBtn) {
                this.openEditModal(editBtn.dataset);
            }
        });

        // Handle save
        this.form.addEventListener('submit', (e) => this.handleSave(e));
    }

    openModal() {
        this.resetForm();
        document.getElementById('vehicleModalTitle').textContent = 'Add Vehicle';
        document.getElementById('saveVehicleBtn').textContent = 'Save';
        this.showModal();
    }

    openEditModal(data) {
        this.resetForm();
        document.getElementById('vehicleModalTitle').textContent = 'Edit Vehicle';
        document.getElementById('vehicle_id').value = data.id || '';
        document.getElementById('plate_no').value = data.plate || '';
        document.getElementById('vehicle_type').value = data.type || '';
        document.getElementById('brand_model').value = data.brand_model || '';
        document.getElementById('color').value = data.color || '';
        document.getElementById('transmission').value = data.transmission || 'Auto';
        document.getElementById('capacity').value = data.capacity || '';
        document.getElementById('rate').value = data.rate || '';
        document.getElementById('saveVehicleBtn').textContent = 'Update';
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
        document.getElementById('transmission').value = 'Auto';
    }

    handleSave(e) {
        e.preventDefault();
        const id = document.getElementById('vehicle_id').value;
        const plate = document.getElementById('plate_no').value.trim();
        const type = document.getElementById('vehicle_type').value.trim();
        const brand_model = document.getElementById('brand_model').value.trim();
        const color = document.getElementById('color').value.trim();
        const transmission = document.getElementById('transmission').value;
        const capacity = Number(document.getElementById('capacity').value);
        const rate = Number(document.getElementById('rate').value);

        if (!plate || !type || !brand_model) return;

        if (!id) {
            const newId = Date.now();
            tempVehicles.push({id: newId, plate, type, brand_model, color, transmission, capacity, rate});
        } else {
            const idx = tempVehicles.findIndex(v => String(v.id) === String(id));
            if (idx !== -1) {
                tempVehicles[idx] = {id: Number(id), plate, type, brand_model, color, transmission, capacity, rate};
            }
        }

        renderVehiclesTable();
        this.closeModal();
    }
}

class DeleteVehicleModal {
    constructor() {
        this.modal = document.getElementById('deleteVehicleModal');
        this.modalCard = this.modal.querySelector('.modal-content');
        this.backdrop = document.getElementById('deleteVehicleBackdrop');
        this.nameSpan = document.getElementById('deleteVehicleName');
        this.form = document.getElementById('deleteVehicleForm');
        this.currentId = null;

        this.initializeEvents();
    }

    initializeEvents() {
        // Delegate delete clicks
        document.addEventListener('click', (e) => {
            const delBtn = e.target.closest('.delete-vehicle-btn');
            if (delBtn) {
                this.openModal(delBtn.dataset.id, delBtn.dataset.name);
            }
        });

        document.getElementById('cancelDeleteVehicleBtn').addEventListener('click', () => this.closeModal());
        this.backdrop.addEventListener('click', () => this.closeModal());

        this.form.addEventListener('submit', (e) => this.handleDelete(e));
    }

    openModal(id, name) {
        this.currentId = id;
        this.nameSpan.textContent = name;
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
            this.form.reset();
            this.currentId = null;
        }, 300);
    }

    handleDelete(e) {
        e.preventDefault();
        const id = this.currentId;
        if (!id) return;
        const idx = tempVehicles.findIndex(v => String(v.id) === String(id));
        if (idx !== -1) tempVehicles.splice(idx, 1);
        renderVehiclesTable();
        this.closeModal();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const vehicleModal = new VehicleModal();
    const deleteModal = new DeleteVehicleModal();

    renderVehiclesTable();

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('#vehiclesTable tr').forEach(row => {
                const plate = row.cells[0]?.textContent.toLowerCase() || '';
                const brand = row.cells[2]?.textContent.toLowerCase() || '';
                row.style.display = (plate.includes(term) || brand.includes(term)) ? '' : 'none';
            });
        });
    }
});
</script>

@endsection
