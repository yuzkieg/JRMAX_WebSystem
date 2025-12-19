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
    --nav-tab-text: #ffffff;
}

#sidebar nav a {
    color: var(--nav-tab-text) !important;
}

.action-edit {
    background-color: var(--action-edit) !important;
    color: #fff !important;
    padding: .5rem .9rem;
    border-radius: .5rem;
}
.action-edit:hover { background-color: var(--action-edit-hover) !important; }

.action-delete {
    background-color: var(--action-delete) !important;
    color: #fff !important;
    padding: .5rem .9rem;
    border-radius: .5rem;
}
.action-delete:hover { background-color: var(--action-delete-hover) !important; }

.status-pill {
    display: inline-block;
    padding: .25rem .75rem;
    border-radius: 9999px;
    font-weight: 700;
    font-size: .85rem;
}

.status-pill.pending { background: var(--status-pending); color: #000; }
.status-pill.confirmed { background: var(--status-confirmed); color: #fff; }
.status-pill.ongoing { background: var(--status-ongoing); color: #fff; }
.status-pill.completed { background: var(--status-completed); color: #fff; }
.status-pill.cancelled { background: var(--status-cancelled); color: #fff; }

.stat-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.02));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 0.75rem;
    padding: 1.5rem;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
    cursor: pointer;
}

.stat-card:hover {
    border-color: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.stat-card .stat-value {
    font-size: 1.875rem;
    font-weight: bold;
    color: #fff;
}

.stat-card .stat-label {
    font-size: 0.875rem;
    color: #9CA3AF;
    margin-top: 0.5rem;
}

.stat-card .stat-trend {
    font-size: 0.75rem;
    margin-top: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.stat-card .trend-up { color: #10B981; }
.stat-card .trend-down { color: #EF4444; }
.stat-card .trend-neutral { color: #9CA3AF; }

.revenue-chart-container {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 0.75rem;
    padding: 1.5rem;
    backdrop-filter: blur(10px);
    margin-bottom: 1.5rem;
}

.revenue-chart-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #fff;
    margin-bottom: 1rem;
}

.revenue-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.revenue-tab {
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #9CA3AF;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    font-weight: 500;
}

.revenue-tab:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
}

.revenue-tab.active {
    background: #EF4444;
    color: #fff;
    border-color: #EF4444;
}

.revenue-chart {
    height: 300px;
    width: 100%;
    position: relative;
}

.revenue-data-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1.5rem;
}

.revenue-data-item {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 0.5rem;
    padding: 1rem;
}

.revenue-data-label {
    font-size: 0.75rem;
    color: #9CA3AF;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.25rem;
}

.revenue-data-value {
    font-size: 1.25rem;
    font-weight: 600;
    color: #fff;
}

.revenue-data-delta {
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    margin-top: 0.25rem;
}

.delta-positive { color: #10B981; }
.delta-negative { color: #EF4444; }
.delta-neutral { color: #9CA3AF; }

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #ffffff;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border-radius: 0.75rem;
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #ffffff;
    outline: none;
    transition: all 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: rgba(255, 255, 255, 0.3);
    box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
}

.form-group input::placeholder,
.form-group textarea::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

.vehicle-checkbox {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 0.5rem;
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.vehicle-checkbox:hover { background: rgba(255, 255, 255, 0.1); }

.vehicle-checkbox input[type="checkbox"] {
    width: auto;
    margin-right: 0.5rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 0.75rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.btn-primary {
    background: #EF4444;
    color: #fff;
}

.btn-primary:hover {
    background: #DC2626;
    transform: scale(1.05);
}

.btn-secondary {
    background: #3B82F6;
    color: #fff;
}

.btn-secondary:hover {
    background: #2563EB;
}

.btn-group {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}

.error-message {
    background: rgba(239, 68, 68, 0.2);
    border: 1px solid #EF4444;
    color: #FCA5A5;
    padding: 0.75rem;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
}

.success-message {
    background: rgba(16, 185, 129, 0.2);
    border: 1px solid #10B981;
    color: #6EE7B7;
    padding: 0.75rem;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
}

.detail-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 0.75rem;
    padding: 1.5rem;
}

.detail-label {
    font-size: 0.875rem;
    color: #9CA3AF;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
}

.detail-value {
    font-size: 1rem;
    color: #fff;
    font-weight: 600;
}

.vehicle-item {
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.3);
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 0.75rem;
}

/* Modal-like view sections (create/edit/show) */
.view-section { display: none; }
.view-section.active {
    display: flex;
    position: fixed;
    inset: 0;
    z-index: 60;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,0.6);
    padding: 2rem;
}
/* keep index view as normal page content */
#indexView.view-section.active {
    display: block;
    position: static;
    inset: auto;
    z-index: auto;
    align-items: stretch;
    justify-content: stretch;
    background: transparent;
    padding: 0;
}
.view-section .modal-card {
    width: 100%;
    max-width: 1100px;
    max-height: 90vh;
    overflow: auto;
    border-radius: 1rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, rgba(38,43,50,0.95), rgba(22,24,27,0.95));
    border: 1px solid rgba(255,255,255,0.06);
    box-shadow: 0 10px 30px rgba(2,6,23,0.6);
}

/* Table Actions */
td .action-edit, td .action-delete { 
    display: inline-flex; 
    align-items: center; 
    justify-content: center; 
    gap: .35rem; 
}

/* Pagination styling - FIXED */
.pagination-wrapper {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 0.75rem;
    padding: 0.75rem 1.5rem;
    backdrop-filter: blur(10px);
    display: inline-flex;
}

.pagination { 
    display: flex; 
    gap: .5rem; 
    list-style: none; 
    padding: 0;
    margin: 0;
    align-items: center;
}

.pagination .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 0.75rem;
    min-width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    background: rgba(255, 255, 255, 0.05);
    color: #fff;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.08);
    text-decoration: none;
}

.pagination .page-link:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.15);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #fff;
    border-color: rgba(0, 0, 0, 0.2);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    font-weight: 600;
}

.pagination .page-item.disabled .page-link {
    background: rgba(255, 255, 255, 0.02);
    color: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.05);
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.pagination .prev-next-btn {
    padding: 0.5rem 1rem;
    gap: 0.5rem;
    background: rgba(239, 68, 68, 0.1);
    border-color: rgba(239, 68, 68, 0.2);
    min-width: auto;
}

