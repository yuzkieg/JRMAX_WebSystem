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
            <h1 class="text-3xl font-bold text-red-500 drop-shadow-lg">User Management</h1>
          
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
            

        {{-- SEARCH AND ADD EMPLOYEE --}}
        <div class="flex justify-between items-center mb-6">
            <input type="text" placeholder="Search users..."
                   class="w-80 p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none 
                   focus:ring-2 focus:ring-red-500 transition-all duration-300"
                   id="searchInput">

            <button data-action="add-employee" class="cursor-pointer px-5 py-2 bg-red-700 hover:bg-red-500 rounded-xl text-white shadow-lg transition-all duration-300 hover:scale-105">
                + Add User
            </button>
        </div>

        {{-- USER TABLE --}}
        <div class="rounded-2xl shadow-2xl backdrop-blur-xl">
            <table class="w-full text-left dark-table">
                <thead class="bg-black/30 text-white uppercase text-sm tracking-wide text-center">
                    <tr>
                        <th class="p-4">ID</th>
                        <th class="p-4">Name</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Role</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="employeeTable" class="text-white">
                    @foreach($employees as $employee)
                    <tr class="border-b border-white/10 hover:bg-white/10 transition-all text-center">
                        <td class="p-4">{{ $employee->id }}</td>
                        <td class="p-4">{{ $employee->name }}</td>
                        <td class="p-4">{{ $employee->email }}</td>
                        <td class="p-4">{{ ucfirst($employee->role) }}</td>
                        <td class="p-4 text-center flex justify-center gap-3">
                            <div class="relative inline-block">
                                <button type="button" class="actions-toggle p-2 rounded-full hover:bg-white/10 focus:outline-none" aria-expanded="false">
                                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="5" cy="12" r="1.5" />
                                        <circle cx="12" cy="12" r="1.5" />
                                        <circle cx="19" cy="12" r="1.5" />
                                    </svg>
                                </button>

                                <div class="actions-menu hidden absolute right-0 mt-2 w-40 bg-[#262B32] rounded-lg shadow-xl z-50 border border-white/10" style="transform: translateZ(0); pointer-events: auto;">
                                    <button class="edit-btn edit-employee-btn flex items-center gap-3 w-full px-3 py-2 text-white hover:bg-white/5"
                                            data-id="{{ $employee->id }}"
                                            data-name="{{ $employee->name }}"
                                            data-email="{{ $employee->email }}"
                                            data-role="{{ $employee->role }}">
                                        <img src="{{ asset('assets/edit.png') }}" alt="Edit" class="w-5 h-5">
                                        <span>Edit</span>
                                    </button>

                                    <button class="delete-btn delete-employee-btn flex items-center gap-3 w-full px-3 py-2 text-white hover:bg-white/5"
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
                </tbody>
            </table>
        </div>

        {{-- ADD / EDIT MODAL --}}
        <div id="employeeModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" id="employeeBackdrop"></div>

            <div id="employeeModalCard" class="modal-content relative w-96 p-6 rounded-2xl shadow-2xl bg-[#262B32] transform scale-90 opacity-0 transition-all duration-300">
                <h2 class="text-2xl font-bold text-red-500 mb-4" id="employeeModalTitle">Add User</h2>

                {{-- CLIENT-SIDE VALIDATION MESSAGES --}}
                <div id="clientSideErrors" class="hidden mb-4 p-3 bg-red-600/20 border border-red-500 rounded-lg text-red-300 text-sm">
                    <ul id="clientErrorList"></ul>
                </div>

                <form method="POST" action="{{ route('admin.employees.store') }}" id="employeeForm">
                    @csrf
                    <input type="hidden" name="_method" id="employee_hidden_method" value="POST">
                    <input type="hidden" name="employee_id" id="employee_id">

                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Name</label>
                        <input type="text" name="name" id="employee_name" required
                               class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" placeholder="Enter name">
                        <span class="text-red-400 text-sm hidden" id="nameError"></span>
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Email</label>
                        <input type="email" name="email" id="employee_email" required
                               class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500" placeholder="Enter email">
                        <span class="text-red-400 text-sm hidden" id="emailError"></span>
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Role</label>
                        <select name="role" id="employee_role" required
                                class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                            <option value="" disabled selected>Select role</option>
                            <option value="booking_officer">Booking Officer</option>
                            <option value="fleet_assistant">Fleet Assistant</option>
                        </select>
                        <span class="text-red-400 text-sm hidden" id="roleError"></span>
                    </div>

                    <div class="mb-4" id="employee_password_field">
                        <label class="block font-semibold mb-1">Password</label>
                        <input type="password" name="password" id="employee_password"
                               class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500"
                               placeholder="Enter password">
                        <span class="text-red-400 text-sm hidden" id="passwordError"></span>
                    </div>

                    <div class="mb-4" id="employee_password_confirm_field">
                        <label class="block font-semibold mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="employee_password_confirmation"
                               class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500"
                               placeholder="Confirm password">
                        <span class="text-red-400 text-sm hidden" id="passwordConfirmError"></span>
                    </div>

                    <div class="flex justify-end mt-6 gap-3">
                        <button type="button" id="closeEmployeeModalBtn"
                                class="cursor-pointer px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-lg text-white">Cancel</button>

                        <button type="submit" id="submitEmployeeBtn"
                                class="cursor-pointer px-4 py-2 bg-red-700 hover:bg-red-500 rounded-lg text-white">Save</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- DELETE MODAL (UPDATED UI) --}}
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
                    <div id="deleteError" class="hidden mb-4 p-3 bg-red-600/20 border border-red-500 rounded-lg text-red-300 text-sm">
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
class EmployeeModal {
    constructor() {
        this.modal = document.getElementById('employeeModal');
        this.modalCard = document.getElementById('employeeModalCard');
        this.backdrop = document.getElementById('employeeBackdrop');
        this.form = document.getElementById('employeeForm');
        this.clientSideErrors = document.getElementById('clientSideErrors');
        this.clientErrorList = document.getElementById('clientErrorList');

        this.initializeEvents();
    }

