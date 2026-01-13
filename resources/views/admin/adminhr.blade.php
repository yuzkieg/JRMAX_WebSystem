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

#navEmployees.bg-red-700,
#navDrivers.bg-red-700 {
    color: #ffffff !important;
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

/* Light mode - inactive buttons get dark text */
.dark #navEmployees:not(.bg-red-700),
.dark #navDrivers:not(.bg-red-700) {
    color: #1e293b !important;
}

    .actions-menu.dropup {
        bottom: 100%;
        top: auto;
        margin-bottom: 0.5rem;
        margin-top: 0;
    }
</style>

<div class="flex min-h-screen text-white transition-colors duration-500">

    @include('admin.layout.sidebar')
    {{-- MAIN CONTENT --}}
    <main class="min-h-screen transition-all duration-300 p-8" style="margin-left: 18rem; width: calc(100% - 18rem);">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <h1 id="pageTitle" class="text-3xl font-bold text-red-500 drop-shadow-lg">HR Management</h1>
            <div class="flex items-center space-x-4">
                {{-- Theme Toggle --}}
                <button id="theme-toggle" class="flex items-center gap-2 bg-black/30 backdrop-blur-xl p-2 rounded-lg hover:bg-[#998282] transition-all duration-300 cursor-pointer">
                    <img id="theme-icon" src="{{ asset('assets/moon.png') }}" class="w-6 h-6 transition-transform duration-500">
                    <span class="font-medium text-white">Dark Mode</span>
                </button>
            </div>
        </div>

        {{-- SUCCESS/ERROR MESSAGES --}}
        @if(session('success'))
            <div id="successMessage" class="mb-6 p-4 bg-green-600/20 border border-green-500 rounded-xl text-green-300 backdrop-blur-sm transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-green-300 hover:text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div id="errorMessage" class="mb-6 p-4 bg-red-600/20 border border-red-500 rounded-xl text-red-300 backdrop-blur-sm transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span>Please fix the following errors:</span>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-red-300 hover:text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <ul class="mt-2 ml-6 list-disc text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- TOP NAVIGATION: Employees / Drivers --}}
        <div class="flex gap-4 mb-6" role="tablist" aria-label="HR navigation">
            <button id="navEmployees" data-type="employee" class="cursor-pointer px-6 py-2 bg-red-700 hover:bg-red-500 rounded-xl shadow-lg transition-all duration-300 hover:scale-105">Employees</button>
            <button id="navDrivers" data-type="driver" class="cursor-pointer px-6 py-2 bg-black hover:bg-red-500 rounded-xl shadow-lg transition-all duration-300 hover:scale-105">Drivers</button>
        </div>

        {{-- SEARCH AND ADD --}}
        <div class="flex justify-between items-center mb-6">
            <input type="text" placeholder="Search..." id="searchInput"
                   class="w-80 p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none 
                   focus:ring-2 focus:ring-red-500 transition-all duration-300">

            <button id="addHrBtn" class="cursor-pointer px-5 py-2 bg-red-700 hover:bg-red-500 rounded-xl text-white shadow-lg transition-all duration-300 hover:scale-105">
                + Add Employee
            </button>
        </div>

        {{-- TABLE --}}
        <div class="rounded-2xl shadow-2xl backdrop-blur-xl">
            <table class="w-full text-left dark-table">
                <thead class="bg-black/30 text-white uppercase text-sm tracking-wide text-center">
                    <tr>
                        <th class="p-4">ID</th>
                        <th class="p-4">Name</th>
                        <th class="p-4">Email</th>
                        <th class="p-4" id="thirdColumnHeader">Position / License</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="hrTable" class="text-white text-center">
                    @foreach($employeesrecord as $employee)
                        <tr class="border-b border-white/10 hover:bg-white/10 transition-all">
                            <td class="p-4">{{ $employee->id }}</td>
                            <td class="p-4">{{ $employee->name }}</td>
                            <td class="p-4">{{ $employee->email }}</td>
                            <td class="p-4">{{ $employee->position }}</td>
                            <td class="p-4 text-center">
                                <div class="relative inline-block">
                                    <button type="button" class="actions-toggle p-2 rounded-full hover:bg-white/10 focus:outline-none" aria-expanded="false">
                                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="5" cy="12" r="1.5" />
                                            <circle cx="12" cy="12" r="1.5" />
                                            <circle cx="19" cy="12" r="1.5" />
                                        </svg>
                                    </button>

                                    <div class="actions-menu hidden absolute right-0 mt-2 w-40 bg-[#262B32] rounded-lg shadow-xl z-50 border border-white/10" style="transform: translateZ(0); pointer-events: auto;">
                                        <button class="edit-btn flex items-center gap-3 w-full px-3 py-2 text-white hover:bg-white/5"
                                                data-type="employee"
                                                data-id="{{ $employee->id }}"
                                                data-name="{{ $employee->name }}"
                                                data-email="{{ $employee->email }}"
                                                data-role="{{ $employee->position }}">
                                            <img src="{{ asset('assets/edit.png') }}" alt="Edit" class="w-5 h-5">
                                            <span>Edit</span>
                                        </button>

                                        <button class="delete-btn flex items-center gap-3 w-full px-3 py-2 text-white hover:bg-white/5"
                                                data-type="employee"
                                                data-id="{{ $employee->id }}"
                                                data-name="{{ $employee->name }}">
                                            <img src="{{ asset('assets/delete.png') }}" alt="Delete" class="w-5 h-5">
                                            <span>Delete</span>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    @foreach($drivers as $driver)
                        <tr class="border-b border-white/10 hover:bg-white/10 transition-all">
                            <td class="p-4">{{ $driver->id }}</td>
                            <td class="p-4">{{ $driver->name }}</td>
                            <td class="p-4">{{ $driver->email }}</td>
                            <td class="p-4">{{ $driver->license_num }}</td>
                            <td class="p-4 text-center">
                                <div class="relative inline-block">
                                    <button type="button" class="actions-toggle p-2 rounded-full hover:bg-white/10 focus:outline-none" aria-expanded="false">
                                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="5" cy="12" r="1.5" />
                                            <circle cx="12" cy="12" r="1.5" />
                                            <circle cx="19" cy="12" r="1.5" />
                                        </svg>
                                    </button>

                                    <div class="actions-menu hidden absolute right-0 mt-2 w-40 bg-[#262B32] rounded-lg shadow-xl z-50 border border-white/10" style="transform: translateZ(0); pointer-events: auto;">
                                        <button class="edit-btn flex items-center gap-3 w-full px-3 py-2 text-white hover:bg-white/5"
                                                data-type="driver"
                                                data-id="{{ $driver->id }}"
                                                data-name="{{ $driver->name }}"
                                                data-email="{{ $driver->email }}"
                                                data-license="{{ $driver->license_num }}"
                                                data-date="{{ $driver->dateadded }}">
                                            <img src="{{ asset('assets/edit.png') }}" alt="Edit" class="w-5 h-5">
                                            <span>Edit</span>
                                        </button>

                                        <button class="delete-btn flex items-center gap-3 w-full px-3 py-2 text-white hover:bg-white/5"
                                                data-type="driver"
                                                data-id="{{ $driver->id }}"
                                                data-name="{{ $driver->name }}">
                                            <img src="{{ asset('assets/delete.png') }}" alt="Delete" class="w-5 h-5">
                                            <span>Delete</span>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ADD/EDIT MODAL --}}
        <div id="employeeModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" id="employeeBackdrop"></div>
            <div id="employeeModalCard" class="modal-content relative w-96 p-6 rounded-2xl shadow-2xl bg-[#262B32] transform scale-90 opacity-0 transition-all duration-300">
                <h2 class="text-2xl font-bold text-red-500 mb-4" id="modalTitle">Add Employee</h2>
                {{-- CLIENT-SIDE VALIDATION MESSAGES --}}
                <div id="clientSideErrors" class="hidden mb-4 p-3 bg-red-600/20 border border-red-500 rounded-lg text-red-300 text-sm">
                    <ul id="clientErrorList"></ul>
                </div>
                <form method="POST" id="employeeForm">
                    @csrf
                    <input type="hidden" name="_method" id="employee_hidden_method" value="POST">
                    <input type="hidden" name="id" id="employee_id">
                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Name</label>
                        <input type="text" name="name" id="employee_name" required
                               class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500"
                               placeholder="Enter name">
                        <span class="text-red-400 text-sm hidden" id="nameError"></span>
                    </div>
                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Email</label>
                        <input type="email" name="email" id="employee_email" required
                               class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500"
                               placeholder="Enter email">
                        <span class="text-red-400 text-sm hidden" id="emailError"></span>
                    </div>
                    <div class="mb-4" id="positionField">
                        <label class="block font-semibold mb-1">Position</label>
                        <select name="position" id="employee_role" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                            <option value="" disabled selected>Select position</option>
                            <option value="Booking Officer">Booking Officer</option>
                            <option value="Fleet Assistant">Fleet Assistant</option>
                        </select>
                        <span class="text-red-400 text-sm hidden" id="positionError"></span>
                    </div>
                    <div class="mb-4" id="licenseField" style="display:none;">
                        <label class="block font-semibold mb-1">License No.</label>
                        <input type="text" name="license_num" id="employee_license"
                               class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500"
                               placeholder="Enter license number">
                        <span class="text-red-400 text-sm hidden" id="licenseError"></span>
                    </div>
                    <div class="mb-4" id="dateAddedField" style="display:none;">
                        <label class="block font-semibold mb-1">Date Added</label>
                        <input type="date" name="dateadded" id="driver_dateadded"
                               class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    <div class="flex justify-end mt-6 gap-3">
                        <button type="button" id="closeEmployeeModalBtn" class="cursor-pointer px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-lg text-white transition-all duration-200">Cancel</button>
                        <button type="submit" id="submitEmployeeBtn" class="cursor-pointer px-4 py-2 bg-red-700 hover:bg-red-500 rounded-lg text-white transition-all duration-200">Save</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- DELETE MODAL WITH PASSWORD CONFIRMATION --}}
        <div id="deleteEmployeeModal" class="fixed inset-0 flex items-center justify-center z-[60] hidden">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" id="deleteEmployeeBackdrop"></div>
            <form id="deleteEmployeeForm" method="POST" class="modal-content relative w-96 p-6 rounded-2xl shadow-2xl bg-[#262B32] transform scale-90 opacity-0 transition-all duration-300">
                @csrf
                <input type="hidden" name="_method" value="DELETE">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-red-500 mb-2">Confirm Delete</h2>
                    <p class="text-gray-300 mb-4">Enter your password to confirm deletion of "<span id="deleteEmployeeName" class="font-semibold"></span>"</p>
                    {{-- DELETE ERROR MESSAGE --}}
                    <div id="deleteError" class="hidden mb-4 p-3 bg-red-600/20 border border-red-500 rounded-lg text-red-300 text-sm" style="min-height: 2.5rem; display: none;">
                        <span id="deleteErrorText"></span>
                    </div>
                    {{-- Password Input --}}
                    <div class="mb-4 text-left">
                        <input type="password" name="admin_password" required
                               class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500"
                               placeholder="Enter your password to confirm">
                        <p class="text-xs text-gray-400 mt-1 ms-1">This action cannot be undone.</p>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" id="cancelDeleteEmployeeBtn" class="cursor-pointer px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-lg text-white transition-all duration-200">Cancel</button>
                    <button type="submit" class="cursor-pointer px-4 py-2 bg-red-700 hover:bg-red-500 rounded-lg text-white transition-all duration-200">Delete</button>
                </div>
            </form>
        </div>

    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Tab functionality
    let activeHrType = 'employee';
    const navEmployees = document.getElementById('navEmployees');
    const navDrivers = document.getElementById('navDrivers');
    const addBtn = document.getElementById('addHrBtn');
    const hrTable = document.getElementById('hrTable');
    const allRows = hrTable.querySelectorAll('tr');

    // Function to filter table rows based on type
    function filterTableRows(type) {
        allRows.forEach(row => {
            const editBtn = row.querySelector('.edit-btn');
            if (editBtn) {
                const rowType = editBtn.dataset.type;
                row.style.display = rowType === type ? '' : 'none';
            }
        });
    }

    function setActive(type) {
        activeHrType = type;
        // Update button styles
        if(type === 'employee'){
            navEmployees.classList.add('bg-red-700'); 
            navEmployees.classList.remove('bg-black/30');
            navDrivers.classList.remove('bg-red-700'); 
            navDrivers.classList.add('bg-black/30');
            addBtn.textContent = '+ Add Employee';
            document.getElementById('thirdColumnHeader').textContent = 'Position';
        } else {
            navDrivers.classList.add('bg-red-700'); 
            navDrivers.classList.remove('bg-black/30');
            navEmployees.classList.remove('bg-red-700'); 
            navEmployees.classList.add('bg-black/30');
            addBtn.textContent = '+ Add Driver';
            document.getElementById('thirdColumnHeader').textContent = 'License';
        }
        // Filter the table
        filterTableRows(type);
    }

    // Tab click handlers
    navEmployees.addEventListener('click', () => setActive('employee'));
    navDrivers.addEventListener('click', () => setActive('driver'));

    // Initialize with employees visible
    setActive(activeHrType);

    // Add/Edit Modal Logic
    const modal = document.getElementById('employeeModal');
    const modalCard = document.getElementById('employeeModalCard');
    const backdrop = document.getElementById('employeeBackdrop');
    const closeBtn = document.getElementById('closeEmployeeModalBtn');
    const modalTitle = document.getElementById('modalTitle');
    const form = document.getElementById('employeeForm');
    const positionField = document.getElementById('positionField');
    const licenseField = document.getElementById('licenseField');
    const dateAddedField = document.getElementById('dateAddedField');
    const clientSideErrors = document.getElementById('clientSideErrors');
    const clientErrorList = document.getElementById('clientErrorList');

    function openModal(type = activeHrType, data = {}) {
        modalTitle.textContent = data.id ? `Edit ${type === 'driver' ? 'Driver' : 'Employee'}` : `Add ${type === 'driver' ? 'Driver' : 'Employee'}`;
        // Set form action
        if (data.id) {
            form.action = type === 'employee' 
                ? `/admin/hr/${data.id}` 
                : `/admin/drivers/${data.id}`;
        } else {
            form.action = type === 'employee' 
                ? "{{ route('admin.hr.store') }}" 
                : "{{ route('admin.drivers.store') }}";
        }
        // Set method
        form.querySelector('#employee_hidden_method').value = data.id ? 'PUT' : 'POST';

        // Set form values
        document.getElementById('employee_id').value = data.id || '';
        document.getElementById('employee_name').value = data.name || '';
        document.getElementById('employee_email').value = data.email || '';

        if(type === 'driver'){
            positionField.style.display = 'none';
            licenseField.style.display = 'block';
            dateAddedField.style.display = 'block';
            document.getElementById('employee_license').value = data.license_num || '';
            document.getElementById('driver_dateadded').value = data.dateadded || '';
        } else {
            positionField.style.display = 'block';
            licenseField.style.display = 'none';
            dateAddedField.style.display = 'none';
            document.getElementById('employee_role').value = data.role || '';
        }

        // Clear errors
        clearErrors();

        modal.classList.remove('hidden');
        setTimeout(() => { 
            modalCard.classList.remove('scale-90', 'opacity-0'); 
            modalCard.classList.add('scale-100', 'opacity-100'); 
        }, 10);
    }

    function closeModal() {
        modalCard.classList.remove('scale-100', 'opacity-100');
        modalCard.classList.add('scale-90', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function clearErrors() {
        clientSideErrors.classList.add('hidden');
        clientErrorList.innerHTML = '';
        document.querySelectorAll('[id$="Error"]').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
    }

    function showError(field, message) {
        const errorElement = document.getElementById(field + 'Error');
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Form validation
    form.addEventListener('submit', function(e) {
        clearErrors();
        let isValid = true;
        const errors = [];

        const name = document.getElementById('employee_name').value.trim();
        const email = document.getElementById('employee_email').value.trim();
        const currentType = activeHrType;

        if (!name) {
            showError('name', 'Name is required');
            errors.push('Name is required');
            isValid = false;
        }

        if (!email) {
            showError('email', 'Email is required');
            errors.push('Email is required');
            isValid = false;
        } else if (!isValidEmail(email)) {
            showError('email', 'Please enter a valid email address');
            errors.push('Please enter a valid email address');
            isValid = false;
        }

        if (currentType === 'employee') {
            const position = document.getElementById('employee_role').value;
            if (!position) {
                showError('position', 'Position is required');
                errors.push('Position is required');
                isValid = false;
            }
        } else {
            const license = document.getElementById('employee_license').value;
            if (!license) {
                showError('license', 'License number is required');
                errors.push('License number is required');
                isValid = false;
            }
        }

        if (!isValid) {
            e.preventDefault();
            if (errors.length > 0) {
                clientErrorList.innerHTML = errors.map(error => `<li>${error}</li>`).join('');
                clientSideErrors.classList.remove('hidden');
            }
        }
    });

    // Add button click
    document.getElementById('addHrBtn').addEventListener('click', () => openModal());

    // Edit buttons
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const type = btn.dataset.type;
            openModal(type, {
                id: btn.dataset.id,
                name: btn.dataset.name,
                email: btn.dataset.email,
                role: btn.dataset.role,
                license_num: btn.dataset.license,
                dateadded: btn.dataset.date
            });
        });
    });

    // Close modal buttons
    document.getElementById('closeEmployeeModalBtn').addEventListener('click', closeModal);
    document.getElementById('employeeBackdrop').addEventListener('click', closeModal);

    // Delete Modal Logic
    const deleteModal = document.getElementById('deleteEmployeeModal');
    const deleteBackdrop = document.getElementById('deleteEmployeeBackdrop');
    const cancelDeleteBtn = document.getElementById('cancelDeleteEmployeeBtn');
    const deleteForm = document.getElementById('deleteEmployeeForm');
    const deleteName = document.getElementById('deleteEmployeeName');
    const deleteError = document.getElementById('deleteError');
    const deleteErrorText = document.getElementById('deleteErrorText');
    let currentDeleteButton = null;

    // Delete buttons
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            deleteForm.action = btn.dataset.type === 'employee' 
                ? `/admin/hr/${btn.dataset.id}` 
                : `/admin/drivers/${btn.dataset.id}`;
            deleteName.textContent = btn.dataset.name;
            currentDeleteButton = btn;
            hideDeleteError();
            deleteForm.reset();

            deleteModal.classList.remove('hidden');
            setTimeout(() => deleteModal.querySelector('.modal-content').classList.remove('scale-90', 'opacity-0'), 10);
        });
    });