.pagination .prev-next-btn:hover {
    background: rgba(239, 68, 68, 0.2);
    border-color: rgba(239, 68, 68, 0.3);
}

/* Mobile responsive adjustments */
@media (max-width: 640px) {
    .pagination-wrapper {
        padding: 0.5rem;
    }
    
    .pagination .page-link {
        min-width: 2.25rem;
        height: 2.25rem;
        padding: 0.5rem;
        font-size: 0.8125rem;
    }
    
    .pagination .prev-next-btn {
        padding: 0.5rem;
    }
    
    .pagination .prev-next-btn span {
        display: none;
    }
    
    .revenue-data-grid {
        grid-template-columns: 1fr;
    }
}

/* Success and Error Message Animations */
.animate-slidedown {
    animation: slidedown 0.5s ease-out;
}

@keyframes slidedown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Ensure messages respect theme */
.success-message {
    background: rgba(16, 185, 129, 0.2) !important;
    border-color: #10B981 !important;
    color: #6EE7B7 !important;
}

.error-message {
    background: rgba(239, 68, 68, 0.2) !important;
    border-color: #EF4444 !important;
    color: #FCA5A5 !important;
}

/* Booking officer specific styles */
.booking-status-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-weight: 600;
}

.status-badge-pending { background: #F59E0B; color: #000; }
.status-badge-confirmed { background: #3B82F6; color: #fff; }
.status-badge-ongoing { background: #8B5CF6; color: #fff; }
.status-badge-completed { background: #10B981; color: #fff; }
.status-badge-cancelled { background: #EF4444; color: #fff; }

/* Action buttons alignment - FIXED */
.actions-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 3rem;
}

.quick-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
}

.action-btn {
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.85rem;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.action-btn.view {
    background: #3B82F6;
    color: white;
}

.action-btn.view:hover {
    background: #2563EB;
}

.quick-action-btn {
    padding: 0.5rem 0.8rem;
    border-radius: 0.5rem;
    font-size: 0.85rem;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.quick-action-btn.confirm {
    background: #10B981;
    color: white;
}

.quick-action-btn.confirm:hover {
    background: #059669;
}

.quick-action-btn.cancel {
    background: #EF4444;
    color: white;
}

.quick-action-btn.cancel:hover {
    background: #DC2626;
}

.quick-action-btn.complete {
    background: #8B5CF6;
    color: white;
}

.quick-action-btn.complete:hover {
    background: #7C3AED;
}

/* Table cell alignment */
td {
    vertical-align: middle !important;
}

/* Ensure table headers and cells have consistent alignment */
th.text-center, td.text-center {
    text-align: center !important;
}

/* Loading spinner */
.loading-spinner {
    display: inline-block;
    width: 1rem;
    height: 1rem;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: #fff;
    animation: spin 1s ease-in-out infinite;
    margin-right: 0.5rem;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Chart tooltip */
.chart-tooltip {
    background: rgba(0, 0, 0, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 0.5rem;
    padding: 0.5rem;
    color: #fff;
    font-size: 0.75rem;
}

/* Refresh button */
.refresh-btn {
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.3);
    color: #3B82F6;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
}

.refresh-btn:hover {
    background: rgba(59, 130, 246, 0.2);
    border-color: rgba(59, 130, 246, 0.5);
}

.refresh-btn.spinning svg {
    animation: spin 1s linear infinite;
}
</style>

<div class="flex min-h-screen bg-[#1A1F24] text-white transition-colors duration-500" id="dashboard-wrapper">

    {{-- SIDEBAR --}}
    <aside id="sidebar" class="w-64 bg-black/80 h-screen fixed top-0 left-0 shadow-xl border-r border-white/10 backdrop-blur-xl transition-all duration-300 hover:w-72">
        <div class="p-6 flex flex-col items-center">
            <img src="{{ asset('assets/logo.png') }}" class="w-20 h-20 mb-4 transition-all duration-300 hover:scale-105">
            <h2 class="text-xl font-bold tracking-wide text-red-500">BOOKING OFFICER</h2>
        </div>

        @php
            $menuItems = [
                ['name' => 'Booking Management', 'url' => '/employee/bookingdash'],
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
            <h1 id="pageTitle" class="text-3xl font-bold text-red-500 drop-shadow-lg">Booking Management</h1>

            <div class="flex items-center space-x-4">
                {{-- Refresh Revenue Stats --}}
                <button id="refreshRevenueBtn" onclick="refreshRevenueStats()" class="refresh-btn">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Refresh Stats
                </button>

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

        {{-- SUCCESS MESSAGE --}}
        @if ($message = Session::get('success'))
            <div class="success-message border-l-4 rounded-lg p-4 mb-6 flex justify-between items-center bg-green-600/20 text-green-300 border-green-500 animate-slidedown" role="alert">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ $message }}</span>
                </div>
                <button class="close-alert text-green-300 hover:text-green-100" onclick="this.parentElement.style.display='none';">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- ERROR MESSAGE --}}
        @if ($message = Session::get('error'))
            <div class="error-message border-l-4 rounded-lg p-4 mb-6 flex justify-between items-center bg-red-600/20 text-red-300 border-red-500 animate-slidedown" role="alert">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ $message }}</span>
                </div>
                <button class="close-alert text-red-300 hover:text-red-100" onclick="this.parentElement.style.display='none';">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- VALIDATION ERRORS --}}
        @if ($errors->any())
            <div class="error-message border-l-4 rounded-lg p-4 mb-6 bg-red-600/20 text-red-300 border-red-500" role="alert">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-semibold">Validation Errors:</span>
                </div>
                <ul class="list-disc list-inside ml-8 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-red-200">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ========== INDEX VIEW ========== --}}
        <div id="indexView" class="view-section active">
            {{-- REVENUE STATISTICS SECTION --}}
            <div class="revenue-chart-container mb-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="revenue-chart-title">Revenue Overview</h2>
                    <div class="flex items-center gap-3">
                        <div class="revenue-tabs">
                            <button class="revenue-tab active" onclick="switchRevenuePeriod('today')">Today</button>
                            <button class="revenue-tab" onclick="switchRevenuePeriod('monthly')">This Month</button>
                            <button class="revenue-tab" onclick="switchRevenuePeriod('total')">All Time</button>
                        </div>
                    </div>
                </div>

                {{-- REVENUE STATS CARDS --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8" id="revenueStats">
                    <div class="stat-card" onclick="switchRevenuePeriod('today')">
                        <div class="stat-value" id="todayRevenue">₱{{ number_format($stats['todayRevenue'] ?? 0, 2) }}</div>
                        <div class="stat-label">Today's Revenue</div>
                        <div class="stat-trend" id="todayTrend">
                            <span class="trend-neutral">Loading trend...</span>
                        </div>
                    </div>
                    <div class="stat-card" onclick="switchRevenuePeriod('monthly')">
                        <div class="stat-value" id="monthlyRevenue">₱{{ number_format($stats['monthlyRevenue'] ?? 0, 2) }}</div>
                        <div class="stat-label">Monthly Revenue</div>
                        <div class="stat-trend" id="monthlyTrend">
                            <span class="trend-neutral">Loading trend...</span>
                        </div>
                    </div>
                    <div class="stat-card" onclick="switchRevenuePeriod('total')">
                        <div class="stat-value" id="totalRevenue">₱{{ number_format($stats['totalRevenue'] ?? 0, 2) }}</div>
                        <div class="stat-label">Total Revenue</div>
                        <div class="stat-trend" id="totalTrend">
                            <span class="trend-neutral">Loading trend...</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value" id="avgBookingValue">₱{{ number_format($stats['totalRevenue'] > 0 ? $stats['totalRevenue'] / $stats['total'] : 0, 2) }}</div>
                        <div class="stat-label">Avg. Booking Value</div>
                        <div class="stat-trend">
                            <span class="trend-neutral">Per booking</span>
                        </div>
                    </div>
                </div>

                {{-- REVENUE CHART --}}
                <div class="revenue-chart" id="revenueChart">
                    <canvas id="revenueChartCanvas"></canvas>
                </div>

                {{-- ADDITIONAL REVENUE DATA --}}
                <div class="revenue-data-grid" id="additionalRevenueData">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>

            {{-- BOOKING STATISTICS CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="stat-card">
                    <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
                    <div class="stat-label">Total Bookings</div>
                    <div class="stat-trend">
                        @php
                            $yesterdayBookings = 0; // This should come from your database
                            $todayBookings = $stats['total'] ?? 0;
                            $bookingChange = $todayBookings - $yesterdayBookings;
                            $trendClass = $bookingChange > 0 ? 'trend-up' : ($bookingChange < 0 ? 'trend-down' : 'trend-neutral');
                            $trendIcon = $bookingChange > 0 ? '↑' : ($bookingChange < 0 ? '↓' : '→');
                        @endphp
                        <span class="{{ $trendClass }}">{{ $trendIcon }} {{ abs($bookingChange) }} from yesterday</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color: #F59E0B;">{{ $stats['pending'] ?? 0 }}</div>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color: #3B82F6;">{{ $stats['active'] ?? 0 }}</div>
                    <div class="stat-label">Active</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color: #10B981;">{{ $stats['completedBookings'] ?? 0 }}</div>
                    <div class="stat-label">Completed</div>
                </div>
            </div>

            {{-- SEARCH AND ADD BOOKING --}}
            <div class="flex justify-between items-center mb-6">
                <input type="text" placeholder="Search booking ID, client name..."
                       class="w-80 p-3 rounded-xl bg-black/20 text-white placeholder-gray-300 outline-none 
                       focus:ring-2 focus:ring-red-500 transition-all duration-300"
                       id="searchInput">

                <button id="newBookingBtn" onclick="switchView('createView')" class="cursor-pointer px-5 py-2 bg-red-700 hover:bg-red-500 rounded-xl text-white shadow-lg transition-all duration-300 hover:scale-105">
                    + New Booking
                </button>
            </div>

            {{-- BOOKINGS TABLE --}}
            <div class="overflow-hidden rounded-2xl shadow-2xl backdrop-blur-xl">
                <table class="w-full text-left">
                    <thead class="bg-black/30 text-white uppercase text-sm tracking-wide">
                        <tr>
                            <th class="p-4">Booking ID</th>
                            <th class="p-4">Client</th>
                            <th class="p-4">Vehicles</th>
                            <th class="p-4">Check-in</th>
                            <th class="p-4">Check-out</th>
                            <th class="p-4">Total</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="bookingsTable" class="text-white">
                        @forelse($bookings as $booking)
                            <tr class="border-b border-white/10 hover:bg-white/10 transition-all booking-row" data-revenue="{{ $booking->total_price }}">
                                <td class="p-4 font-semibold align-middle">#{{ str_pad($booking->boarding_id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td class="p-4 booking-client align-middle">{{ $booking->client->first_name ?? 'N/A' }} {{ $booking->client->last_name ?? '' }}</td>
                                <td class="p-4 align-middle">
                                    @foreach($booking->vehicles as $bv)
                                        <span class="inline-block px-2 py-1 bg-blue-600/30 text-blue-200 rounded text-xs mr-1 mb-1">
                                            {{ $bv->vehicle->brand ?? 'N/A' }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="p-4 text-sm align-middle">{{ $booking->start_datetime ? $booking->start_datetime->format('M d H:i') : 'N/A' }}</td>
                                <td class="p-4 text-sm align-middle">{{ $booking->end_datetime ? $booking->end_datetime->format('M d H:i') : 'N/A' }}</td>
                                <td class="p-4 font-bold text-green-400 align-middle">₱{{ number_format($booking->total_price, 2) }}</td>
                                <td class="p-4 text-center align-middle">
                                    @php
                                        $statusClass = 'pending';
                                        if ($booking->status_id == 2) $statusClass = 'confirmed';
                                        elseif ($booking->status_id == 3) $statusClass = 'ongoing';
                                        elseif ($booking->status_id == 4) $statusClass = 'completed';
                                        elseif ($booking->status_id == 5) $statusClass = 'cancelled';
                                    @endphp
                                    <span class="status-pill {{ $statusClass }}">{{ $booking->status->status_name ?? 'Unknown' }}</span>
                                </td>
                                <td class="p-4 text-center align-middle">
                                    <div class="actions-container">
                                        <div class="quick-actions">
                                            <button onclick="showBooking({{ $booking->boarding_id }})" 
                                                    class="action-btn view">
                                                View
                                            </button>
                                            @if($booking->status_id == 1) {{-- Pending --}}
                                                <button onclick="updateStatus({{ $booking->boarding_id }}, 2, this)" 
                                                        class="quick-action-btn confirm">
                                                    Confirm
                                                </button>
                                            @elseif($booking->status_id == 2) {{-- Confirmed --}}
                                                <button onclick="updateStatus({{ $booking->boarding_id }}, 3, this)" 
                                                        class="quick-action-btn confirm">
                                                    Start
                                                </button>
                                            @elseif($booking->status_id == 3) {{-- Ongoing --}}
                                                <button onclick="updateStatus({{ $booking->boarding_id }}, 4, this)" 
                                                        class="quick-action-btn complete">
                                                    Complete
                                                </button>
                                            @endif
                                            @if(in_array($booking->status_id, [1, 2, 3])) {{-- Can cancel if pending, confirmed, or ongoing --}}
                                                <button onclick="updateStatus({{ $booking->boarding_id }}, 5, this)" 
                                                        class="quick-action-btn cancel">
                                                    Cancel
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="p-8 text-center text-gray-400 align-middle">No bookings found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION - FIXED ALIGNMENT --}}
            @if($bookings->hasPages())
                <div class="mt-8 flex justify-center">
                    <nav class="pagination-wrapper">
                        <ul class="pagination">
                            {{-- Previous Page Link --}}
                            @if ($bookings->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link prev-next-btn opacity-50 cursor-not-allowed">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                        <span class="hidden sm:inline">Previous</span>
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a href="{{ $bookings->previousPageUrl() }}" class="page-link prev-next-btn hover:bg-red-600/20">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                        <span class="hidden sm:inline">Previous</span>
                                    </a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @if(isset($bookings->links()->elements[0]))
                                @foreach ($bookings->links()->elements[0] as $page => $url)
                                    @if (is_string($page))
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @else
                                        <li class="page-item {{ $bookings->currentPage() == $page ? 'active' : '' }}">
                                            <a href="{{ $url }}" class="page-link {{ $bookings->currentPage() == $page ? 'bg-red-600 text-white' : 'hover:bg-white/10' }}">
                                                {{ $page }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            @endif

                            {{-- Next Page Link --}}
                            @if ($bookings->hasMorePages())
                                <li class="page-item">
                                    <a href="{{ $bookings->nextPageUrl() }}" class="page-link prev-next-btn hover:bg-red-600/20">
                                        <span class="hidden sm:inline">Next</span>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link prev-next-btn opacity-50 cursor-not-allowed">
                                        <span class="hidden sm:inline">Next</span>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            @endif
        </div>

        {{-- ========== CREATE/EDIT VIEW ========== --}}
        <div id="createView" class="view-section">
            <div class="modal-card">
                <form id="bookingForm" method="POST" action="/employee/booking">
                    @csrf
                    <input type="hidden" id="formMethod" name="_method" value="POST">
                    <input type="hidden" id="bookingId" name="boarding_id">

                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-red-500 mb-4">Client Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="client_id">Select Client *</label>
                                <select name="client_id" id="client_id" required class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                                    <option value="">-- Choose Client --</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->Editor_id }}">{{ $client->first_name }} {{ $client->last_name }} ({{ $client->email }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="boarding_date">Boarding Date *</label>
                                <input type="date" name="boarding_date" id="boarding_date" required class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-red-500 mb-4">Booking Details</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="start_datetime">Check-in *</label>
                                <input type="datetime-local" name="start_datetime" id="start_datetime" required class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                            </div>

                            <div class="form-group">
                                <label for="end_datetime">Check-out *</label>
                                <input type="datetime-local" name="end_datetime" id="end_datetime" required class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                            </div>

                            <div class="form-group">
                                <label for="pickup_location">Pick-up Location *</label>
                                <input type="text" name="pickup_location" id="pickup_location" required placeholder="e.g., Airport Terminal 1" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                            </div>

                            <div class="form-group">
                                <label for="dropoff_location">Drop-off Location *</label>
                                <input type="text" name="dropoff_location" id="dropoff_location" required placeholder="e.g., Hotel Downtown" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-red-500 mb-4">Vehicles & Driver</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label>Select Vehicle(s) *</label>
                                <div style="max-height: 300px; overflow-y: auto; background: rgba(0,0,0,0.2); padding: 1rem; border-radius: 0.75rem;">
                                    @foreach($vehicles as $vehicle)
                                        <label class="vehicle-checkbox">
                                            <input type="checkbox" name="vehicle_ids[]" value="{{ $vehicle->vehicle_id }}" class="vehicle-checkbox-input">
                                            <span>{{ $vehicle->brand }} {{ $vehicle->model }} - {{ $vehicle->plate_num }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="driver_id">Assign Driver (Optional)</label>
                                <select name="driver_id" id="driver_id" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                                    <option value="">-- No Driver --</option>
                                    @foreach($drivers as $driver)
                                        <option value="{{ $driver->id }}">{{ $driver->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-red-500 mb-4">Pricing & Status</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="form-group">
                                <label for="total_price">Total Price (₱) *</label>
                                <input type="number" name="total_price" id="total_price" required step="0.01" min="0" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                            </div>

                            <div class="form-group">
                                <label for="status_id">Booking Status *</label>
                                <select name="status_id" id="status_id" required class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                                    <option value="">-- Select Status --</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->status_id }}">{{ $status->status_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="payment_method">Payment Method</label>
                                <select name="payment_method" id="payment_method" class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500">
                                    <option value="cash">Cash</option>
                                    <option value="credit_card">Credit Card</option>
                                    <option value="online_transfer">Online Transfer</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <div class="form-group">
                            <label for="special_requests">Special Requests / Notes</label>
                            <textarea name="special_requests" id="special_requests" rows="4" placeholder="Any special requests or notes about this booking..." class="w-full p-3 rounded-xl bg-black/20 text-white outline-none focus:ring-2 focus:ring-red-500"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="switchView('indexView')" class="btn btn-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Create Booking</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========== SHOW VIEW ========== --}}
        <div id="showView" class="view-section">
            <div id="showContent"></div>
        </div>

    </main>
</div>

{{-- Include Chart.js for revenue charts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Global variables
let revenueChart = null;
let currentRevenuePeriod = 'today';

// View switching
function switchView(view) {
    document.querySelectorAll('.view-section').forEach(v => v.classList.remove('active'));
    document.getElementById(view).classList.add('active');
    if (view === 'indexView') {
        // Reset search when switching back to index
        document.getElementById('searchInput').value = '';
        document.querySelectorAll('.booking-row').forEach(row => row.style.display = '');
    }
}

// Show booking details
function showBooking(id) {
    fetch(`/employee/booking/${id}`, { 
        headers: { 
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        } 
    })
    .then(r => {
        if (!r.ok) {
            if (r.status === 401 || r.status === 302) { 
                window.location.href = '/login'; 
            }
            return r.text().then(t => { 
                throw new Error('Failed to load booking: ' + t); 
            });
        }
        const ct = r.headers.get('content-type') || '';
        if (ct.includes('application/json')) return r.json();
        return r.text().then(t => { 
            throw new Error('Unexpected response: ' + t); 
        });
    })
    .then(response => {
        const booking = response.booking || response.data || response;
        
        const statusMap = {
            1: ['Pending', 'status-pill pending'],
            2: ['Confirmed', 'status-pill confirmed'],
            3: ['Ongoing', 'status-pill ongoing'],
            4: ['Completed', 'status-pill completed'],
            5: ['Cancelled', 'status-pill cancelled']
        };
        
        const status = booking.status_id || booking.status?.status_id;
        const [statusText, statusClass] = statusMap[status] || ['Unknown', 'status-pill pending'];
        
        // Format dates
        const formatDate = (dateStr) => {
            if (!dateStr) return 'N/A';
            const date = new Date(dateStr);
            return date.toLocaleString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        };
        
        // Get client information
        const client = booking.client || booking.Client || {};
        const clientName = `${client.first_name || ''} ${client.last_name || ''}`.trim() || 'N/A';
        const clientEmail = client.email || 'N/A';
        const clientPhone = client.phone_number || 'N/A';
        
        // Get driver information
        const driver = booking.driver || booking.Driver || {};
        const driverName = driver.full_name || driver.name || 'Unassigned';
        const driverLicense = driver.license_number || 'N/A';
        const driverEmail = driver.email || 'N/A';
        const driverPhone = driver.phone_number || 'N/A';
        
        // Get vehicles
        const vehicles = booking.vehicles || booking.Vehicles || [];
        
        // Calculate duration
        const startDate = booking.start_datetime ? new Date(booking.start_datetime) : null;
        const endDate = booking.end_datetime ? new Date(booking.end_datetime) : null;
        let duration = { days: 0, hours: 0, totalHours: 0 };
        
        if (startDate && endDate) {
            const diffMs = endDate - startDate;
            const diffHours = diffMs / (1000 * 60 * 60);
            duration.totalHours = Math.round(diffHours);
            duration.days = Math.floor(diffHours / 24);
            duration.hours = Math.round(diffHours % 24);
        }
        
        const html = `
            <div class="modal-card">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-3xl font-bold text-red-500">Booking #${String(booking.boarding_id).padStart(6,'0')}</h2>
                        <div class="text-sm text-gray-400 mt-1">
                            Created: ${formatDate(booking.created_at)}
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="switchView('indexView')" class="px-4 py-2 bg-gray-700 rounded text-white hover:bg-gray-600">Close</button>
                    </div>
                </div>

                <!-- CLIENT & DRIVER INFORMATION -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b border-gray-700">
                    <div class="detail-card">
                        <h3 class="detail-label">Client Information</h3>
                        <div class="space-y-2">
                            <div class="detail-value">${clientName}</div>
                            <div class="text-sm text-gray-400">${clientEmail}</div>
                            <div class="text-sm text-gray-400">${clientPhone}</div>
                            ${client.identification_type ? `
                                <div class="text-xs text-gray-500 mt-2">
                                    ID: ${client.identification_type} - ${client.identification_number || 'N/A'}
                                </div>
                            ` : ''}
                        </div>
                    </div>
                    <div class="detail-card">
                        <h3 class="detail-label">Driver Assignment</h3>
                        <div class="space-y-2">
                            <div class="detail-value">${driverName}</div>
                            ${driverName !== 'Unassigned' ? `
                                <div class="text-sm text-gray-400">License: ${driverLicense}</div>
                                <div class="text-sm text-gray-400">${driverEmail}</div>
                                <div class="text-sm text-gray-400">Phone: ${driverPhone}</div>
                            ` : '<div class="text-sm text-gray-500">No driver assigned to this booking</div>'}
                        </div>
                    </div>
                </div>

                <!-- LOCATION & TIMING INFORMATION -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b border-gray-700">
                    <div>
                        <div class="detail-card mb-4">
                            <h3 class="detail-label">Pick-up Location</h3>
                            <div class="detail-value">${booking.pickup_location || 'N/A'}</div>
                        </div>
                        <div class="detail-card">
                            <h3 class="detail-label">Check-in Time</h3>
                            <div class="detail-value">${formatDate(booking.start_datetime)}</div>
                        </div>
                    </div>
                    <div>
                        <div class="detail-card mb-4">
                            <h3 class="detail-label">Drop-off Location</h3>
                            <div class="detail-value">${booking.dropoff_location || 'N/A'}</div>
                        </div>
                        <div class="detail-card">
                            <h3 class="detail-label">Check-out Time</h3>
                            <div class="detail-value">${formatDate(booking.end_datetime)}</div>
                        </div>
                    </div>
                </div>

                <!-- DURATION INFORMATION -->
                <div class="detail-card mb-6 pb-6 border-b border-gray-700">
                    <h3 class="detail-label">Booking Duration</h3>
                    <div class="grid grid-cols-3 gap-4 mt-3">
                        <div class="bg-blue-900/30 rounded p-3 border border-blue-700/50">
                            <div class="text-2xl font-bold text-blue-400">${duration.days}</div>
                            <div class="text-xs text-gray-400">Days</div>
                        </div>
                        <div class="bg-blue-900/30 rounded p-3 border border-blue-700/50">
                            <div class="text-2xl font-bold text-blue-400">${duration.hours}</div>
                            <div class="text-xs text-gray-400">Hours</div>
                        </div>
                        <div class="bg-blue-900/30 rounded p-3 border border-blue-700/50">
                            <div class="text-2xl font-bold text-blue-400">${duration.totalHours}</div>
                            <div class="text-xs text-gray-400">Total Hours</div>
                        </div>
                    </div>
                </div>

                <!-- VEHICLES ASSIGNED -->
                <div class="mb-6 pb-6 border-b border-gray-700">
                    <h3 class="detail-label">Assigned Vehicles (${vehicles.length})</h3>
                    <div class="mt-3 space-y-3">
                        ${vehicles.length > 0 ? vehicles.map(vehicle => {
                            const v = vehicle.vehicle || vehicle;
                            const plateNum = v.plate_num || v.plate_number || 'N/A';
                            const brand = v.brand || 'N/A';
                            const model = v.model || 'N/A';
                            const bodyType = v.body_type || v.type || 'N/A';
                            const priceRate = v.price_rate || v.daily_rate || 0;
                            
                            return `
                                <div class="vehicle-item">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        <div>
                                            <div class="text-xs text-gray-500">Plate Number</div>
                                            <div class="font-semibold text-white">${plateNum}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">Vehicle</div>
                                            <div class="font-semibold text-white">${brand} ${model}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">Type</div>
                                            <div class="font-semibold text-white">${bodyType}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">Rate (Daily)</div>
                                            <div class="font-semibold text-green-400">₱${Number(priceRate).toFixed(2)}</div>
                                        </div>
                                    </div>
                                    ${vehicle.remarks ? `
                                        <div class="mt-2 pt-2 border-t border-blue-600/30 text-xs text-gray-400">
                                            <div>Remarks: ${vehicle.remarks}</div>
                                        </div>
                                    ` : ''}
                                </div>
                            `;
                        }).join('') : '<div class="text-gray-400 py-3">No vehicles assigned</div>'}
                    </div>
                </div>

                <!-- PRICING INFORMATION -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 pb-6 border-b border-gray-700">
                    <div class="bg-green-900/30 rounded p-4 border border-green-700/50">
                        <h3 class="detail-label">Total Price</h3>
                        <div class="text-3xl font-bold text-green-400 mt-2">
                            ₱${Number(booking.total_price || 0).toFixed(2)}
                        </div>
                    </div>
                    <div class="bg-purple-900/30 rounded p-4 border border-purple-700/50">
                        <h3 class="detail-label">Payment Method</h3>
                        <div class="text-xl font-bold text-purple-400 mt-2">
                            ${booking.payment_method ? booking.payment_method.replace('_', ' ').toUpperCase() : 'N/A'}
                        </div>
                    </div>
                    <div class="bg-indigo-900/30 rounded p-4 border border-indigo-700/50">
                        <h3 class="detail-label">Status</h3>
                        <div class="mt-2">
                            <span class="${statusClass} px-3 py-1 text-sm">${statusText}</span>
                        </div>
                    </div>
                </div>

                <!-- SPECIAL REQUESTS & NOTES -->
                <div class="mb-6 pb-6 border-b border-gray-700">
                    <h3 class="detail-label">Special Requests</h3>
                    <div class="detail-card mt-2">
                        <div class="text-gray-300">
                            ${booking.special_requests || '<span class="text-gray-500">No special requests</span>'}
                        </div>
                    </div>
                </div>

                <!-- BOOKING NOTES -->
                ${booking.notes ? `
                    <div class="mb-6 pb-6 border-b border-gray-700">
                        <h3 class="detail-label">Admin Notes</h3>
                        <div class="detail-card mt-2">
                            <div class="text-gray-300">${booking.notes}</div>
                        </div>
                    </div>
                ` : ''}

                <!-- AUDIT TRAIL -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div>
                        <h3 class="detail-label">Created</h3>
                        <div class="text-gray-300">${booking.created_by || 'System'}</div>
                        <div class="text-gray-500">${formatDate(booking.created_at)}</div>
                    </div>
                    <div>
                        <h3 class="detail-label">Last Updated</h3>
                        <div class="text-gray-300">${booking.updated_by || 'System'}</div>
                        <div class="text-gray-500">${formatDate(booking.updated_at)}</div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('showContent').innerHTML = html;
        switchView('showView');
    })
    .catch(err => {
        console.error('Error loading booking:', err);
        showNotification('error', 'Failed to load booking details: ' + (err.message || 'Please check console for details'));
    });
}

// Update booking status with loading state
function updateStatus(bookingId, statusId, buttonElement) {
    const statusNames = {
        1: 'Pending',
        2: 'Confirmed',
        3: 'Ongoing',
        4: 'Completed',
        5: 'Cancelled'
    };
    
    const action = statusNames[statusId] || 'update';
    if (!confirm(`Are you sure you want to ${action.toLowerCase()} this booking?`)) {
        return;
    }
    
    // Show loading state
    const originalText = buttonElement.textContent;
    buttonElement.innerHTML = '<span class="loading-spinner"></span>Processing...';
    buttonElement.disabled = true;
    
    fetch(`/employee/booking/${bookingId}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status_id: statusId })
    })
    .then(async r => {
        const data = await r.json().catch(() => ({}));
        
        if (!r.ok) {
            // Show error message in UI instead of alert
            showNotification('error', data.message || 'Failed to update booking status');
            throw new Error(data.message || 'Failed to update status');
        }
        return data;
    })
    .then(data => {
        // Show success message in UI
        showNotification('success', `Booking ${statusNames[statusId].toLowerCase()} successfully!`);
        // Refresh revenue stats after status update
        refreshRevenueStats();
        // Refresh the page after a short delay
        setTimeout(() => {
            location.reload();
        }, 1500);
    })
    .catch(err => {
        console.error('Error updating status:', err);
        // Restore button state
        buttonElement.textContent = originalText;
        buttonElement.disabled = false;
        // Show error message in UI
        showNotification('error', 'Failed to update booking status: ' + err.message);
    });
}

// Form submission with loading state
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const method = document.getElementById('formMethod').value;
    const isUpdate = method === 'PUT';
    const url = this.action;
    
    // Validate required fields
    const requiredFields = ['client_id', 'boarding_date', 'start_datetime', 'end_datetime', 'pickup_location', 'dropoff_location', 'total_price', 'status_id'];
    let missingFields = [];
    
    requiredFields.forEach(field => {
        if (!formData.get(field)) {
            missingFields.push(field.replace('_', ' '));
        }
    });
    
    // Check if at least one vehicle is selected
    const vehicleCheckboxes = document.querySelectorAll('input[name="vehicle_ids[]"]:checked');
    if (vehicleCheckboxes.length === 0) {
        missingFields.push('vehicles');
    }
    
    if (missingFields.length > 0) {
        showNotification('error', `Please fill in the following required fields:\n${missingFields.join('\n')}`);
        return;
    }
    
    // Show loading state
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.textContent;
    submitBtn.innerHTML = '<span class="loading-spinner"></span>Processing...';
    submitBtn.disabled = true;
    
    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(async r => {
        const data = await r.json().catch(() => ({}));
        
        if (!r.ok) {
            showNotification('error', data.message || 'Failed to save booking');
            throw new Error(data.message || 'Failed to save booking');
        }
        
        return data;
    })
    .then(data => {
        showNotification('success', isUpdate ? 'Booking updated successfully!' : 'Booking created successfully!');
        // Refresh revenue stats after booking creation/update
        refreshRevenueStats();
        // Refresh the page after a short delay
        setTimeout(() => {
            location.reload();
        }, 1500);
    })
    .catch(err => {
        console.error('Form error:', err);
        // Restore button state
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        showNotification('error', 'Error: ' + err.message);
    });
});

// REVENUE STATISTICS FUNCTIONS

// Switch revenue period
function switchRevenuePeriod(period) {
    currentRevenuePeriod = period;
    
    // Update active tab
    document.querySelectorAll('.revenue-tab').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.revenue-tab').forEach(tab => {
        if (tab.textContent.toLowerCase().includes(period)) {
            tab.classList.add('active');
        }
    });
    
    // Load revenue data for the selected period
    loadRevenueData(period);
}

