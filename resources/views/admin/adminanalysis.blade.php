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

   @include('admin.layout.sidebar')

   {{-- MAIN CONTENT WRAPPER --}}
   <main id="dashboard-wrapper" class="ml-64 w-full min-h-screen p-8 transition-all duration-300">

       {{-- Header --}}
       <div class="flex justify-between items-center mb-8">
           <h1 id="pageTitle" class="text-3xl font-bold text-red-500 drop-shadow-lg">Analysis Overview</h1>
           <div class="flex items-center space-x-4">
               {{-- Refresh Revenue Stats --}}
               <button id="refreshRevenueBtn" onclick="refreshRevenueStats()" class="refresh-btn">
                   <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                   </svg>
                   Refresh Stats
               </button>

               {{-- Theme Toggle --}}
               <button id="theme-toggle"
                   class="flex items-center gap-2 bg-black/30 backdrop-blur-xl p-2 rounded-lg hover:bg-[#998282] transition-all duration-300 cursor-pointer">
                   <img id="theme-icon" src="{{ asset('assets/moon.png') }}"
                        class="w-6 h-6 transition-transform duration-500">
                   <span class="font-medium text-white">Dark Mode</span>
               </button>
           </div>
       </div>

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
}

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
    fetch(`/admin/analysis/stats`)
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
    fetch(`/admin/analysis/stats`)
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
    const header = document.querySelector('main .flex.justify-between.items-center.mb-8');
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

function confirmLogout(event) {
    event.preventDefault();
    if (confirm('Are you sure you want to logout?')) {
        document.getElementById('logoutForm').submit();
    }
}
</script>
@endsection