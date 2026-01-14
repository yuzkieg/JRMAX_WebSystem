@extends('layouts.app')

@section('content')
@vite('resources/css/app.css')

<style>
    :root {
        --action-view: #3B82F6;
        --action-view-hover: #2563EB;
        --type-create: #10B981;
        --type-update: #F59E0B;
        --type-delete: #EF4444;
        --type-login: #8B5CF6;
    }

    /* Nav links - base styles */
    #sidebar nav a {
        color: #ffffff;
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

    /* Hover states */
    #sidebar nav a:hover {
        background-color: rgba(220, 38, 38, 0.4) !important;
    }

    /* Active state */
    #sidebar nav a.bg-red-600\/60 {
        background-color: rgba(220, 38, 38) !important;
    }

    /* Logo styling */
    #sidebar img[src*="logo.png"] {
        transition: filter 0.3s ease;
    }

    .dark #sidebar img[src*="logo.png"] {
        filter: brightness(0.3) saturate(1.5);
    }

    #searchInput {
        background-color: rgba(62, 61, 61, 0.2) !important;
        color: #ffffff !important;
    }

    .dark #searchInput {
        background-color: rgba(119, 119, 119, 0.2) !important;
        color: #000000 !important;
        border-color: #000000 !important;
    }

    .dark #searchInput::placeholder {
        color: #4a4a4a !important;
        opacity: 1;
    }

    /* Action type badges */
    .badge-create {
        background-color: rgba(16, 185, 129, 0.2);
        color: #10B981;
        border: 1px solid #10B981;
    }

    .badge-update {
        background-color: rgba(245, 158, 11, 0.2);
        color: #F59E0B;
        border: 1px solid #F59E0B;
    }

    .badge-delete {
        background-color: rgba(239, 68, 68, 0.2);
        color: #EF4444;
        border: 1px solid #EF4444;
    }

    .badge-login {
        background-color: rgba(139, 92, 246, 0.2);
        color: #8B5CF6;
        border: 1px solid #8B5CF6;
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
            <h1 class="text-3xl font-bold text-red-500 drop-shadow-lg">Audit Logs</h1>
            <div class="flex items-center space-x-4">
                {{-- Theme Toggle --}}
                <button id="theme-toggle" class="flex items-center gap-2 bg-black/30 backdrop-blur-xl p-2 rounded-lg hover:bg-[#998282] transition-all duration-300 cursor-pointer">
                    <img id="theme-icon" src="{{ asset('assets/moon.png') }}" class="w-6 h-6 transition-transform duration-500">
                    <span class="font-medium text-white">Dark Mode</span>
                </button>
            </div>
        </div>

        {{-- FILTERS --}}
        <div class="flex justify-between items-center mb-6 gap-4">
            <input type="text" placeholder="Search logs..." id="searchInput"
                   class="w-80 p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none 
                   focus:ring-2 focus:ring-red-500 transition-all duration-300">

            <div class="flex gap-3">
                <select id="actionFilter" class="p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500 cursor-pointer">
                    <option value="">All Actions</option>
                    <option value="create">Create</option>
                    <option value="update">Update</option>
                    <option value="delete">Delete</option>
                    <option value="login">Login</option>
                </select>

                <input type="date" id="dateFilter" class="p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
            </div>
        </div>

        {{-- AUDIT LOGS TABLE --}}
        <div class="rounded-2xl shadow-2xl backdrop-blur-xl">
            <table class="w-full text-left dark-table">
                <thead class="bg-black/30 text-white uppercase text-sm tracking-wide text-center">
                    <tr>
                        <th class="p-4">ID</th>
                        <th class="p-4">User</th>
                        <th class="p-4">Action</th>
                        <th class="p-4">Module</th>
                        <th class="p-4">Description</th>
                        <th class="p-4">Timestamp</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="auditTable" class="text-white text-center">
                    {{-- Static Log 1 --}}
                    <tr class="border-b border-white/10 hover:bg-white/10 transition-all" data-action="create" data-date="2026-01-15">
                        <td class="p-4">1</td>
                        <td class="p-4">Admin User</td>
                        <td class="p-4">
                            <span class="badge-create px-3 py-1 rounded-full text-xs font-semibold">CREATE</span>
                        </td>
                        <td class="p-4">Employees</td>
                        <td class="p-4">Created new employee: John Doe</td>
                        <td class="p-4">2026-01-15 09:30:15</td>
                        <td class="p-4 text-center">
                            <div class="relative inline-block">
                                <button type="button" class="actions-toggle p-2 rounded-full hover:bg-white/10 focus:outline-none">
                                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                        <circle cx="5" cy="12" r="1.5" />
                                        <circle cx="12" cy="12" r="1.5" />
                                        <circle cx="19" cy="12" r="1.5" />
                                    </svg>
                                </button>
                                <div class="actions-menu hidden absolute right-0 mt-2 w-40 bg-[#262B32] rounded-lg shadow-xl z-50 border border-white/10">
                                    <button class="view-btn flex items-center gap-3 w-full px-3 py-2 text-white hover:bg-white/5">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span>View Details</span>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>

                    {{-- Static Log 2 --}}
                    <tr class="border-b border-white/10 hover:bg-white/10 transition-all" data-action="update" data-date="2026-01-15">
                        <td class="p-4">2</td>
                        <td class="p-4">Fleet Manager</td>
                        <td class="p-4">
                            <span class="badge-update px-3 py-1 rounded-full text-xs font-semibold">UPDATE</span>
                        </td>
                        <td class="p-4">Bookings</td>
                        <td class="p-4">Updated booking status to Confirmed</td>
                        <td class="p-4">2026-01-15 10:15:42</td>
                        <td class="p-4 text-center">
                            <div class="relative inline-block">
                                <button type="button" class="actions-toggle p-2 rounded-full hover:bg-white/10 focus:outline-none">
                                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                        <circle cx="5" cy="12" r="1.5" />
                                        <circle cx="12" cy="12" r="1.5" />
                                        <circle cx="19" cy="12" r="1.5" />
                                    </svg>
                                </button>
                                <div class="actions-menu hidden absolute right-0 mt-2 w-40 bg-[#262B32] rounded-lg shadow-xl z-50 border border-white/10">
                                    <button class="view-btn flex items-center gap-3 w-full px-3 py-2 text-white hover:bg-white/5">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span>View Details</span>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>

                    {{-- Static Log 3 --}}
                    <tr class="border-b border-white/10 hover:bg-white/10 transition-all" data-action="delete" data-date="2026-01-14">
                        <td class="p-4">3</td>
                        <td class="p-4">Admin User</td>
                        <td class="p-4">
                            <span class="badge-delete px-3 py-1 rounded-full text-xs font-semibold">DELETE</span>
                        </td>
                        <td class="p-4">Drivers</td>
                        <td class="p-4">Deleted driver: Mark Wilson</td>
                        <td class="p-4">2026-01-14 16:45:22</td>
                        <td class="p-4 text-center">
                            <div class="relative inline-block">
                                <button type="button" class="actions-toggle p-2 rounded-full hover:bg-white/10 focus:outline-none">
                                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                        <circle cx="5" cy="12" r="1.5" />
                                        <circle cx="12" cy="12" r="1.5" />
                                        <circle cx="19" cy="12" r="1.5" />
                                    </svg>
                                </button>
                                <div class="actions-menu hidden absolute right-0 mt-2 w-40 bg-[#262B32] rounded-lg shadow-xl z-50 border border-white/10">
                                    <button class="view-btn flex items-center gap-3 w-full px-3 py-2 text-white hover:bg-white/5">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span>View Details</span>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>

                    {{-- Static Log 4 --}}
                    <tr class="border-b border-white/10 hover:bg-white/10 transition-all" data-action="login" data-date="2026-01-14">
                        <td class="p-4">4</td>
                        <td class="p-4">Booking Officer</td>
                        <td class="p-4">
                            <span class="badge-login px-3 py-1 rounded-full text-xs font-semibold">LOGIN</span>
                        </td>
                        <td class="p-4">Authentication</td>
                        <td class="p-4">User logged into the system</td>
                        <td class="p-4">2026-01-14 08:00:12</td>
                        <td class="p-4 text-center">
                            <div class="relative inline-block">
                                <button type="button" class="actions-toggle p-2 rounded-full hover:bg-white/10 focus:outline-none">
                                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                        <circle cx="5" cy="12" r="1.5" />
                                        <circle cx="12" cy="12" r="1.5" />
                                        <circle cx="19" cy="12" r="1.5" />
                                    </svg>
                                </button>
                                <div class="actions-menu hidden absolute right-0 mt-2 w-40 bg-[#262B32] rounded-lg shadow-xl z-50 border border-white/10">
                                    <button class="view-btn flex items-center gap-3 w-full px-3 py-2 text-white hover:bg-white/5">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span>View Details</span>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>

                    {{-- Static Log 5 --}}
                    <tr class="border-b border-white/10 hover:bg-white/10 transition-all" data-action="create" data-date="2026-01-13">
                        <td class="p-4">5</td>
                        <td class="p-4">Admin User</td>
                        <td class="p-4">
                            <span class="badge-create px-3 py-1 rounded-full text-xs font-semibold">CREATE</span>
                        </td>
                        <td class="p-4">Vehicles</td>
                        <td class="p-4">Added new vehicle: Toyota Hiace 2024</td>
                        <td class="p-4">2026-01-13 14:20:55</td>
                        <td class="p-4 text-center">
                            <div class="relative inline-block">
                                <button type="button" class="actions-toggle p-2 rounded-full hover:bg-white/10 focus:outline-none">
                                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                        <circle cx="5" cy="12" r="1.5" />
                                        <circle cx="12" cy="12" r="1.5" />
                                        <circle cx="19" cy="12" r="1.5" />
                                    </svg>
                                </button>
                                <div class="actions-menu hidden absolute right-0 mt-2 w-40 bg-[#262B32] rounded-lg shadow-xl z-50 border border-white/10">
                                    <button class="view-btn flex items-center gap-3 w-full px-3 py-2 text-white hover:bg-white/5">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span>View Details</span>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- VIEW DETAILS MODAL --}}
        <div id="viewModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" id="viewBackdrop"></div>
            <div id="viewModalCard" class="modal-content relative w-[500px] p-6 rounded-2xl shadow-2xl bg-[#262B32] transform scale-90 opacity-0 transition-all duration-300">
                <h2 class="text-2xl font-bold text-red-500 mb-4">Log Details</h2>
                <div class="space-y-3 text-white">
                    <div class="flex justify-between border-b border-white/10 pb-2">
                        <span class="font-semibold">ID:</span>
                        <span>1</span>
                    </div>
                    <div class="flex justify-between border-b border-white/10 pb-2">
                        <span class="font-semibold">User:</span>
                        <span>Admin User</span>
                    </div>
                    <div class="flex justify-between border-b border-white/10 pb-2">
                        <span class="font-semibold">Action:</span>
                        <span class="badge-create px-2 py-1 rounded text-xs">CREATE</span>
                    </div>
                    <div class="flex justify-between border-b border-white/10 pb-2">
                        <span class="font-semibold">Module:</span>
                        <span>Employees</span>
                    </div>
                    <div class="flex justify-between border-b border-white/10 pb-2">
                        <span class="font-semibold">Description:</span>
                        <span>Created new employee: John Doe</span>
                    </div>
                    <div class="flex justify-between border-b border-white/10 pb-2">
                        <span class="font-semibold">Timestamp:</span>
                        <span>2026-01-15 09:30:15</span>
                    </div>
                    <div class="flex justify-between border-b border-white/10 pb-2">
                        <span class="font-semibold">IP Address:</span>
                        <span>192.168.1.100</span>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="button" id="closeViewModalBtn" class="cursor-pointer px-4 py-2 bg-red-700 hover:bg-red-500 rounded-lg text-white transition-all duration-200">Close</button>
                </div>
            </div>
        </div>

    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const auditTable = document.getElementById('auditTable');
    const allRows = auditTable.querySelectorAll('tr');
    const searchInput = document.getElementById('searchInput');
    const actionFilter = document.getElementById('actionFilter');
    const dateFilter = document.getElementById('dateFilter');

    // Search functionality
    searchInput.addEventListener('input', filterTable);
    actionFilter.addEventListener('change', filterTable);
    dateFilter.addEventListener('change', filterTable);

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedAction = actionFilter.value.toLowerCase();
        const selectedDate = dateFilter.value;

        allRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            const rowAction = row.dataset.action;
            const rowDate = row.dataset.date;

            const matchesSearch = rowText.includes(searchTerm);
            const matchesAction = !selectedAction || rowAction === selectedAction;
            const matchesDate = !selectedDate || rowDate === selectedDate;

            row.style.display = (matchesSearch && matchesAction && matchesDate) ? '' : 'none';
        });
    }

    // Actions menu toggles
    document.querySelectorAll('.actions-toggle').forEach(toggle => {
        const menu = toggle.nextElementSibling;
        toggle.addEventListener('click', (e) => {
            e.stopPropagation();
            // Close other menus
            document.querySelectorAll('.actions-menu').forEach(m => {
                if (m !== menu) {
                    m.classList.add('hidden');
                    m.classList.remove('dropup');
                }
            });
            // Toggle current menu
            if (menu) {
                menu.classList.toggle('hidden');
                
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

    // Close menus when clicking outside
    document.addEventListener('click', () => {
        document.querySelectorAll('.actions-menu').forEach(m => m.classList.add('hidden'));
    });

    // View Details Modal
    const viewModal = document.getElementById('viewModal');
    const viewModalCard = document.getElementById('viewModalCard');
    const viewBackdrop = document.getElementById('viewBackdrop');
    const closeViewBtn = document.getElementById('closeViewModalBtn');

    function openViewModal() {
        viewModal.classList.remove('hidden');
        setTimeout(() => {
            viewModalCard.classList.remove('scale-90', 'opacity-0');
            viewModalCard.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeViewModal() {
        viewModalCard.classList.remove('scale-100', 'opacity-100');
        viewModalCard.classList.add('scale-90', 'opacity-0');
        setTimeout(() => viewModal.classList.add('hidden'), 300);
    }

    // View buttons
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', openViewModal);
    });

    closeViewBtn.addEventListener('click', closeViewModal);
    viewBackdrop.addEventListener('click', closeViewModal);
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
</script>

@endsection