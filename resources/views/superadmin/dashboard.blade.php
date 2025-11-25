@extends('layouts.app')

@section('content')
@vite('resources/css/app.css')

<div class="flex min-h-screen bg-[#1A1F24] text-white transition-colors duration-500" id="dashboard-wrapper">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-black/80 h-screen fixed top-0 left-0 shadow-xl border-r border-white/10 backdrop-blur-xl transition-all duration-300 hover:w-72">
        <div class="p-6 flex flex-col items-center">
            <img src="{{ asset('assets/logo.png') }}" class="w-20 h-20 mb-4 transition-all duration-300 hover:scale-105">
            <h2 class="text-xl font-bold tracking-wide text-red-500">SUPER ADMIN</h2>
        </div>

        <nav class="mt-10 space-y-2 px-4">
            @php
                $menuItems = [
                    ['name' => 'Dashboard', 'url' => '/superadmin/dashboard'],
                    ['name' => 'Companies', 'url' => '/superadmin/companies'],
                    ['name' => 'System Settings', 'url' => '/superadmin/settings'],
                    ['name' => 'Activity Logs', 'url' => '/superadmin/logs'],
                ];
            @endphp

            @foreach($menuItems as $item)
            <a href="{{ $item['url'] }}"
               class="block py-3 px-4 rounded-lg hover:bg-red-600/40 hover:translate-x-2 transition-all duration-300 text-white">
                {{ $item['name'] }}
            </a>
            @endforeach
        </nav>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="ml-64 w-full min-h-screen p-8 transition-all duration-300">

        {{-- TOP HEADER --}}
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-red-500 drop-shadow-lg">JRMAX Car Rentals Inc.</h1>

            <div class="flex items-center space-x-4">
                {{-- Theme Toggle --}}
                <button id="theme-toggle" class="flex items-center gap-2 bg-black/30 backdrop-blur-xl p-2 rounded-lg hover:bg-[#998282] transition-all duration-300 cursor-pointer">
                    <img id="theme-icon" src="{{ asset('assets/moon.png') }}" class="w-6 h-6 transition-transform duration-500">
                    <span class="font-medium text-white">Dark Mode</span>
                </button>

                {{-- Logout --}}
                <form method="POST" action="/logout">
                    @csrf
                    <button class="cursor-pointer flex items-center gap-2 px-5 py-2 bg-[#742121] hover:bg-red-500 rounded-lg shadow-md transition-all duration-200 hover:scale-105 text-white">
                        <img src="{{ asset('assets/logout.png') }}" class="w-6 h-6">
                        <span>Logout</span>
                    </button>
                </form>
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

        @if(session('error'))
        <div id="errorMessage" class="mb-6 p-4 bg-red-600/20 border border-red-500 rounded-xl text-red-300 backdrop-blur-sm transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-red-300 hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        @endif

        @if ($errors->any())
        <div id="validationErrors" class="mb-6 p-4 bg-red-600/20 border border-red-500 rounded-xl text-red-300 backdrop-blur-sm transition-all duration-300">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-semibold">Please fix the following errors:</span>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-red-300 hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <ul class="mt-2 ml-6 list-disc text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- DASHBOARD CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            @php
                $cards = [
                    ['title' => 'Total Admins', 'value' => $admins->count()],
                    ['title' => 'Companies', 'value' => 1],
                    ['title' => 'Active Sessions', 'value' => 21],
                    ['title' => 'System Logs', 'value' => 105],
                ];
            @endphp

            @foreach($cards as $card)
            <div class="backdrop-blur-xl rounded-2xl p-6 shadow-2xl hover:scale-105 transition-all duration-300 cursor-pointer card-text"
                 style="background-color: rgba(38,43,50,0.85);">
                <h3 class="text-xl font-semibold mb-2">{{ $card['title'] }}</h3>
                <p class="text-3xl font-bold text-red-500">{{ $card['value'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- PAGE CONTENT: Manage Admins --}}
        <div class="backdrop-blur-xl p-8 rounded-2xl shadow-xl card-text dark-card">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-red-500 drop-shadow-xl">Manage Admins</h1>

                <button id="openModalBtn"
                    class="cursor-pointer px-5 py-2 bg-red-700 hover:bg-red-500 rounded-xl text-white shadow-lg transition-all duration-300 hover:scale-105">
                    + Add Admin
                </button>
            </div>

            {{-- SEARCH BAR --}}
            <div class="mb-6">
                <input type="text" placeholder="Search admins..."
                       class="w-80 p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none 
              focus:ring-2 focus:ring-red-500 transition-all duration-300"
                       id="searchInput"> 
            </div>

            {{-- ADMIN TABLE --}}
            <div class="overflow-hidden rounded-2xl shadow-2xl backdrop-blur-xl card-text dark-card">
                <table class="w-full text-left">
                    <thead class="bg-black/30 text-white uppercase text-sm tracking-wide">
                        <tr>
                            <th class="p-4">Name</th>
                            <th class="p-4">Email</th>
                            <th class="p-4">Role</th>
                            <th class="p-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="adminTable" class="text-white">
                        @foreach($admins as $admin)
                        <tr class="border-b border-white/10 hover:bg-white/10 transition-all" id="admin-row-{{ $admin->id }}">
                            <td class="p-4">{{ $admin->name }}</td>
                            <td class="p-4">{{ $admin->email }}</td>
                            <td class="p-4 text-red-400 font-semibold uppercase">{{ $admin->role }}</td>
                            <td class="p-4 text-center flex justify-center gap-3">
                                <button 
                                    class="cursor-pointer px-4 py-1 bg-blue-600 hover:bg-blue-500 rounded-lg text-white shadow transition-all duration-200 hover:scale-105 edit-admin-btn"
                                    data-id="{{ $admin->id }}"
                                    data-name="{{ $admin->name }}"
                                    data-email="{{ $admin->email }}">
                                    Edit
                                </button>

                                <button type="button"
                                        class="cursor-pointer px-4 py-1 bg-[#742121] hover:bg-red-500 rounded-lg text-white shadow transition-all duration-200 hover:scale-105 delete-admin-btn"
                                        data-id="{{ $admin->id }}"
                                        data-name="{{ $admin->name }}">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ADD/EDIT ADMIN MODAL --}}
        <div id="adminModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" id="modalBackdrop"></div>
            <div id="modalCard" class="modal-content relative w-96 p-6 rounded-2xl shadow-2xl bg-[#262B32] transform scale-90 opacity-0 transition-all duration-300">
                <h2 class="text-2xl font-bold text-red-500 mb-4" id="modalTitle">Add Admin</h2>

                {{-- CLIENT-SIDE VALIDATION MESSAGES --}}
                <div id="clientSideErrors" class="hidden mb-4 p-3 bg-red-600/20 border border-red-500 rounded-lg text-red-300 text-sm">
                    <ul id="clientErrorList"></ul>
                </div>

                <form method="POST" action="{{ route('superadmin.admins.store') }}" id="adminForm">
                    @csrf
                    <input type="hidden" name="_method" id="hidden_method" value="POST">
                    <input type="hidden" name="admin_id" id="admin_id">
                    
                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Name</label>
                        <input type="text" name="name" id="admin_name" required
                               class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500"
                               placeholder="Enter admin name">
                        <span class="text-red-400 text-sm hidden" id="nameError"></span>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Email</label>
                        <input type="email" name="email" id="admin_email" required
                               class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500"
                               placeholder="Enter admin email">
                        <span class="text-red-400 text-sm hidden" id="emailError"></span>
                    </div>
                    
                    <div class="mb-4" id="passwordField">
                        <label class="block font-semibold mb-1">Password</label>
                        <input type="password" name="password" id="admin_password"
                               class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500"
                               placeholder="Enter password">
                        <span class="text-red-400 text-sm hidden" id="passwordError"></span>
                    </div>
                    <div class="mb-4" id="passwordConfirmField">
                        <label class="block font-semibold mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="admin_password_confirmation"
                               class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500"
                               placeholder="Confirm password">
                        <span class="text-red-400 text-sm hidden" id="passwordConfirmError"></span>
                    </div>

                    <div class="flex justify-end mt-6 gap-3">
                        <button type="button" id="closeModalBtn" class="cursor-pointer px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-lg text-white transition-all duration-200">Cancel</button>
                        <button type="submit" id="submitBtn" class="cursor-pointer px-4 py-2 bg-red-700 hover:bg-red-500 rounded-lg text-white transition-all duration-200">Save</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- DELETE CONFIRMATION MODAL --}}
