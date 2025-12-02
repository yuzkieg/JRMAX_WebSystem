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

        {{-- SEARCH AND ADD MAINTENANCE --}}
        <div class="flex justify-between items-center mb-6">
            <input type="text" placeholder="Search maintenance records..."
                   class="w-80 p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none 
                   focus:ring-2 focus:ring-red-500 transition-all duration-300"
                   id="searchInput">

            <button id="addMaintenanceBtn" class="px-5 py-2 bg-red-700 hover:bg-red-500 rounded-xl text-white shadow-lg transition-all duration-300 hover:scale-105">
                + Add Maintenance
            </button>
        </div>

        {{-- MAINTENANCE TABLE --}}
        <div class="overflow-hidden rounded-2xl shadow-2xl backdrop-blur-xl card-text dark-card">
            <table class="w-full text-left">
                <thead class="bg-black/30 text-white uppercase text-sm tracking-wide">
                    <tr>
                        <th class="p-4">Plate No.</th>
                        <th class="p-4">Category</th>
                        <th class="p-4">Date Start</th>
                        <th class="p-4">Date End</th>
                        <th class="p-4">Cost</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="maintenanceTable" class="text-white"></tbody>
            </table>
        </div>

        {{-- ADD/EDIT MAINTENANCE MODAL --}}
        <div id="maintenanceModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" id="maintenanceBackdrop"></div>
            <div id="maintenanceModalCard" class="modal-content relative w-96 p-6 rounded-2xl shadow-2xl bg-[#262B32] transform scale-90 opacity-0 transition-all duration-300">
                <h2 class="text-2xl font-bold text-red-500 mb-4" id="maintenanceModalTitle">Add Maintenance</h2>
                <form id="maintenanceForm">
                    <input type="hidden" id="maintenance_id">

                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Plate No.</label>
                        <input type="text" id="plate_no" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" placeholder="Enter plate number">
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Category</label>
                        <select id="category" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                            <option value="" disabled selected>Select category</option>
                            <option value="engine">Engine</option>
                            <option value="tires">Tires</option>
                            <option value="brakes">Brakes</option>
                            <option value="oil_change">Oil Change</option>
                        </select>
                    </div>

                    <div class="mb-4 grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold mb-1">Date Start</label>
                            <input type="date" id="date_start" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                        <div>
                            <label class="block font-semibold mb-1">Date End</label>
                            <input type="date" id="date_end" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Cost</label>
                        <input type="number" id="cost" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" placeholder="Enter cost">
                    </div>

                    <div class="flex justify-end mt-6 gap-3">
                        <button type="button" id="closeMaintenanceBtn" class="cursor-pointer px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-lg text-white transition-all duration-200">Cancel</button>
                        <button type="submit" id="saveMaintenanceBtn" class="cursor-pointer px-4 py-2 bg-red-700 hover:bg-red-500 rounded-lg text-white transition-all duration-200">Save</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- DELETE CONFIRMATION MODAL --}}
        <div id="deleteMaintenanceModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" id="deleteMaintenanceBackdrop"></div>
            <div class="modal-content relative w-96 p-6 rounded-2xl shadow-2xl bg-[#262B32] transform scale-90 opacity-0 transition-all duration-300">
                <h2 class="text-2xl font-bold text-red-500 mb-4">Confirm Delete</h2>
                <p class="mb-4 text-white">Are you sure you want to delete maintenance record for <span id="deletePlate" class="font-semibold"></span>?</p>
                <form id="deleteMaintenanceForm">
                    <div class="flex justify-end gap-3">
                        <button type="button" id="cancelDeleteMaintenanceBtn" class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-lg text-white transition-all duration-200">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-red-700 hover:bg-red-500 rounded-lg text-white transition-all duration-200">Delete</button>
                    </div>
                </form>
            </div>
        </div>

    </main>
</div>

<script>
// Temporary maintenance data (persisted in localStorage for now)
let tempMaintenance = JSON.parse(localStorage.getItem('tempMaintenance') || 'null') || [
    {id: 1, plate_no: 'ABC-123', category: 'engine', date_start: '2025-11-01', date_end: '2025-11-03', cost: 250.00},
    {id: 2, plate_no: 'XYZ-789', category: 'tires', date_start: '2025-10-20', date_end: '2025-10-21', cost: 120.00},
];

function saveMaintenanceToStorage() {
    try {
        localStorage.setItem('tempMaintenance', JSON.stringify(tempMaintenance));
    } catch (e) {
        // ignore storage errors
    }
}

function formatDate(d) {
    if (!d) return '';
    return d;
}

