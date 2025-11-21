@extends('layouts.app')

@section('content')

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

        {{-- SUCCESS/ERROR MESSAGES --}}
        @if(session('success'))
        <div id="successMessage" class="mb-6 p-4 bg-green-600/20 border border-green-500 rounded-xl text-green-300 backdrop-blur-xl transition-all duration-300">
            ✅ {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div id="errorMessage" class="mb-6 p-4 bg-red-600/20 border border-red-500 rounded-xl text-red-300 backdrop-blur-xl transition-all duration-300">
            ❌ {{ session('error') }}
        </div>
        @endif

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
            <div id="modalCard" class="relative w-96 p-6 rounded-2xl shadow-2xl bg-[#262B32] transform scale-90 opacity-0 transition-all duration-300">
                <h2 class="text-2xl font-bold text-red-500 mb-4">Add Admin</h2>

                <form method="POST" action="{{ route('superadmin.admins.store') }}" id="adminForm">
    @csrf
    <input type="hidden" name="_method" id="hidden_method" value="POST">
    <input type="hidden" name="admin_id" id="admin_id">
    <div class="mb-4">
        <label class="block font-semibold mb-1">Name</label>
        <input type="text" name="name" id="admin_name" required
               class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500"
               placeholder="Enter admin name">
    </div>
    <div class="mb-4">
        <label class="block font-semibold mb-1">Email</label>
        <input type="email" name="email" id="admin_email" required
               class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500"
               placeholder="Enter admin email">
    </div>
    <div class="mb-4" id="passwordField">
        <label class="block font-semibold mb-1">Password</label>
        <input type="password" name="password" id="admin_password"
               class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500"
               placeholder="Enter password">
    </div>

    
    <div class="flex justify-end mt-6 gap-3">
        <button type="button" id="closeModalBtn" class="cursor-pointer px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-lg text-white transition-all duration-200">Cancel</button>
        <button type="submit" id="submitBtn" class="cursor-pointer px-4 py-2 bg-red-700 hover:bg-red-500 rounded-lg text-white transition-all duration-200">Save</button>
    </div>
</form>
            </div>
        </div>

       {{-- DELETE CONFIRMATION MODAL --}}
<div id="deleteConfirmModal" class="fixed inset-0 flex items-center justify-center z-[60] hidden">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" id="deleteModalBackdrop"></div>
    <div id="deleteModalCard" class="relative w-96 p-6 rounded-2xl shadow-2xl bg-[#262B32] transform scale-90 opacity-0 transition-all duration-300">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-red-500 mb-2">Confirm Delete</h2>
            <p class="text-gray-300 mb-4" id="deleteConfirmText">Are you sure you want to delete this admin?</p>
            
            {{-- Password Input --}}
            <div class="mb-4 text-left">
                <input type="password" id="superadminPassword" 
                       class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500"
                       placeholder="Enter your password to confirm"
                       required>
                <p class="text-xs text-gray-400 mt-1 ms-1">This action cannot be undone.</p>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <button type="button" id="cancelDeleteBtn" class="cursor-pointer px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-lg text-white transition-all duration-200">Cancel</button>
            <button type="button" id="confirmDeleteBtn" class="cursor-pointer px-4 py-2 bg-red-700 hover:bg-red-500 rounded-lg text-white transition-all duration-200 opacity-50 " disabled>Delete</button>
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
        this.isEditMode = false;

        // Delete modal elements
        this.deleteModal = document.getElementById('deleteConfirmModal');
        this.deleteModalCard = document.getElementById('deleteModalCard');
        this.deleteBackdrop = document.getElementById('deleteModalBackdrop');
        this.deleteConfirmText = document.getElementById('deleteConfirmText');
        this.confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        this.cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
        
        this.adminToDelete = null;
        
        // Correct password input reference
        this.superadminPasswordInput = document.getElementById('superadminPassword');

        this.initializeEvents();
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
        document.getElementById('modalCard').querySelector('h2').textContent = 'Add Admin';
        document.getElementById('passwordField').style.display = 'block';
        document.getElementById('admin_password').required = true;
        document.getElementById('submitBtn').textContent = 'Save';
        
        this.showModal();
    }

    openEditModal(id, name, email) {
        this.isEditMode = true;
        this.resetForm();
        
        this.form.setAttribute('action', `/superadmin/admins/${id}`);
        this.form.querySelector('input[name="_method"]').value = 'PUT';
         
        
        document.getElementById('admin_id').value = id;
        document.getElementById('admin_name').value = name;
        document.getElementById('admin_email').value = email;
        document.getElementById('modalCard').querySelector('h2').textContent = 'Edit Admin';
        document.getElementById('passwordField').style.display = 'none';
        document.getElementById('admin_password').required = false;
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
        }, 300);
    }

    showDeleteConfirmation(id, name) {
        this.adminToDelete = id;
        this.deleteConfirmText.textContent = `Are you sure you want to delete "${name}"? This action cannot be undone.`;
        
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

    // Confirm delete with password
    confirmDelete() {
        if (this.adminToDelete && !this.confirmDeleteBtn.disabled) {
            const password = this.superadminPasswordInput.value.trim();
            
            if (!password) {
                alert('Please enter your password to confirm deletion.');
                return;
            }

            // Create a form for deletion with password
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
            
            // Add password field
            const passwordField = document.createElement('input');
            passwordField.type = 'hidden';
            passwordField.name = 'superadmin_password';
            passwordField.value = password;
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            form.appendChild(passwordField);
            document.body.appendChild(form);
            form.submit();
        }
        
        this.hideDeleteConfirmation();
    }

    hideDeleteConfirmation() {
        this.deleteModalCard.classList.remove('scale-100', 'opacity-100');
        this.deleteModalCard.classList.add('scale-90', 'opacity-0');
        
        setTimeout(() => {
            this.deleteModal.classList.add('hidden');
            this.adminToDelete = null;
            // Clear password field
            this.superadminPasswordInput.value = '';
        }, 300);
    }

    resetForm() {
        this.form.reset();
        document.getElementById('admin_id').value = '';
        document.getElementById('passwordField').style.display = 'block';
        document.getElementById('admin_password').required = true;
    }

    autoHideMessages() {
        // Auto-hide success/error messages after 5 seconds
        setTimeout(() => {
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');
            
            if (successMessage) {
                successMessage.style.opacity = '0';
                setTimeout(() => successMessage.remove(), 300);
            }
            
            if (errorMessage) {
                errorMessage.style.opacity = '0';
                setTimeout(() => errorMessage.remove(), 300);
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