<div id="deleteConfirmModal" 
     class="fixed inset-0 flex items-center justify-center z-60 hidden">

    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" id="deleteModalBackdrop"></div>

    <div id="deleteModalCard" 
         class="modal-content relative w-96 p-6 rounded-2xl shadow-2xl bg-[#262B32] 
                transform scale-90 opacity-0 transition-all duration-300">

        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                    </path>
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-red-500 mb-2">Confirm Delete</h2>
            <p class="text-gray-300 mb-4" id="deleteConfirmText">
                Are you sure you want to delete this admin?
            </p>

            {{-- SIMPLE DELETE ERROR MESSAGE --}}
            <div id="deleteError" 
                 class="hidden mb-4 p-3 bg-red-600/20 border border-red-500 rounded-lg 
                        text-red-300 text-sm text-center">
                <span id="deleteErrorText"></span>
            </div>

            {{-- Password Input --}}
            <div class="mb-4 text-left">
                <input type="password" id="superadminPassword"
                       class="w-full p-3 rounded-xl bg-black/20 text-white outline-none 
                              focus:ring-2 focus:ring-red-500"
                       placeholder="Enter your password to confirm" required>
                <p class="text-xs text-gray-400 mt-1 ms-1">This action cannot be undone.</p>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <button type="button" id="cancelDeleteBtn"
                    class="cursor-pointer px-4 py-2 bg-gray-600 hover:bg-gray-500 
                           rounded-lg text-white transition-all duration-200">
                Cancel
            </button>

            <button type="button" id="confirmDeleteBtn"
                    class="cursor-pointer px-4 py-2 bg-red-700 hover:bg-red-500 
                           rounded-lg text-white transition-all duration-200
                           opacity-50" disabled>
                Delete
            </button>
        </div>
    </div>