function renderMaintenanceTable() {
    const tbody = document.getElementById('maintenanceTable');
    tbody.innerHTML = '';
    tempMaintenance.forEach(item => {
        const tr = document.createElement('tr');
        tr.className = 'border-b border-white/10 hover:bg-white/10 transition-all';
        tr.innerHTML = `
            <td class="p-4">${item.plate_no}</td>
            <td class="p-4">${item.category}</td>
            <td class="p-4">${formatDate(item.date_start)}</td>
            <td class="p-4">${formatDate(item.date_end)}</td>
            <td class="p-4">${item.cost.toFixed(2)}</td>
            <td class="p-4 text-center flex justify-center gap-3">
                <button class="cursor-pointer px-4 py-1 bg-blue-600 hover:bg-blue-500 rounded-lg text-white shadow transition-all duration-200 hover:scale-105 edit-maint-btn"
                        data-id="${item.id}" data-plate="${item.plate_no}" data-category="${item.category}" data-start="${item.date_start}" data-end="${item.date_end}" data-cost="${item.cost}">                        
                        <img src="{{ asset('assets/edit.png') }}" class="w-5 h-5">
</button>
                <button class="cursor-pointer px-4 py-1 bg-[#742121] hover:bg-red-500 rounded-lg text-white shadow transition-all duration-200 hover:scale-105 delete-maint-btn"
                        data-id="${item.id}" data-plate="${item.plate_no}">                        
                        <img src="{{ asset('assets/delete.png') }}" class="w-5 h-5">
                </button>
            </td>`;
        tbody.appendChild(tr);
    });
}

class MaintenanceModal {
    constructor() {
        this.modal = document.getElementById('maintenanceModal');
        this.modalCard = document.getElementById('maintenanceModalCard');
        this.backdrop = document.getElementById('maintenanceBackdrop');
        this.form = document.getElementById('maintenanceForm');
        this.initializeEvents();
    }

    initializeEvents() {
        document.getElementById('addMaintenanceBtn').addEventListener('click', () => this.openModal());
        document.getElementById('closeMaintenanceBtn').addEventListener('click', () => this.closeModal());
        this.backdrop.addEventListener('click', () => this.closeModal());

        // delegate edit clicks
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.edit-maint-btn');
            if (btn) {
                this.openEditModal(btn.dataset);
            }
        });

        this.form.addEventListener('submit', (e) => this.handleSave(e));
    }

    openModal() {
        document.getElementById('maintenanceModalTitle').textContent = 'Add Maintenance';
        this.resetForm();
        document.getElementById('saveMaintenanceBtn').textContent = 'Save';
        this.showModal();
    }

    openEditModal(data) {
        document.getElementById('maintenanceModalTitle').textContent = 'Edit Maintenance';
        document.getElementById('maintenance_id').value = data.id;
        document.getElementById('plate_no').value = data.plate;
        document.getElementById('category').value = data.category;
        document.getElementById('date_start').value = data.start;
        document.getElementById('date_end').value = data.end;
        document.getElementById('cost').value = data.cost;
        document.getElementById('saveMaintenanceBtn').textContent = 'Update';
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
        document.getElementById('maintenance_id').value = '';
    }

    handleSave(e) {
        e.preventDefault();
        const id = document.getElementById('maintenance_id').value;
        const plate = document.getElementById('plate_no').value.trim();
        const category = document.getElementById('category').value;
        const start = document.getElementById('date_start').value;
        const end = document.getElementById('date_end').value;
        const cost = parseFloat(document.getElementById('cost').value) || 0;

        if (!plate || !category) return;

        if (!id) {
            const newId = Date.now();
            tempMaintenance.push({id: newId, plate_no: plate, category, date_start: start, date_end: end, cost});
        } else {
            const idx = tempMaintenance.findIndex(i => String(i.id) === String(id));
            if (idx !== -1) {
                tempMaintenance[idx].plate_no = plate;
                tempMaintenance[idx].category = category;
                tempMaintenance[idx].date_start = start;
                tempMaintenance[idx].date_end = end;
                tempMaintenance[idx].cost = cost;
            }
        }

        renderMaintenanceTable();
        this.closeModal();
    }
}

class DeleteMaintenanceModal {
    constructor() {
        this.modal = document.getElementById('deleteMaintenanceModal');
        this.modalCard = this.modal.querySelector('.modal-content');
        this.backdrop = document.getElementById('deleteMaintenanceBackdrop');
        this.form = document.getElementById('deleteMaintenanceForm');
        this.deletePlate = document.getElementById('deletePlate');
        this.currentId = null;
        this.initializeEvents();
    }

    initializeEvents() {
        // delegate delete clicks
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.delete-maint-btn');
            if (btn) this.openModal(btn.dataset.id, btn.dataset.plate);
        });

        document.getElementById('cancelDeleteMaintenanceBtn').addEventListener('click', () => this.closeModal());
        this.backdrop.addEventListener('click', () => this.closeModal());
        this.form.addEventListener('submit', (e) => this.handleDelete(e));
    }

    openModal(id, plate) {
        this.currentId = id;
        this.deletePlate.textContent = plate;
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

    handleDelete(e) {
        e.preventDefault();
        const id = this.currentId;
        const idx = tempMaintenance.findIndex(i => String(i.id) === String(id));
        if (idx !== -1) tempMaintenance.splice(idx,1);
        renderMaintenanceTable();
        this.closeModal();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    renderMaintenanceTable();
    new MaintenanceModal();
    new DeleteMaintenanceModal();

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('#maintenanceTable tr').forEach(row => {
                const plate = row.cells[0]?.textContent.toLowerCase() || '';
                const category = row.cells[1]?.textContent.toLowerCase() || '';
                row.style.display = (plate.includes(term) || category.includes(term)) ? '' : 'none';
            });
        });
    }
});
</script>

@endsection
