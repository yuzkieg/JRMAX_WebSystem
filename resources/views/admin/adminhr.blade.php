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
            <h1 class="text-3xl font-bold text-red-500 drop-shadow-lg">HR Management</h1>

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

        {{-- TOP NAVIGATION: Employees / Drivers --}}
        <div class="flex gap-4 mb-6">
            <button class="px-6 py-2 bg-red-700 hover:bg-red-500 rounded-xl shadow-lg transition-all duration-300 hover:scale-105">Employees</button>
            <button class="px-6 py-2 bg-black/30 hover:bg-red-600/40 rounded-xl shadow-lg transition-all duration-300 hover:scale-105">Drivers</button>
        </div>

        {{-- SEARCH AND ADD EMPLOYEE --}}
        <div class="flex justify-between items-center mb-6">
            <input type="text" placeholder="Search users..."
                   class="w-80 p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none 
                   focus:ring-2 focus:ring-red-500 transition-all duration-300"
                   id="searchInput">

            <button data-action="add-employee" class="px-5 py-2 bg-red-700 hover:bg-red-500 rounded-xl text-white shadow-lg transition-all duration-300 hover:scale-105">
                + Add User
            </button>
        </div>

        {{-- EMPLOYEE TABLE --}}
        <div class="overflow-hidden rounded-2xl shadow-2xl backdrop-blur-xl card-text dark-card">
            <table class="w-full text-left">
                <thead class="bg-black/30 text-white uppercase text-sm tracking-wide">
                    <tr>
                        <th class="p-4">Name</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Position</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="employeeTable" class="text-white">
                    <!-- @foreach($employees as $employee)
                    <tr class="border-b border-white/10 hover:bg-white/10 transition-all">
                        <td class="p-4">{{ $employee->name }}</td>
                        <td class="p-4">{{ $employee->email }}</td>
                        <td class="p-4">{{ ucfirst($employee->role) }}</td>
                        <td class="p-4 text-center flex justify-center gap-3">
                            <button class="px-4 py-1 bg-blue-600 hover:bg-blue-500 rounded-lg text-white shadow transition-all duration-200 hover:scale-105 edit-employee-btn"
                                    data-id="{{ $employee->id }}"
                                    data-name="{{ $employee->name }}"
                                    data-email="{{ $employee->email }}"
                                    data-role="{{ $employee->role }}">

                                Edit
                            </button>
                            <button class="px-4 py-1 bg-[#742121] hover:bg-red-500 rounded-lg text-white shadow transition-all duration-200 hover:scale-105 delete-employee-btn"
                                    data-id="{{ $employee->id }}"
                                    data-name="{{ $employee->name }}">
                                Delete
                            </button>
                        </td>
                    </tr>
                    @endforeach -->
                </tbody>
            </table>
        </div>

        {{-- ADD/EDIT MODAL --}}
        <div id="employeeModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" id="employeeBackdrop"></div>
            <div id="employeeModalCard" class="modal-content relative w-96 p-6 rounded-2xl shadow-2xl bg-[#262B32] transform scale-90 opacity-0 transition-all duration-300">
                <h2 class="text-2xl font-bold text-red-500 mb-4">Add Employee</h2>
                <form method="POST" action="{{ route('admin.employees.store') }}" id="employeeForm">
                    @csrf
                    <input type="hidden" name="_method" id="employee_hidden_method" value="POST">
                    <input type="hidden" name="employee_id" id="employee_id">
                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Name</label>
                        <input type="text" name="name" id="employee_name" required
                               class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500"
                               placeholder="Enter employee name">
                    </div>
                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Email</label>
                        <input type="email" name="email" id="employee_email" required
                               class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500"
                               placeholder="Enter employee email">
                    </div>
                    <div class="mb-4">
    <label class="block font-semibold mb-1">Role</label>
    <select name="role" id="employee_role" required
            class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
        <option value="" disabled selected>Select role</option>
        <option value="booking_officer">Booking Officer</option>
        <option value="fleet_assistant">Fleet Assistant</option>
    </select>