// Load revenue data
function loadRevenueData(period) {
    fetch(`/employee/booking/stats`)
        .then(r => r.json())
        .then(data => {
            updateRevenueStats(data, period);
            updateRevenueChart(data, period);
            updateAdditionalRevenueData(data);
        })
        .catch(err => {
            console.error('Error loading revenue data:', err);
            showNotification('error', 'Failed to load revenue statistics');
        });
}

// Update revenue statistics
function updateRevenueStats(data, period) {
    // Update main revenue stats
    document.getElementById('todayRevenue').textContent = '₱' + (data.todayRevenue || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('monthlyRevenue').textContent = '₱' + (data.monthlyRevenue || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('totalRevenue').textContent = '₱' + (data.totalRevenue || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
    
    // Calculate average booking value
    const avgValue = data.total > 0 ? data.totalRevenue / data.total : 0;
    document.getElementById('avgBookingValue').textContent = '₱' + avgValue.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
    
    // Update trends (you would need historical data for this)
    updateTrends(data);
}

// Update revenue trends
function updateTrends(data) {
    // This is a simplified example - you would need to fetch historical data
    // For now, we'll just show placeholder trends
    const todayTrend = document.getElementById('todayTrend');
    const monthlyTrend = document.getElementById('monthlyTrend');
    const totalTrend = document.getElementById('totalTrend');
    
    // Example trends - replace with actual calculations from your database
    todayTrend.innerHTML = '<span class="trend-up">↑ 15% from yesterday</span>';
    monthlyTrend.innerHTML = '<span class="trend-up">↑ 8% from last month</span>';
    totalTrend.innerHTML = '<span class="trend-up">↑ 25% from last year</span>';
}

// Update revenue chart
function updateRevenueChart(data, period) {
    const ctx = document.getElementById('revenueChartCanvas').getContext('2d');
    
    // Destroy existing chart if it exists
    if (revenueChart) {
        revenueChart.destroy();
    }
    
    // Prepare chart data based on period
    let labels, chartData, backgroundColor;
    
    if (period === 'today') {
        // Last 24 hours in 4-hour intervals
        labels = ['12 AM', '4 AM', '8 AM', '12 PM', '4 PM', '8 PM'];
        chartData = [1200, 800, 1500, 2500, 1800, 900]; // Example data
        backgroundColor = 'rgba(239, 68, 68, 0.3)';
    } else if (period === 'monthly') {
        // Last 30 days
        labels = [];
        chartData = [];
        for (let i = 29; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            labels.push(date.getDate());
            // Example data - random values between 500 and 5000
            chartData.push(Math.floor(Math.random() * 4500) + 500);
        }
        backgroundColor = 'rgba(59, 130, 246, 0.3)';
    } else {
        // Total - last 12 months
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        labels = months;
        chartData = months.map(() => Math.floor(Math.random() * 20000) + 5000);
        backgroundColor = 'rgba(16, 185, 129, 0.3)';
    }
    
    revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: `Revenue (₱)`,
                data: chartData,
                backgroundColor: backgroundColor,
                borderColor: period === 'today' ? '#EF4444' : period === 'monthly' ? '#3B82F6' : '#10B981',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: period === 'today' ? '#EF4444' : period === 'monthly' ? '#3B82F6' : '#10B981',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            return `₱${context.parsed.y.toLocaleString()}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#9CA3AF'
                    }
                },
                y: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#9CA3AF',
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        }
                    },
                    beginAtZero: true
                }
            }
        }
    });
}

// Update additional revenue data
function updateAdditionalRevenueData(data) {
    const container = document.getElementById('additionalRevenueData');
    
    const revenueData = {
        'Revenue by Status': {
            'Confirmed': data.revenue_by_status?.confirmed || 0,
            'Ongoing': data.revenue_by_status?.ongoing || 0,
            'Completed': data.revenue_by_status?.completed || 0
        },
        'Performance Metrics': {
            'Avg. Daily Revenue': data.totalRevenue > 0 ? Math.round(data.totalRevenue / 365) : 0,
            'Conversion Rate': '85%', // Example
            'Customer Lifetime Value': '₱' + (data.totalRevenue > 0 ? Math.round(data.totalRevenue / data.total * 3) : 0).toLocaleString()
        }
    };
    
    let html = '';
    
    Object.entries(revenueData).forEach(([category, items]) => {
        html += `
            <div class="revenue-data-item">
                <div class="revenue-data-label">${category}</div>
                ${Object.entries(items).map(([label, value]) => `
                    <div class="mt-2">
                        <div class="text-sm text-gray-400">${label}</div>
                        <div class="revenue-data-value">
                            ${typeof value === 'number' ? '₱' + value.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) : value}
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    });
    
    // Add 7-day trend data if available
    if (data.last_7_days_revenue && data.last_7_days_revenue.length > 0) {
        const last7Days = data.last_7_days_revenue;
        const total7Days = last7Days.reduce((sum, day) => sum + (day.revenue || 0), 0);
        const avg7Days = total7Days / 7;
        
        html += `
            <div class="revenue-data-item">
                <div class="revenue-data-label">7-Day Trend</div>
                <div class="mt-2">
                    <div class="text-sm text-gray-400">Total (7 days)</div>
                    <div class="revenue-data-value">₱${total7Days.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
                </div>
                <div class="mt-2">
                    <div class="text-sm text-gray-400">Daily Average</div>
                    <div class="revenue-data-value">₱${avg7Days.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
                </div>
            </div>
        `;
    }
    
    container.innerHTML = html;
}

// Refresh revenue stats
function refreshRevenueStats() {
    const refreshBtn = document.getElementById('refreshRevenueBtn');
    const originalHtml = refreshBtn.innerHTML;
    
    // Show spinning animation
    refreshBtn.classList.add('spinning');
    refreshBtn.innerHTML = '<span class="loading-spinner"></span>Refreshing...';
    refreshBtn.disabled = true;
    
    // Load fresh data
    loadRevenueData(currentRevenuePeriod);
    
    // Also get fresh booking stats
    fetch(`/employee/booking/stats`)
        .then(r => r.json())
        .then(data => {
            showNotification('success', 'Revenue statistics refreshed successfully!');
        })
        .catch(err => {
            console.error('Error refreshing stats:', err);
            showNotification('error', 'Failed to refresh statistics');
        })
        .finally(() => {
            // Restore button state after 1.5 seconds
            setTimeout(() => {
                refreshBtn.classList.remove('spinning');
                refreshBtn.innerHTML = originalHtml;
                refreshBtn.disabled = false;
            }, 1500);
        });
}

// Function to show notification messages
function showNotification(type, message) {
    // Remove any existing notification
    const existingNotification = document.querySelector('.dynamic-notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `dynamic-notification ${type}-message border-l-4 rounded-lg p-4 mb-6 flex justify-between items-center ${
        type === 'success' ? 'bg-green-600/20 text-green-300 border-green-500' : 'bg-red-600/20 text-red-300 border-red-500'
    } animate-slidedown`;
    
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                ${type === 'success' ? 
                    '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>' :
                    '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>'
                }
            </svg>
            <span>${message}</span>
        </div>
        <button class="close-notification ${type === 'success' ? 'text-green-300 hover:text-green-100' : 'text-red-300 hover:text-red-100'}">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    `;
    
    // Insert after header
    const header = document.querySelector('main .flex.justify-between.items-center.mb-6');
    if (header) {
        header.parentNode.insertBefore(notification, header.nextSibling);
    } else {
        // Fallback: insert at the top of main content
        document.querySelector('main').insertBefore(notification, document.querySelector('main').firstChild);
    }
    
    // Add close functionality
    notification.querySelector('.close-notification').addEventListener('click', () => {
        notification.style.opacity = '0';
        setTimeout(() => {
            if (notification.parentElement) notification.remove();
        }, 300);
    });
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.opacity = '0';
            setTimeout(() => {
                if (notification.parentElement) notification.remove();
            }, 300);
        }
    }, 5000);
}

// Reset form when clicking 'New Booking'
document.getElementById('newBookingBtn').addEventListener('click', () => {
    setTimeout(() => {
        document.getElementById('bookingForm').reset();
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('bookingForm').action = '/employee/booking';
        document.getElementById('submitBtn').textContent = 'Create Booking';
        document.getElementById('pageTitle').textContent = 'Create New Booking';
        document.getElementById('bookingId').value = '';
        document.querySelectorAll('.vehicle-checkbox-input').forEach(cb => cb.checked = false);
    }, 50);
});

// Theme toggle functionality
document.getElementById('theme-toggle').addEventListener('click', function() {
    const body = document.body;
    const themeIcon = document.getElementById('theme-icon');
    const themeText = this.querySelector('span');
    
    if (body.classList.contains('dark')) {
        body.classList.remove('dark');
        themeIcon.src = '{{ asset('assets/moon.png') }}';
        themeText.textContent = 'Dark Mode';
    } else {
        body.classList.add('dark');
        themeIcon.src = '{{ asset('assets/sun.png') }}';
        themeText.textContent = 'Light Mode';
    }
});

// Auto-close alerts after 5 seconds
document.querySelectorAll('.success-message, .error-message').forEach(alert => {
    setTimeout(() => {
        if (alert.parentElement) {
            alert.style.opacity = '0';
            setTimeout(() => {
                if (alert.parentElement) alert.remove();
            }, 300);
        }
    }, 5000);
});

// Initialize revenue statistics when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Load initial revenue data
    loadRevenueData('today');
    
    // Add click handlers to stat cards
    document.querySelectorAll('.stat-card').forEach(card => {
        card.style.cursor = 'pointer';
        card.addEventListener('click', function() {
            const label = this.querySelector('.stat-label').textContent.toLowerCase();
            if (label.includes('today')) {
                switchRevenuePeriod('today');
            } else if (label.includes('monthly')) {
                switchRevenuePeriod('monthly');
            } else if (label.includes('total')) {
                switchRevenuePeriod('total');
            }
        });
    });
});
</script>

@endsection