    initializeEvents() {
        document.querySelector('[data-action="add-employee"]').addEventListener('click', () => this.openModal());
        document.getElementById('closeEmployeeModalBtn').addEventListener('click', () => this.closeModal());
        this.backdrop.addEventListener('click', () => this.closeModal());

        // Form validation
        this.form.addEventListener('submit', (e) => this.validateForm(e));

        // Real-time password matching validation
        document.getElementById('employee_password')?.addEventListener('input', () => this.validatePasswords());
        document.getElementById('employee_password_confirmation')?.addEventListener('input', () => this.validatePasswords());

        document.querySelectorAll('.edit-employee-btn').forEach(btn => {
            btn.addEventListener('click', () => this.openEditModal(
                btn.dataset.id,
                btn.dataset.name,
                btn.dataset.email,
                btn.dataset.role
            ));
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
    }

    openModal() {
        this.resetForm();
        this.clearErrors();

        document.getElementById('employeeModalTitle').textContent = 'Add Employee';
        this.form.action = '{{ route("admin.employees.store") }}';
        this.form.querySelector('#employee_hidden_method').value = 'POST';

        // Show both password fields for add mode
        document.getElementById('employee_password_field').style.display = 'block';
        document.getElementById('employee_password_confirm_field').style.display = 'block';

        // Set password fields as required for add mode
        document.getElementById('employee_password').required = true;
        document.getElementById('employee_password_confirmation').required = true;

        this.showModal();
    }

    openEditModal(id, name, email, role) {
        this.resetForm();
        this.clearErrors();

        document.getElementById('employeeModalTitle').textContent = 'Edit User';
        this.form.action = '{{ route("admin.employees.update", ":id") }}'.replace(':id', id);
        this.form.querySelector('#employee_hidden_method').value = 'PUT';

        document.getElementById('employee_id').value = id;
        document.getElementById('employee_name').value = name;
        document.getElementById('employee_email').value = email;
        document.getElementById('employee_role').value = role;

        // Show both password fields for edit mode
        document.getElementById('employee_password_field').style.display = 'block';
        document.getElementById('employee_password_confirm_field').style.display = 'block';

        // Make password fields optional for edit mode
        document.getElementById('employee_password').required = false;
        document.getElementById('employee_password_confirmation').required = false;

        // Update placeholders for edit mode
        document.getElementById('employee_password').placeholder = 'Enter new password (optional)';
        document.getElementById('employee_password_confirmation').placeholder = 'Confirm new password (optional)';

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

        setTimeout(() => {
            this.modal.classList.add('hidden');
            this.clearErrors();
        }, 300);
    }

    resetForm() {
        this.form.reset();
        // Reset placeholders
        document.getElementById('employee_password').placeholder = 'Enter password';
        document.getElementById('employee_password_confirmation').placeholder = 'Confirm password';
    }

    clearErrors() {
        // Clear client-side errors
        this.clientSideErrors.classList.add('hidden');
        this.clientErrorList.innerHTML = '';

        // Clear individual field errors
        const errorElements = document.querySelectorAll('[id$="Error"]');
        errorElements.forEach(element => {
            element.classList.add('hidden');
            element.textContent = '';
        });
    }

    showError(field, message) {
        const errorElement = document.getElementById(field + 'Error');
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }
    }

    validatePasswords() {
        const password = document.getElementById('employee_password').value;
        const confirmPassword = document.getElementById('employee_password_confirmation').value;
        const isEditMode = this.form.querySelector('#employee_hidden_method').value === 'PUT';
        
        // For add mode, both fields are required
        if (!isEditMode) {
            if (password && confirmPassword && password !== confirmPassword) {
                this.showError('passwordConfirm', 'Passwords do not match');
                return false;
            } else {
                this.hideError('passwordConfirm');
                return true;
            }
        } else {
            // For edit mode, only validate if both fields are filled
            if (password || confirmPassword) {
                if (password !== confirmPassword) {
                    this.showError('passwordConfirm', 'Passwords do not match');
                    return false;
                } else {
                    this.hideError('passwordConfirm');
                    return true;
                }
            }
        }
        return true;
    }

    hideError(field) {
        const errorElement = document.getElementById(field + 'Error');
        if (errorElement) {
            errorElement.classList.add('hidden');
        }
    }

    validateForm(e) {
        this.clearErrors();
        
        let isValid = true;
        const errors = [];

        // Required field validation
        const name = document.getElementById('employee_name').value.trim();
        const email = document.getElementById('employee_email').value.trim();
        const role = document.getElementById('employee_role').value;
        const password = document.getElementById('employee_password').value;
        const confirmPassword = document.getElementById('employee_password_confirmation').value;

        if (!name) {
            this.showError('name', 'Name is required');
            errors.push('Name is required');
            isValid = false;
        }

        if (!email) {
            this.showError('email', 'Email is required');
            errors.push('Email is required');
            isValid = false;
        } else if (!this.isValidEmail(email)) {
            this.showError('email', 'Please enter a valid email address');
            errors.push('Please enter a valid email address');
            isValid = false;
        }

        if (!role) {
            this.showError('role', 'Role is required');
            errors.push('Role is required');
            isValid = false;
        }

        // Password validation based on mode
        const isEditMode = this.form.querySelector('#employee_hidden_method').value === 'PUT';
        
        if (!isEditMode) {
            // Add mode - password is required
            if (!password) {
                this.showError('password', 'Password is required');
                errors.push('Password is required');
                isValid = false;
            } else if (password.length < 8) {
                this.showError('password', 'Password must be at least 8 characters long');
                errors.push('Password must be at least 8 characters long');
                isValid = false;
            }

            if (!confirmPassword) {
                this.showError('passwordConfirm', 'Please confirm your password');
                errors.push('Please confirm your password');
                isValid = false;
            }

            if (password && confirmPassword && password !== confirmPassword) {
                this.showError('passwordConfirm', 'Passwords do not match');
                errors.push('Passwords do not match');
                isValid = false;
            }
        } else {
            // Edit mode - password is optional but must match if provided
            if (password || confirmPassword) {
                if (password.length < 8) {
                    this.showError('password', 'Password must be at least 8 characters long');
                    errors.push('Password must be at least 8 characters long');
                    isValid = false;
                }

                if (password !== confirmPassword) {
                    this.showError('passwordConfirm', 'Passwords do not match');
                    errors.push('Passwords do not match');
                    isValid = false;
                }
            }
        }

        if (!isValid) {
            e.preventDefault();
            
            // Show client-side errors summary
            if (errors.length > 0) {
                this.clientErrorList.innerHTML = errors.map(error => `<li>${error}</li>`).join('');
                this.clientSideErrors.classList.remove('hidden');
            }
        }

        return isValid;
    }

    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
}

class DeleteEmployeeModal {
    constructor() {
        this.modal = document.getElementById('deleteEmployeeModal');
        this.modalCard = this.modal.querySelector('.modal-content');
        this.backdrop = document.getElementById('deleteEmployeeBackdrop');
        this.nameSpan = document.getElementById('deleteEmployeeName');
        this.form = document.getElementById('deleteEmployeeForm');
        this.deleteError = document.getElementById('deleteError');
        this.deleteErrorText = document.getElementById('deleteErrorText');
        this.currentButton = null;

        this.initializeEvents();
    }

