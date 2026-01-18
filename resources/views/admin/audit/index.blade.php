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

    .dark #sidebar nav a:not(.bg-red-600\/60) {
        color: #1e293b;
    }

    #sidebar nav a.bg-red-600\/60 {
        color: #ffffff !important;
    }

    #sidebar nav a:hover {
        background-color: rgba(220, 38, 38, 0.4) !important;
    }

    #sidebar nav a.bg-red-600\/60 {
        background-color: rgba(220, 38, 38) !important;
    }

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

    /* Enhanced Action type badges with gradient pills */
    .badge-create,
    .badge-update,
    .badge-delete,
    .badge-login,
    .badge-logout {
        display: inline-block;
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        font-weight: 600;
        font-size: 0.875rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .badge-create {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
    }

    .badge-update {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #ffffff;
        box-shadow: 0 2px 4px rgba(245, 158, 11, 0.3);
    }

    .badge-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #ffffff;
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
    }

    .badge-login {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: #ffffff;
        box-shadow: 0 2px 4px rgba(139, 92, 246, 0.3);
    }

    .badge-logout {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: #ffffff;
        box-shadow: 0 2px 4px rgba(107, 114, 128, 0.3);
    }

    /* Light mode adjustments */
    .dark .badge-create {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        box-shadow: 0 2px 4px rgba(5, 150, 105, 0.4);
    }

    .dark .badge-update {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        box-shadow: 0 2px 4px rgba(217, 119, 6, 0.4);
    }

    .dark .badge-delete {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        box-shadow: 0 2px 4px rgba(220, 38, 38, 0.4);
    }

    .dark .badge-login {
        background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
        box-shadow: 0 2px 4px rgba(124, 58, 237, 0.4);
    }

    /* Hover effect for badges */
    .badge-create:hover,
    .badge-update:hover,
    .badge-delete:hover,
    .badge-login:hover,
    .badge-logout:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .actions-menu.dropup {
        bottom: 100%;
        top: auto;
        margin-bottom: 0.5rem;
        margin-top: 0;
    }

    table {
        overflow: visible !important;
    }

    tbody {
        overflow: visible !important;
    }

    .actions-menu {
        z-index: 9999;
    }

    /* Export button styling */
    .export-btn {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
    }

    .export-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.4);
    }
</style>

