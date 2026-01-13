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
    /* Actions dropdown styling */
    .actions { position: relative; display: inline-block; }
    .actions-toggle { background: transparent; border: none; color: inherit; padding: .25rem; border-radius: .5rem; }
    .actions-menu { position: absolute; right: 0; top: 100%; margin-top: 0.5rem; min-width: 10rem; background: #1f2937; border: 1px solid rgba(255,255,255,0.06); border-radius: .75rem; box-shadow: 0 6px 18px rgba(0,0,0,0.6); z-index: 40; display: none; overflow: hidden; }
    .actions-menu.show { display: block; }
    .actions-menu button { display: flex; align-items: center; gap: .5rem; width: 100%; text-align: left; padding: .5rem .75rem; background: transparent; border: none; color: #e5e7eb; }
    .actions-menu button:hover { background: rgba(255,255,255,0.03); }

    .status-pill {
    display: inline-block;
    border-radius: 0.5rem;
    font-weight: 700;
    font-size: medium;
}

    .status-pill.pending { background: transparent; color: #FFFF00 ; }
    .status-pill.confirmed { background: transparent; color: #fff; }
    .status-pill.ongoing { background: transparent; color: #ADD8E6  ; }
    .status-pill.completed { background: transparent; color: #93FF54   ; }
    .status-pill.cancelled { background: transparent; color: #FF0000 ; }

    
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
</style>

<div class="flex min-h-screen text-white transition-colors duration-500">

    @include('admin.layout.sidebar')

    {{-- MAIN CONTENT --}}
    <main class="min-h-screen transition-all duration-300 p-8" style="margin-left: 18rem; width: calc(100% - 18rem);">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-red-500 drop-shadow-lg">Vehicle Maintenance</h1>

            <div class="flex items-center space-x-4">
                {{-- Theme Toggle --}}
                <button id="theme-toggle" class="flex items-center gap-2 bg-black/30 backdrop-blur-xl p-2 rounded-lg hover:bg-[#998282] transition-all duration-300 cursor-pointer">
                    <img id="theme-icon" src="{{ asset('assets/moon.png') }}" class="w-6 h-6 transition-transform duration-500">
                    <span class="font-medium text-white">Dark Mode</span>
                </button>
            </div>
        </div>

        <div id="successMessageContainer"></div>
        {{-- STATUS FILTERS (charcoal default; red only when active) --}}
        <div class="flex flex-wrap gap-3 mb-6">
            <button class="filter-btn px-4 py-2 rounded-lg transition-all duration-200 bg-gray-700 hover:bg-gray-600 text-white" data-status="all">All</button>
            <button class="filter-btn px-4 py-2 rounded-lg transition-all duration-200 bg-gray-700 hover:bg-gray-600 text-white" data-status="scheduled">Scheduled</button>
            <button class="filter-btn px-4 py-2 rounded-lg transition-all duration-200 bg-gray-700 hover:bg-gray-600 text-white" data-status="in progress">In Progress</button>
            <button class="filter-btn px-4 py-2 rounded-lg transition-all duration-200 bg-gray-700 hover:bg-gray-600 text-white" data-status="completed">Completed</button>
            <button class="filter-btn px-4 py-2 rounded-lg transition-all duration-200 bg-gray-700 hover:bg-gray-600 text-white" data-status="cancelled">Cancelled</button>
        </div>

        {{-- SEARCH AND ADD MAINTENANCE --}}
        <div class="flex justify-between items-center mb-6">
            <input type="text" placeholder="Search by plate, type, or description..."
                   class="w-80 p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none 
                   focus:ring-2 focus:ring-red-500 transition-all duration-300"
                   id="searchInput">

            <button id="addMaintenanceBtn" class="cursor-pointer px-5 py-2 bg-red-700 hover:bg-red-500 rounded-xl text-white shadow-lg transition-all duration-300 hover:scale-105">
                + Add Maintenance
            </button>
        </div>

        {{-- MAINTENANCE TABLE --}}
        <div class=" rounded-2xl shadow-2xl backdrop-blur-xl">
            <table class="w-full text-left dark-table">
                <thead class="bg-black/30 text-white uppercase text-sm tracking-wide text-center">
                    <tr>
                        <th class="p-4">Plate No.</th>
                        <th class="p-4">Vehicle</th>
                        <th class="p-4">Type</th>
                        <th class="p-4">Scheduled Date</th>
                        <th class="p-4">Cost</th>
                        <th class="p-4">Description</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="maintenanceTable" class="text-white">
                    @foreach($maintenances as $maintenance)
                    <tr class="border-b border-white/10 hover:bg-white/10 transition-all" data-status="{{ $maintenance->status }}">
                        <td class="p-4">
                            <div class="text-center">{{ $maintenance->vehicle->plate_num ?? 'N/A' }}</div>
                        </td>
                        <td class="p-4">
                            <div class="text-center">{{ $maintenance->vehicle->brand ?? 'N/A' }}</div>
                        </td>
                        <td class="p-4 text-center">
                            <span>
                                {{ ucwords(str_replace('-', ' ', $maintenance->maintenance_type)) }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            {{ $maintenance->scheduled_date ? \Carbon\Carbon::parse($maintenance->scheduled_date)->format('M d, Y') : 'N/A' }}
                        </td>
                        <td class="p-4 text-right">
                            ₱{{ number_format($maintenance->cost, 2) }}
                        </td>
                        <td class="p-4 max-w-xs truncate text-center" title="{{ $maintenance->description }}">
                            {{ Str::limit($maintenance->description, 50) }}
                        </td>
                        <td class="p-4 text-center">
                            @php
                                // map maintenance statuses to standard status-pill classes
                                $statusClass = 'pending';
                                if ($maintenance->status === 'scheduled') $statusClass = 'pending';
                                elseif ($maintenance->status === 'in progress') $statusClass = 'ongoing';
                                elseif ($maintenance->status === 'completed') $statusClass = 'completed';
                                elseif ($maintenance->status === 'cancelled') $statusClass = 'cancelled';
                            @endphp
                            <span class="status-pill {{ $statusClass }}">{{ ucfirst($maintenance->status) }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex justify-center">
                                <div class="actions">
                                    <button class="actions-toggle" aria-haspopup="true" aria-expanded="false">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </button>

                                    <div class="actions-menu" role="menu">
                                        @if($maintenance->status !== 'completed' && $maintenance->status !== 'cancelled')
                                        <button class="status-btn" data-id="{{ $maintenance->maintenance_ID }}" data-action="next" role="menuitem">
                                            @if($maintenance->status === 'scheduled')
                                            <span>Start</span>
                                            @elseif($maintenance->status === 'in progress')
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Complete
                                            </span>
                                            @endif
                                        </button>
                                        @endif

                                        <button class="edit-btn" data-id="{{ $maintenance->maintenance_ID }}" role="menuitem">
                                            <img src="{{ asset('assets/edit.png') }}" class="w-4 h-4 inline"> <span>Edit</span>
                                        </button>

                                        <button class="delete-btn" data-id="{{ $maintenance->maintenance_ID }}" data-plate="{{ $maintenance->vehicle->plate_num ?? '' }}" role="menuitem">
                                            <img src="{{ asset('assets/delete.png') }}" class="w-4 h-4 inline"> <span>Delete</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ADD/EDIT MAINTENANCE MODAL --}}
        <div id="maintenanceModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" id="maintenanceBackdrop"></div>
            <div id="maintenanceModalCard" class="modal-content relative w-full max-w-2xl p-6 rounded-2xl shadow-2xl bg-[#262B32] transform scale-90 opacity-0 transition-all duration-300 max-h-[90vh] overflow-y-auto">
                <h2 class="text-2xl font-bold text-red-500 mb-4" id="maintenanceModalTitle">Add Maintenance</h2>
                <form id="maintenanceForm">
                    <input type="hidden" id="maintenance_id">

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block font-semibold mb-1">Vehicle *</label>
                            <select id="vehicle_ID" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" required>
                                <option value="">Select Vehicle</option>
                                @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->vehicle_id }}">
                                    {{ $vehicle->plate_num }} - {{ $vehicle->brand }} {{ $vehicle->model }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold mb-1">Maintenance Type *</label>
                            <select id="maintenance_type" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" required>
                                <option value="">Select Type</option>
                                <option value="repair">Repair</option>
                                <option value="check-up">Check-up</option>
                                <option value="oil change">Oil Change</option>
                                <option value="tire replacement">Tire Replacement</option>
                                <option value="engine service">Engine Service</option>
                                <option value="cleaning">Cleaning</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Description</label>
                        <textarea id="description" rows="3" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" placeholder="Describe the maintenance work..."></textarea>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block font-semibold mb-1">Odometer Reading</label>
                            <input type="number" id="odometer_reading" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" placeholder="km">
                        </div>

                        <div>
                            <label class="block font-semibold mb-1">Scheduled Date *</label>
                            <input type="date" id="scheduled_date" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" required>
                        </div>

                        <div>
                            <label class="block font-semibold mb-1">Cost (₱) *</label>
                            <input type="number" step="0.01" id="cost" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" placeholder="0.00" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Status *</label>
                        <select id="status" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" required>
                            <option value="scheduled">Scheduled</option>
                            <option value="in progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
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
                <p class="mb-4 text-gray-300">Enter your password to confirm deletion of maintenance record for <span id="deletePlate" class="font-semibold text-white"></span>.</p>
                
                <div id="deleteMaintenanceError" class="hidden mb-4 p-3 bg-red-600/20 border border-red-500 rounded-lg text-red-300 text-sm"></div>
                
                <form id="deleteMaintenanceForm">
                    <input type="password" id="deleteConfirmPassword" required placeholder="Enter your password" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500 mb-4">
                    <div class="flex justify-end gap-3">
                        <button type="button" id="cancelDeleteMaintenanceBtn" class="cursor-pointer px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-lg text-white">Cancel</button>
                        <button type="submit" class="cursor-pointer px-4 py-2 bg-red-700 hover:bg-red-500 rounded-lg text-white">Delete</button>
                    </div>
                </form>
            </div>
        </div>

    </main>
</div>

<script>
const csrfToken = '{{ csrf_token() }}';
let maintenances = @json($maintenances);

// Render table function (if needed for dynamic updates)
function renderMaintenanceTable(data = maintenances) {
    const tbody = document.getElementById('maintenanceTable');
    // Table is already rendered server-side, this is for future dynamic updates
}

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
            
            const res = await fetch(`/admin/maintenance/${id}/status`, {
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
// Maintenance Modal
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

        // Edit button clicks
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.edit-btn');
            if (!btn) return;
            
            const id = btn.dataset.id;
            this.openEditModal(id);
        });

        this.form.addEventListener('submit', (e) => this.handleSave(e));
    }

    openModal() {
        document.getElementById('maintenanceModalTitle').textContent = 'Add Maintenance';
        this.resetForm();
        this.showModal();
    }

    async openEditModal(id) {
        try {
            // Fetch maintenance record details
            const response = await fetch(`/admin/maintenance/${id}/edit`);
            if (!response.ok) throw new Error('Failed to fetch record');
            
            const maintenance = await response.json();
            
            document.getElementById('maintenanceModalTitle').textContent = 'Edit Maintenance';
            document.getElementById('maintenance_id').value = maintenance.maintenance_ID;
            document.getElementById('vehicle_ID').value = maintenance.vehicle_ID;
            document.getElementById('maintenance_type').value = maintenance.maintenance_type;
            document.getElementById('description').value = maintenance.description || '';
            document.getElementById('odometer_reading').value = maintenance.odometer_reading || '';
            document.getElementById('scheduled_date').value = maintenance.scheduled_date;
            document.getElementById('cost').value = maintenance.cost;
            document.getElementById('status').value = maintenance.status;
            
            this.showModal();
        } catch (error) {
            console.error('Error fetching maintenance record:', error);
            alert('Error loading maintenance record');
        }
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
        document.getElementById('scheduled_date').valueAsDate = new Date();
    }

   async handleSave(e) {
    e.preventDefault();

    const id = document.getElementById('maintenance_id').value;
    const submitBtn = this.form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;

    const payload = {
        vehicle_ID: document.getElementById('vehicle_ID').value,
        maintenance_type: document.getElementById('maintenance_type').value,
        description: document.getElementById('description').value.trim(),
        odometer_reading: document.getElementById('odometer_reading').value || null,
        scheduled_date: document.getElementById('scheduled_date').value,
        cost: document.getElementById('cost').value,
        status: document.getElementById('status').value,
    };

    try {
        submitBtn.textContent = 'Saving...';
        submitBtn.disabled = true;

        let res;
        if (!id) {
            res = await fetch('/admin/maintenance', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });
        } else {
            res = await fetch(`/admin/maintenance/${id}`, {
                method: 'PUT',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });
        }

        const result = await res.json();

        if (res.ok) {
            // Show success message instead of alert
            showSuccessMessage(result.message || 'Maintenance record saved successfully', 'success');
            
            // Close modal
            this.closeModal();
            
            // Reload after a short delay to show the success message
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            if (res.status === 422 && result.errors) {
                let errorMessages = [];
                for (const [field, messages] of Object.entries(result.errors)) {
                    errorMessages.push(`${field}: ${messages.join(', ')}`);
                }
                // Show error message
                showSuccessMessage('Validation errors: ' + errorMessages.join(', '), 'error');
            } else {
                showSuccessMessage(result.message || 'Error saving maintenance record', 'error');
            }
        }
    } catch (error) {
        showSuccessMessage('Network error: ' + error.message, 'error');
    } finally {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }
}
}
// Delete Maintenance Modal
class DeleteMaintenanceModal {
    constructor() {
        this.modal = document.getElementById('deleteMaintenanceModal');
        this.modalCard = this.modal.querySelector('.modal-content');
        this.backdrop = document.getElementById('deleteMaintenanceBackdrop');
        this.form = document.getElementById('deleteMaintenanceForm');
        this.errorDiv = document.getElementById('deleteMaintenanceError');
        this.deletePlate = document.getElementById('deletePlate');
        this.currentId = null;

        this.initializeEvents();
    }

    initializeEvents() {
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.delete-btn');
            if (!btn) return;
            
            this.openModal(btn.dataset.id, btn.dataset.plate);
        });

        document.getElementById('cancelDeleteMaintenanceBtn').addEventListener('click', () => this.closeModal());
        this.backdrop.addEventListener('click', () => this.closeModal());
        this.form.addEventListener('submit', (e) => this.handleDelete(e));
    }

    openModal(id, plate) {
        this.currentId = id;
        this.deletePlate.textContent = plate;
        this.hideError();
        this.form.reset();

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
        this.errorDiv.classList.add('hidden');
    }

    showError(message) {
        this.errorDiv.textContent = message;
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

        // Create FormData for DELETE request
        const formData = new FormData();
        formData.append('admin_password', password);
        formData.append('_method', 'DELETE'); // Important for Laravel to recognize DELETE method
        formData.append('_token', csrfToken);

        const res = await fetch(`/admin/maintenance/${this.currentId}`, {
            method: 'POST', // Use POST with _method=DELETE
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        });

        const result = await res.json();

        if (res.ok) {
            // Show success message
            showSuccessMessage(result.message || 'Maintenance record deleted successfully', 'success');
            
            // Close modal
            this.closeModal();
            
            // Reload after a short delay
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            console.log('Delete error response:', result); // Debug log
            if (res.status === 422) {
                const errorMessage = result.message || 
                                   (result.errors && result.errors.admin_password ? 
                                    result.errors.admin_password[0] : 
                                    'Incorrect password');
                this.showError(errorMessage);
                showSuccessMessage(errorMessage, 'error');
            } else {
                this.showError(result.message || 'An error occurred');
                showSuccessMessage(result.message || 'An error occurred', 'error');
            }
        }
    } catch (error) {
        console.error('Delete error:', error); // Debug log
        this.showError('Network error: ' + error.message);
        showSuccessMessage('Network error: ' + error.message, 'error');
    } finally {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }
}
}
// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new MaintenanceModal();
    new DeleteMaintenanceModal();
    
    // Set default scheduled date to today
    const scheduledDate = document.getElementById('scheduled_date');
    if (scheduledDate) {
        const today = new Date().toISOString().split('T')[0];
        scheduledDate.value = today;
        scheduledDate.min = today;
    }
});

function showSuccessMessage(message, type = 'success') {
    const container = document.getElementById('successMessageContainer') || document.body;
    const existing = document.getElementById('dynamicSuccessMessage');
    
    if (existing) {
        existing.remove();
    }
    
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

// Add this CSS for animation
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

// Actions dropdown toggle and outside-click handling
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

        // After shown, adjust dropup if it would overflow viewport
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

    // If clicking a menu item, let its handler run then close the menu shortly after
    if (e.target.closest('.actions-menu button')) {
        const menu = e.target.closest('.actions-menu');
        setTimeout(() => menu.classList.remove('show'), 100);
        return;
    }

    // Click outside: close all menus
    document.querySelectorAll('.actions-menu.show').forEach(m => m.classList.remove('show'));
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