</div>


    </main>
</div>

@endsection

<script>
class AdminModal {
    constructor() {
        this.modal = document.getElementById('adminModal');
        this.modalCard = document.getElementById('modalCard');
        this.backdrop = document.getElementById('modalBackdrop');
        this.form = document.getElementById('adminForm');
        this.clientSideErrors = document.getElementById('clientSideErrors');
        this.clientErrorList = document.getElementById('clientErrorList');
        this.isEditMode = false;

        // password inputs for validation
        this.passwordInput = document.getElementById('admin_password');
        this.passwordConfirmInput = document.getElementById('admin_password_confirmation');

        // store initial form metadata to allow full reset
        this.initialFormAction = this.form.getAttribute('action');
        this.initialMethodValue = this.form.querySelector('input[name="_method"]').value || 'POST';
        this.initialPasswordPlaceholder = this.passwordInput?.placeholder || 'Enter password';
        this.initialPasswordConfirmPlaceholder = this.passwordConfirmInput?.placeholder || 'Confirm password';
        this.initialSubmitBtnText = document.getElementById('submitBtn')?.textContent || 'Save';

        // Delete modal elements
        this.deleteModal = document.getElementById('deleteConfirmModal');
        this.deleteModalCard = document.getElementById('deleteModalCard');
        this.deleteBackdrop = document.getElementById('deleteModalBackdrop');
        this.deleteConfirmText = document.getElementById('deleteConfirmText');
        this.confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        this.cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
        this.deleteError = document.getElementById('deleteError');
        this.deleteErrorText = document.getElementById('deleteErrorText');
        
        this.adminToDelete = null;
        this.superadminPasswordInput = document.getElementById('superadminPassword');

        this.initializeEvents();
    }

    validatePasswords() {
        const password = this.passwordInput?.value || '';
        const confirm = this.passwordConfirmInput?.value || '';

        if (!this.isEditMode) {
            // add mode: both required and must match
            if (password && confirm && password !== confirm) {
                this.showError('passwordConfirm', 'Passwords do not match');
                return false;
            }
            this.hideError('passwordConfirm');
            return true;
        } else {
            // edit mode: only validate if one is filled
            if (password || confirm) {
                if (password !== confirm) {
                    this.showError('passwordConfirm', 'Passwords do not match');
                    return false;
                }
            }
            this.hideError('passwordConfirm');
            return true;
        }
    }