    initializeEvents() {
        document.querySelectorAll('.delete-employee-btn').forEach(btn => {
            btn.addEventListener('click', (e) => this.openModal(btn.dataset.id, btn.dataset.name, e.currentTarget));
        });

        document.getElementById('cancelDeleteEmployeeBtn').addEventListener('click', () => this.closeModal());
        this.backdrop.addEventListener('click', () => this.closeModal());

        // Handle delete form submission
        this.form.addEventListener('submit', (e) => this.handleDelete(e));
    }

    openModal(id, name, button) {
        this.nameSpan.textContent = name;
        this.form.action = '{{ route("admin.employees.destroy", ":id") }}'.replace(':id', id);
        this.hideDeleteError();
        this.currentButton = button || null;

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
            this.hideDeleteError();
        }, 300);
    }

    showDeleteError(message) {
        this.deleteErrorText.textContent = message;
        this.deleteError.classList.remove('hidden');
    }

    hideDeleteError() {
        this.deleteError.classList.add('hidden');
    }

    async handleDelete(e) {
        e.preventDefault();
        
        const formData = new FormData(this.form);
        const submitButton = this.form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;

        try {
            // Show loading state
            submitButton.textContent = 'Deleting...';
            submitButton.disabled = true;

            const response = await fetch(this.form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const result = await response.json();

            if (response.ok) {
                // Success - show a success banner, remove the deleted row, close modal
                const message = result.message || 'User deleted successfully.';
                this.showSuccess(message);

                // Remove the row corresponding to the button that opened the modal
                try {
                    const id = this.currentButton?.dataset?.id;
                    if (this.currentButton) {
                        const row = this.currentButton.closest('tr');
                        if (row) row.remove();
                    } else if (id) {
                        const btn = document.querySelector(`.delete-employee-btn[data-id="${id}"]`);
                        if (btn) btn.closest('tr')?.remove();
                    }
                } catch (err) {
                    // ignore DOM removal errors
                }

                this.closeModal();
            } else {
                // Show error message
                this.showDeleteError(result.message || 'An error occurred while deleting the user.');
            }
        } catch (error) {
            this.showDeleteError('Network error. Please try again.');
        } finally {
            submitButton.textContent = originalText;
            submitButton.disabled = false;
        }
    }

    showSuccess(message) {
        try {
            // Create banner similar to server-rendered successMessage
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

            // Insert at top of main content
            const firstChild = main.firstElementChild;
            if (firstChild) main.insertBefore(div, firstChild);
            else main.appendChild(div);

            // Remove after 5 seconds
            setTimeout(() => div.remove(), 5000);
        } catch (err) {
            // ignore
        }
    }
}

// Auto-hide success/error messages after 5 seconds
document.addEventListener('DOMContentLoaded', () => {
    new EmployeeModal();
    new DeleteEmployeeModal();

    // Auto-hide messages
    setTimeout(() => {
        const successMessage = document.getElementById('successMessage');
        const errorMessage = document.getElementById('errorMessage');
        
        if (successMessage) successMessage.remove();
        if (errorMessage) errorMessage.remove();
    }, 5000);

    // Search functionality
    const searchInput = document.getElementById('searchInput');

    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();

            document.querySelectorAll('#employeeTable tr').forEach(row => {
                const name = row.cells[0]?.textContent.toLowerCase();
                const email = row.cells[1]?.textContent.toLowerCase();

                row.style.display = (
                    name.includes(term) || email.includes(term)
                ) ? '' : 'none';
            });
        });
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