</div>

                    <div class="mb-4" id="employee_password_field">
                        <label class="block font-semibold mb-1">Password</label>
                        <input type="password" name="password" id="employee_password"
                               class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500"
                               placeholder="Enter password">
                    </div>
                    <div class="flex justify-end mt-6 gap-3">
                        <button type="button" id="closeEmployeeModalBtn" class="cursor-pointer px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-lg text-white transition-all duration-200">Cancel</button>
                        <button type="submit" id="submitEmployeeBtn" class="cursor-pointer px-4 py-2 bg-red-700 hover:bg-red-500 rounded-lg text-white transition-all duration-200">Save</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- DELETE MODAL --}}
        <div id="deleteEmployeeModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" id="deleteEmployeeBackdrop"></div>
            <div class="modal-content relative w-96 p-6 rounded-2xl shadow-2xl bg-[#262B32] transform scale-90 opacity-0 transition-all duration-300">
                <h2 class="text-2xl font-bold text-red-500 mb-4">Confirm Delete</h2>
                <p class="mb-4 text-white">Enter your password to confirm deletion of <span id="deleteEmployeeName" class="font-semibold"></span>.</p>
                <form method="POST" id="deleteEmployeeForm">
                    @csrf
                    @method('DELETE')
                    <input type="password" name="admin_password" required
                           placeholder="Enter your password"
                           class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500 mb-4">
                    <div class="flex justify-end gap-3">
                        <button type="button" id="cancelDeleteEmployeeBtn" class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-lg text-white transition-all duration-200">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-red-700 hover:bg-red-500 rounded-lg text-white transition-all duration-200">Delete</button>
                    </div>
                </form>
            </div>
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
        this.initializeEvents();
    }

    initializeEvents() {
        document.querySelector('[data-action="add-employee"]').addEventListener('click', () => this.openModal());
        document.getElementById('closeEmployeeModalBtn').addEventListener('click', () => this.closeModal());
        this.backdrop.addEventListener('click', () => this.closeModal());

        document.querySelectorAll('.edit-employee-btn').forEach(btn => {
            btn.addEventListener('click', () => this.openEditModal(btn.dataset.id, btn.dataset.name, btn.dataset.email, btn.dataset.role));
        });
    }

    openModal() {
        this.resetForm();
        document.querySelector('#employeeModalCard h2').textContent = 'Add Employee';
        document.getElementById('employee_role').value = '';
        document.getElementById('employee_password_field').style.display = 'block';
        this.form.action = '{{ route("admin.employees.store") }}';
        this.form.querySelector('#employee_hidden_method').value = 'POST';
        this.showModal();
    }

    openEditModal(id, name, email, role) {
    this.resetForm();
    document.querySelector('#employeeModalCard h2').textContent = 'Edit Employee';
    document.getElementById('employee_password_field').style.display = 'none';
    this.form.action = '{{ route("admin.employees.update", ":id") }}'.replace(':id', id);
    this.form.querySelector('#employee_hidden_method').value = 'PUT';
    document.getElementById('employee_id').value = id;
    document.getElementById('employee_name').value = name;
    document.getElementById('employee_email').value = email;
    document.getElementById('employee_role').value = role || '';
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
        document.getElementById('employee_id').value = '';
        document.getElementById('employee_password_field').style.display = 'block';
    }
}

class DeleteEmployeeModal {
    constructor() {
        this.modal = document.getElementById('deleteEmployeeModal');
        this.modalCard = this.modal.querySelector('.modal-content');
        this.backdrop = document.getElementById('deleteEmployeeBackdrop');
        this.nameSpan = document.getElementById('deleteEmployeeName');
        this.form = document.getElementById('deleteEmployeeForm');
        this.initializeEvents();
    }

    initializeEvents() {
        document.querySelectorAll('.delete-employee-btn').forEach(btn => {
            btn.addEventListener('click', () => this.openModal(btn.dataset.id, btn.dataset.name));
        });
        document.getElementById('cancelDeleteEmployeeBtn').addEventListener('click', () => this.closeModal());
        this.backdrop.addEventListener('click', () => this.closeModal());
    }

    openModal(id, name) {
        this.nameSpan.textContent = name;
        this.form.action = '{{ route("admin.employees.destroy", ":id") }}'.replace(':id', id);
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
        }, 300);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new EmployeeModal();
    new DeleteEmployeeModal();

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('#employeeTable tr').forEach(row => {
                const name = row.cells[0]?.textContent.toLowerCase() || '';
                const email = row.cells[1]?.textContent.toLowerCase() || '';
                row.style.display = (name.includes(term) || email.includes(term)) ? '' : 'none';
            });
        });
    }
});
</script>
@endsection