    initializeEvents() {
        // Open modal for adding
        document.getElementById('openModalBtn').addEventListener('click', () => {
            this.openModal();
        });

        // Close modal events
        document.getElementById('closeModalBtn').addEventListener('click', () => {
            this.closeModal();
        });

        this.backdrop.addEventListener('click', () => {
            this.closeModal();
        });

        // Form validation
        this.form.addEventListener('submit', (e) => this.validateForm(e));

        // Real-time password match validation
        this.passwordInput?.addEventListener('input', () => this.validatePasswords());
        this.passwordConfirmInput?.addEventListener('input', () => this.validatePasswords());

        // Edit buttons
        document.querySelectorAll('.edit-admin-btn').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const email = button.getAttribute('data-email');
                this.openEditModal(id, name, email);
            });
        });

        // Delete buttons
        document.querySelectorAll('.delete-admin-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                this.showDeleteConfirmation(id, name);
            });
        });

        // Delete confirmation events
        this.confirmDeleteBtn.addEventListener('click', () => {
            this.confirmDelete();
        });

        this.cancelDeleteBtn.addEventListener('click', () => {
            this.hideDeleteConfirmation();
        });

        this.deleteBackdrop.addEventListener('click', () => {
            this.hideDeleteConfirmation();
        });

        // Password input validation
        this.superadminPasswordInput.addEventListener('input', () => {
            this.validateDeletePassword();
        });

        // Allow Enter key to trigger delete when password is entered
        this.superadminPasswordInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !this.confirmDeleteBtn.disabled) {
                this.confirmDelete();
            }
        });

        // Escape key to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (!this.modal.classList.contains('hidden')) {
                    this.closeModal();
                }
                if (!this.deleteModal.classList.contains('hidden')) {
                    this.hideDeleteConfirmation();
                }
            }
        });

        // Auto-hide success/error messages
        this.autoHideMessages();
    }

    openModal() {
        this.isEditMode = false;
        this.resetForm();
        this.clearErrors();
        
        document.getElementById('modalTitle').textContent = 'Add Admin';
        document.getElementById('passwordField').style.display = 'block';
        document.getElementById('passwordConfirmField').style.display = 'block';
        document.getElementById('admin_password').required = true;
        document.getElementById('admin_password_confirmation').required = true;
        document.getElementById('submitBtn').textContent = 'Save';
        
        this.showModal();
    }

    openEditModal(id, name, email) {
        this.isEditMode = true;
        this.resetForm();
        this.clearErrors();
        
        this.form.setAttribute('action', `/superadmin/admins/${id}`);
        this.form.querySelector('input[name="_method"]').value = 'PUT';
        
        document.getElementById('admin_id').value = id;
        document.getElementById('admin_name').value = name;
        document.getElementById('admin_email').value = email;
        document.getElementById('modalTitle').textContent = 'Edit Admin';
        // Show password fields for optional password change during edit
        document.getElementById('passwordField').style.display = 'block';
        document.getElementById('passwordConfirmField').style.display = 'block';
        // Make password fields optional in edit mode
        document.getElementById('admin_password').required = false;
        document.getElementById('admin_password_confirmation').required = false;
        // Clear placeholders and values for edit mode
        document.getElementById('admin_password').value = '';
        document.getElementById('admin_password_confirmation').value = '';
        document.getElementById('admin_password').placeholder = 'Enter new password (optional)';
        document.getElementById('admin_password_confirmation').placeholder = 'Confirm new password (optional)';
        document.getElementById('submitBtn').textContent = 'Update';
        
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
            this.resetForm();
            this.clearErrors();
        }, 300);
    }

    showDeleteConfirmation(id, name) {
        this.adminToDelete = id;
        this.deleteConfirmText.textContent = `Are you sure you want to delete "${name}"?`;
        this.hideDeleteError();
        
        // Reset password field and disable delete button
        this.superadminPasswordInput.value = '';
        this.confirmDeleteBtn.disabled = true;
        this.confirmDeleteBtn.classList.add('opacity-50');
        this.confirmDeleteBtn.classList.remove('hover:bg-red-500');
        
        this.deleteModal.classList.remove('hidden');
        setTimeout(() => {
            this.deleteModalCard.classList.remove('scale-90', 'opacity-0');
            this.deleteModalCard.classList.add('scale-100', 'opacity-100');
            
            // Focus on password input
            this.superadminPasswordInput.focus();
        }, 10);
    }

    // Validate password input
    validateDeletePassword() {
        const password = this.superadminPasswordInput.value.trim();
        
        if (password.length >= 1) {
            this.confirmDeleteBtn.disabled = false;
            this.confirmDeleteBtn.classList.remove('opacity-50');
            this.confirmDeleteBtn.classList.add('hover:bg-red-500');
        } else {
            this.confirmDeleteBtn.disabled = true;
            this.confirmDeleteBtn.classList.add('opacity-50');
            this.confirmDeleteBtn.classList.remove('hover:bg-red-500');
        }
    }

    // Show delete error
    showDeleteError(message) {
        this.deleteErrorText.textContent = message;
        this.deleteError.classList.remove('hidden');
    }

    // Hide delete error
    hideDeleteError() {
        this.deleteError.classList.add('hidden');
    }

    // SIMPLIFIED DELETE CONFIRMATION
    async confirmDelete() {
        if (this.adminToDelete && !this.confirmDeleteBtn.disabled) {
            const password = this.superadminPasswordInput.value.trim();
            
            if (!password) {
                this.showDeleteError('Please enter your password.');
                return;
            }

            // Show loading state
            this.confirmDeleteBtn.textContent = 'Deleting.';
            this.confirmDeleteBtn.textContent = 'Deleting..';
            this.confirmDeleteBtn.textContent = 'Deleting...';
            this.confirmDeleteBtn.textContent = 'Deleting....';
            this.confirmDeleteBtn.disabled = true;

            try {
                // Simple form submission instead of JSON
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/superadmin/admins/${this.adminToDelete}`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                const passwordField = document.createElement('input');
                passwordField.type = 'hidden';
                passwordField.name = 'superadmin_password';
                passwordField.value = password;
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                form.appendChild(passwordField);
                document.body.appendChild(form);
                
                // Submit the form - let Laravel handle the response
                form.submit();
                
            } catch (error) {
                // If there's an error, just show a simple message
                this.showDeleteError('Something went wrong. Please try again.');
                this.confirmDeleteBtn.textContent = 'Delete';
                this.confirmDeleteBtn.disabled = false;
            }
        }
    }

    hideDeleteConfirmation() {
        this.deleteModalCard.classList.remove('scale-100', 'opacity-100');
        this.deleteModalCard.classList.add('scale-90', 'opacity-0');
        
        setTimeout(() => {
            this.deleteModal.classList.add('hidden');
            this.adminToDelete = null;
            this.superadminPasswordInput.value = '';
            this.hideDeleteError();
            // Reset delete button text
            this.confirmDeleteBtn.textContent = 'Delete';
        }, 300);
    }

    resetForm() {
        // Reset DOM form values
        this.form.reset();

        // Restore stored form action and method (undo edit changes)
        if (this.initialFormAction) {
            this.form.setAttribute('action', this.initialFormAction);
        }
        const methodField = this.form.querySelector('input[name="_method"]');
        if (methodField) {
            methodField.value = this.initialMethodValue || 'POST';
        }

        // Clear IDs and values that may have been set in edit mode
        document.getElementById('admin_id').value = '';
        if (this.passwordInput) {
            this.passwordInput.value = '';
            this.passwordInput.placeholder = this.initialPasswordPlaceholder;
            this.passwordInput.required = true;
        }
        if (this.passwordConfirmInput) {
            this.passwordConfirmInput.value = '';
            this.passwordConfirmInput.placeholder = this.initialPasswordConfirmPlaceholder;
            this.passwordConfirmInput.required = true;
        }

        // Ensure password fields are visible for add mode
        const pwField = document.getElementById('passwordField');
        const pwConfirmField = document.getElementById('passwordConfirmField');
        if (pwField) pwField.style.display = 'block';
        if (pwConfirmField) pwConfirmField.style.display = 'block';

        // Restore submit button and edit flag
        const submitBtn = document.getElementById('submitBtn');
        if (submitBtn) submitBtn.textContent = this.initialSubmitBtnText || 'Save';
        this.isEditMode = false;
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

    hideError(field) {
        const errorElement = document.getElementById(field + 'Error');
        if (errorElement) {
            errorElement.classList.add('hidden');
            errorElement.textContent = '';
        }
    }

    validateForm(e) {
        this.clearErrors();
        
        let isValid = true;
        const errors = [];

        // Required field validation
        const name = document.getElementById('admin_name').value.trim();
        const email = document.getElementById('admin_email').value.trim();
        const password = document.getElementById('admin_password').value;
        const confirmPassword = document.getElementById('admin_password_confirmation')?.value || '';

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

        if (!this.isEditMode) {
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
            // edit mode
            if (password || confirmPassword) {
                if (password.length > 0 && password.length < 8) {
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

    autoHideMessages() {
        // Auto-hide success/error messages after 5 seconds
        setTimeout(() => {
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');
            const validationErrors = document.getElementById('validationErrors');
            
            if (successMessage) {
                successMessage.style.opacity = '0';
                setTimeout(() => successMessage.remove(), 300);
            }
            
            if (errorMessage) {
                errorMessage.style.opacity = '0';
                setTimeout(() => errorMessage.remove(), 300);
            }

            if (validationErrors) {
                validationErrors.style.opacity = '0';
                setTimeout(() => validationErrors.remove(), 300);
            }
        }, 5000);
    }
}

// Initialize modal when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    const adminModal = new AdminModal();
    
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#adminTable tr');
            
            rows.forEach(row => {
                const name = row.cells[0].textContent.toLowerCase();
                const email = row.cells[1].textContent.toLowerCase();
                
                if (name.includes(searchTerm) || email.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
</script>