// Actions menu toggles (three-dots)
document.querySelectorAll('.actions-toggle').forEach(toggle => {
    const menu = toggle.nextElementSibling;
    toggle.addEventListener('click', (e) => {
        e.stopPropagation();
        // Close other open menus
        document.querySelectorAll('.actions-menu').forEach(m => {
            if (m !== menu) {
                m.classList.add('hidden');
                m.classList.remove('dropup');
            }
        });
        // Toggle current menu
        if (menu) {
            menu.classList.toggle('hidden');
            
            // Check if menu would go off-screen and position upward if needed
            if (!menu.classList.contains('hidden')) {
                setTimeout(() => {
                    const rect = menu.getBoundingClientRect();
                    const isOffScreen = rect.bottom > window.innerHeight;
                    
                    if (isOffScreen) {
                        menu.classList.add('dropup');
                    } else {
                        menu.classList.remove('dropup');
                    }
                }, 0);
            }
        }
    });
    if (menu) menu.addEventListener('click', e => e.stopPropagation());
});

// Close any open action menus when clicking outside
document.addEventListener('click', () => {
    document.querySelectorAll('.actions-menu').forEach(m => m.classList.add('hidden'));
});

    function hideDeleteError() {
        deleteError.style.display = 'none';
        deleteError.classList.add('hidden');
    }

    function showDeleteError(message) {
        deleteErrorText.textContent = message;
        deleteError.style.display = 'flex';
        deleteError.classList.remove('hidden');
    }

    // Handle delete form submission
    deleteForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;

        try {
            submitButton.textContent = 'Deleting...';
            submitButton.disabled = true;

            const response = await fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: new FormData(this)
            });

            const result = await response.json();

            if (response.ok) {
                // Show success message
                showSuccessMessage(result.message || 'Record deleted successfully.');

                // Remove the row from table
                if (currentDeleteButton) {
                    const row = currentDeleteButton.closest('tr');
                    if (row) row.remove();
                }

                closeDeleteModal();
            } else {
                showDeleteError(result.message || 'An error occurred while deleting.');
            }
        } catch (error) {
            showDeleteError('Network error. Please try again.');
        } finally {
            submitButton.textContent = originalText;
            submitButton.disabled = false;
        }
    });

    function showSuccessMessage(message) {
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

    function closeDeleteModal() {
        deleteModal.querySelector('.modal-content').classList.add('scale-90', 'opacity-0');
        setTimeout(() => {
            deleteModal.classList.add('hidden');
            deleteForm.reset();
            hideDeleteError();
        }, 300);
    }

    document.getElementById('cancelDeleteEmployeeBtn').addEventListener('click', closeDeleteModal);
    document.getElementById('deleteEmployeeBackdrop').addEventListener('click', closeDeleteModal);

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', () => {
        const searchTerm = searchInput.value.toLowerCase();
        allRows.forEach(row => {
            const editBtn = row.querySelector('.edit-btn');
            if (editBtn) {
                const rowType = editBtn.dataset.type;
                if (rowType === activeHrType || row.style.display !== 'none') {
                    const cells = row.querySelectorAll('td');
                    let rowText = '';
                    cells.forEach(cell => {
                        rowText += cell.textContent.toLowerCase() + ' ';
                    });
                    row.style.display = rowText.includes(searchTerm) ? '' : 'none';
                }
            }
        });
    });

    // Auto-hide messages after 5 seconds
    setTimeout(() => {
        const successMessage = document.getElementById('successMessage');
        const errorMessage = document.getElementById('errorMessage');
        if (successMessage) successMessage.remove();
        if (errorMessage) errorMessage.remove();
    }, 5000);
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