<div class="flex min-h-screen text-white transition-colors duration-500">

    @include('admin.layout.sidebar')
    
    <main class="min-h-screen transition-all duration-300 p-8" style="margin-left: 18rem; width: calc(100% - 18rem);">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-red-500 drop-shadow-lg">Audit Logs</h1>
            <div class="flex items-center space-x-4">
                {{-- Export Button --}}
                <a href="{{ route('admin.audit.export', request()->only(['action', 'module', 'date', 'search'])) }}" 
                   class="export-btn flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export CSV
                </a>

                {{-- Theme Toggle --}}
                <button id="theme-toggle" class="flex items-center gap-2 bg-black/30 backdrop-blur-xl p-2 rounded-lg hover:bg-[#998282] transition-all duration-300 cursor-pointer">
                    <img id="theme-icon" src="{{ asset('assets/moon.png') }}" class="w-6 h-6 transition-transform duration-500">
                    <span class="font-medium text-white">Dark Mode</span>
                </button>
            </div>
        </div>

        <div class="flex justify-between items-center mb-6 gap-4">
            <input type="text" placeholder="Search logs..." id="searchInput"
                   class="w-80 p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none 
                   focus:ring-2 focus:ring-red-500 transition-all duration-300">

            <div class="flex gap-3">
                <select id="actionFilter" class="p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500 cursor-pointer">
                    <option value="">All Actions</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                            {{ ucfirst($action) }}
                        </option>
                    @endforeach
                </select>

                <select id="moduleFilter" class="p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500 cursor-pointer">
                    <option value="">All Modules</option>
                    @foreach($modules as $module)
                        <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>
                            {{ $module }}
                        </option>
                    @endforeach
                </select>

                <input type="date" id="dateFilter" value="{{ request('date') }}" class="p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
            </div>
        </div>

        <div class="rounded-2xl shadow-2xl backdrop-blur-xl overflow-visible">
            <table class="w-full text-left dark-table overflow-visible">
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
                    @forelse($logs as $log)
                        <tr class="border-b border-white/10 hover:bg-white/10 transition-all audit-row" 
                            data-action="{{ $log->action }}" 
                            data-module="{{ $log->module }}"
                            data-date="{{ $log->created_at->format('Y-m-d') }}">
                            <td class="p-4">{{ $log->id }}</td>
                            <td class="p-4">{{ $log->user_name }}</td>
                            <td class="p-4">
                                <span class="{{ $log->badge_class }} px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ strtoupper($log->action) }}
                                </span>
                            </td>
                            <td class="p-4">{{ $log->module }}</td>
                            <td class="p-4">{{ $log->description }}</td>
                            <td class="p-4">{{ $log->formatted_timestamp }}</td>
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
                                        <button class="view-btn flex items-center gap-3 w-full px-3 py-2 text-white hover:bg-white/5" data-id="{{ $log->id }}">
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
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-400">No audit logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $logs->appends(request()->query())->links() }}
            </div>
        @endif

        {{-- VIEW DETAILS MODAL --}}
        <div id="viewModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" id="viewBackdrop"></div>
            <div id="viewModalCard" class="modal-content relative w-[600px] max-h-[85vh] overflow-y-auto p-6 rounded-2xl shadow-2xl bg-[#262B32] transform scale-90 opacity-0 transition-all duration-300">
                <h2 class="text-2xl font-bold text-red-500 mb-4">Log Details</h2>
                <div id="modalContent" class="space-y-3 text-white">
                    <!-- Content will be loaded dynamically -->
                </div>
                <div class="flex justify-end mt-6">
                    <button type="button" id="closeViewModalBtn" class="cursor-pointer px-4 py-2 bg-red-700 hover:bg-red-500 rounded-lg text-white transition-all duration-200">Close</button>
                </div>
            </div>
        </div>

    </main>
</div>

<script>
const csrfToken = '{{ csrf_token() }}';

document.addEventListener('DOMContentLoaded', () => {
    const auditTable = document.getElementById('auditTable');
    const allRows = auditTable.querySelectorAll('.audit-row');
    const searchInput = document.getElementById('searchInput');
    const actionFilter = document.getElementById('actionFilter');
    const moduleFilter = document.getElementById('moduleFilter');
    const dateFilter = document.getElementById('dateFilter');

    // Filter functionality with URL updates
    searchInput.addEventListener('input', filterTable);
    actionFilter.addEventListener('change', () => updateURLAndFilter());
    moduleFilter.addEventListener('change', () => updateURLAndFilter());
    dateFilter.addEventListener('change', () => updateURLAndFilter());

    function updateURLAndFilter() {
        const params = new URLSearchParams(window.location.search);
        
        if (actionFilter.value) params.set('action', actionFilter.value);
        else params.delete('action');
        
        if (moduleFilter.value) params.set('module', moduleFilter.value);
        else params.delete('module');
        
        if (dateFilter.value) params.set('date', dateFilter.value);
        else params.delete('date');
        
        if (searchInput.value) params.set('search', searchInput.value);
        else params.delete('search');
        
        const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.history.pushState({}, '', newUrl);
        
        filterTable();
    }

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedAction = actionFilter.value.toLowerCase();
        const selectedModule = moduleFilter.value.toLowerCase();
        const selectedDate = dateFilter.value;

        allRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            const rowAction = row.dataset.action;
            const rowModule = row.dataset.module.toLowerCase();
            const rowDate = row.dataset.date;

            const matchesSearch = rowText.includes(searchTerm);
            const matchesAction = !selectedAction || rowAction === selectedAction;
            const matchesModule = !selectedModule || rowModule === selectedModule;
            const matchesDate = !selectedDate || rowDate === selectedDate;

            row.style.display = (matchesSearch && matchesAction && matchesModule && matchesDate) ? '' : 'none';
        });
    }

    // Actions menu toggles
    document.querySelectorAll('.actions-toggle').forEach(toggle => {
        const menu = toggle.nextElementSibling;
        toggle.addEventListener('click', (e) => {
            e.stopPropagation();
            document.querySelectorAll('.actions-menu').forEach(m => {
                if (m !== menu) {
                    m.classList.add('hidden');
                    m.classList.remove('dropup');
                }
            });
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

    document.addEventListener('click', () => {
        document.querySelectorAll('.actions-menu').forEach(m => m.classList.add('hidden'));
    });

    // View Details Modal
    const viewModal = document.getElementById('viewModal');
    const viewModalCard = document.getElementById('viewModalCard');
    const viewBackdrop = document.getElementById('viewBackdrop');
    const closeViewBtn = document.getElementById('closeViewModalBtn');
    const modalContent = document.getElementById('modalContent');

    function openViewModal(logId) {
        fetch(`/admin/audit-logs/${logId}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(r => r.json())
        .then(response => {
            const log = response.log;
            
            let html = `
                <div class="flex justify-between border-b border-white/10 pb-2">
                    <span class="font-semibold">ID:</span>
                    <span>${log.id}</span>
                </div>
                <div class="flex justify-between border-b border-white/10 pb-2">
                    <span class="font-semibold">User:</span>
                    <span>${log.user}</span>
                </div>
                <div class="flex justify-between border-b border-white/10 pb-2">
                    <span class="font-semibold">Action:</span>
                    <span class="${log.badge_class} px-2 py-1 rounded text-xs">${log.action}</span>
                </div>
                <div class="flex justify-between border-b border-white/10 pb-2">
                    <span class="font-semibold">Module:</span>
                    <span>${log.module}</span>
                </div>
                <div class="flex justify-between border-b border-white/10 pb-2">
                    <span class="font-semibold">Description:</span>
                    <span>${log.description}</span>
                </div>
                <div class="flex justify-between border-b border-white/10 pb-2">
                    <span class="font-semibold">Timestamp:</span>
                    <span>${log.timestamp}</span>
                </div>
                <div class="flex justify-between border-b border-white/10 pb-2">
                    <span class="font-semibold">IP Address:</span>
                    <span>${log.ip_address || 'N/A'}</span>
                </div>
            `;

            if (log.user_agent) {
                html += `
                    <div class="flex justify-between border-b border-white/10 pb-2">
                        <span class="font-semibold">User Agent:</span>
                        <span class="text-xs truncate ml-2" title="${log.user_agent}">${log.user_agent}</span>
                    </div>
                `;
            }

            if (log.old_values || log.new_values) {
                html += `
                    <div class="mt-4 pt-4 border-t border-white/10">
                        <h3 class="font-semibold mb-2">Changes:</h3>
                `;

                if (log.old_values) {
                    html += `
                        <div class="mb-3">
                            <p class="text-sm text-gray-400 mb-1">Old Values:</p>
                            <pre class="bg-black/30 p-3 rounded text-xs overflow-x-auto">${JSON.stringify(log.old_values, null, 2)}</pre>
                        </div>
                    `;
                }

                if (log.new_values) {
                    html += `
                        <div class="mb-3">
                            <p class="text-sm text-gray-400 mb-1">New Values:</p>
                            <pre class="bg-black/30 p-3 rounded text-xs overflow-x-auto">${JSON.stringify(log.new_values, null, 2)}</pre>
                        </div>
                    `;
                }

                html += `</div>`;
            }

            modalContent.innerHTML = html;
            
            viewModal.classList.remove('hidden');
            setTimeout(() => {
                viewModalCard.classList.remove('scale-90', 'opacity-0');
                viewModalCard.classList.add('scale-100', 'opacity-100');
            }, 10);
        })
        .catch(err => {
            console.error('Error loading log details:', err);
            alert('Failed to load log details');
        });
    }

    function closeViewModal() {
        viewModalCard.classList.remove('scale-100', 'opacity-100');
        viewModalCard.classList.add('scale-90', 'opacity-0');
        setTimeout(() => viewModal.classList.add('hidden'), 300);
    }

    // View buttons
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const logId = btn.dataset.id;
            openViewModal(logId);
        });
    });

    closeViewBtn.addEventListener('click', closeViewModal);
    viewBackdrop.addEventListener('click', closeViewModal);
});

// Theme toggle
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