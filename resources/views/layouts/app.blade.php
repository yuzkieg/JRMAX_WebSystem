<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>JRMAX</title>

    @vite('resources/css/app.css')
    @vite('resources/js/theme.js')

    <style>
/* ========== DEFAULT DARK MODE STYLES ========== */
body {
    background-color: #0f172a;
    color: #f1f5f9;
}

/* Default dark mode main wrapper - FIXED */
#dashboard-wrapper {
    background-color: #0f172a;
}

/* Default dark mode main content area */
main {
    background-color: transparent;
    color: #f1f5f9;
}

/* Default dark mode page title */
#pageTitle {
    color: #ef4444;
    text-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
}

/* Default dark mode buttons */
.refresh-btn {
    background-color: rgba(0, 0, 0, 0.3);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.refresh-btn:hover {
    background-color: #998282;
}

#theme-toggle {
    background-color: rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

#theme-toggle:hover {
    background-color: #998282;
}

#theme-toggle span {
    color: white;
}

/* Default dark mode sidebar */
aside {
    background-color: #1e293b;
    border-right: 1px solid rgba(255, 255, 255, 0.1);
    color: #f1f5f9;
}

/* Default dark mode revenue section */
.revenue-chart-container {
    background-color: rgba(30, 41, 59, 0.6);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
}

.revenue-chart-title {
    color: #f1f5f9;
    font-size: 1.5rem;
    font-weight: 700;
}

/* Default dark mode revenue tabs */
.revenue-tab {
    background-color: rgba(51, 65, 85, 0.6);
    color: #94a3b8;
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.revenue-tab:hover {
    background-color: rgba(71, 85, 105, 0.8);
    color: #cbd5e1;
}

.revenue-tab.active {
    background-color: #ef4444;
    color: white;
    border-color: #ef4444;
}

/* Default dark mode stat cards */
.stat-card {
    background: linear-gradient(135deg, #991b1b 0%, #7f1d1d 100%);
    color: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.4);
}

.stat-card-booking {
    background: linear-gradient(135deg, #991b1b 0%, #7f1d1d 100%);
    color: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.4);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 12px rgba(0, 0, 0, 0.6);
}

/* Default dark mode chart area */
.revenue-chart {
    background-color: rgba(30, 41, 59, 0.8);
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Default dark mode chart canvas */
.revenue-chart canvas {
    background-color: transparent !important;
}

/* Default dark mode text colors */
.stat-value,
.stat-label,
.stat-trend {
    color: white;
}

/* Ensure consistent dimensions */
.revenue-chart-container {
    min-height: 600px;
}

.revenue-chart {
    min-height: 400px;
    width: 100%;
}

.revenue-chart canvas {
    width: 100% !important;
    height: 400px !important;
}

/* ========== LIGHT MODE STYLES (when .dark class is added) ========== */
.dark body {
    background-color: #e2e8f0; /* Changed from #f8fafc - softer gray */
    color: #1e293b;
}

/* Light mode main wrapper - FIXED */
.dark #dashboard-wrapper {
    background-color: #e2e8f0; /* Changed from #f8fafc */
}

.dark .flex.min-h-screen {
    background-color: #e2e8f0; /* Changed from #f8fafc */
}

/* Light mode main content area */
.dark main {
    background-color: transparent;
    color: #1e293b;
}

/* Light mode page title */
.dark #pageTitle {
    color: #dc2626;
    text-shadow: 0 2px 4px rgba(220, 38, 38, 0.1);
}

/* Light mode buttons */
.dark .refresh-btn {
    background-color: rgba(0, 0, 0, 0.05);
    color: #1e293b;
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.dark .refresh-btn:hover {
    background-color: rgba(0, 0, 0, 0.1);
}

.dark #theme-toggle {
    background-color: rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.dark #theme-toggle:hover {
    background-color: rgba(0, 0, 0, 0.1);
}

.dark #theme-toggle span {
    color: #1e293b;
}

/* Light mode sidebar */
.dark aside {
    background-color: #ffffff;
    border-right: 1px solid #e2e8f0;
    color: #1e293b;
}

/* Light mode revenue section */
.dark .revenue-chart-container {
    background-color: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid #e2e8f0;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.dark .revenue-chart-title {
    color: #1e293b;
    font-size: 1.5rem;
    font-weight: 700;
}

/* Light mode revenue tabs */
.dark .revenue-tab {
    background-color: #f1f5f9;
    color: #64748b;
    border: 1px solid #e2e8f0;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.dark .revenue-tab:hover {
    background-color: #e2e8f0;
    color: #475569;
}

.dark .revenue-tab.active {
    background-color: #dc2626;
    color: white;
    border-color: #dc2626;
}

/* Light mode stat cards - FIXED */
.dark .stat-card {
    background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
    color: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(220, 38, 38, 0.2);
}

.dark .stat-card-booking {
    background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
    color: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(220, 38, 38, 0.2);
}

.dark .stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 12px rgba(220, 38, 38, 0.3);
}

/* Light mode chart area */
.dark .revenue-chart {
    background-color: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
    min-height: 400px;
    width: 100%;
}

/* Light mode chart canvas */
.dark .revenue-chart canvas {
    background-color: white !important;
    width: 100% !important;
    height: 400px !important;
    display: block !important;
}

/* Light mode text colors - FIXED: Allow colored stat values to show through */
.dark .stat-card .stat-label {
    color: white !important;
}

.dark .stat-card .stat-trend {
    color: white !important;
}

/* REMOVED the white color override for stat-value to allow inline styles */
.dark .stat-card .stat-value {
    font-size: 1.875rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    /* Color is now controlled by inline styles in HTML */
}

/* Light mode container sizing */
.dark .revenue-chart-container {
    min-height: 600px;
    margin-bottom: 2rem;
}

/* ========== SHARED STYLES ========== */
.stat-value {
    font-size: 1.875rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: white;
    text-align: right;
}

.stat-label {
    font-size: 0.875rem;
    opacity: 0.9;
    margin-bottom: 0.5rem;
    color: white;
    text-align: right;
}

.stat-trend {
    text-align: right;
}

.stat-trend {
    font-size: 0.75rem;
    opacity: 0.8;
    color: white;
}

/* Override for light mode containers (not stat cards) */
.dark main {
    color: #1e293b;
}

.dark .revenue-chart-container > *:not(.stat-card):not(.stat-card *) {
    color: #1e293b;
}

.refresh-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
    cursor: pointer;
}

.revenue-tabs {
    display: flex;
    gap: 0.5rem;
}

.revenue-data-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-top: 1.5rem;
}

/* Ensure consistent layout across themes */
main {
    padding: 2rem;
    width: 100%;
    min-height: 100vh;
}

.revenue-chart-container {
    min-height: 600px;
    margin-bottom: 2rem;
}

.revenue-chart {
    min-height: 400px;
    width: 100%;
    margin-top: 1.5rem;
}

.revenue-chart canvas {
    width: 100% !important;
    height: 400px !important;
    display: block;
}

/* Grid consistency */
.grid {
    display: grid;
}

.grid-cols-1 {
    grid-template-columns: repeat(1, minmax(0, 1fr));
}

@media (min-width: 768px) {
    .md\:grid-cols-4 {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }
}

.gap-6 {
    gap: 1.5rem;
}

.mb-8 {
    margin-bottom: 2rem;
}

/* Date/Time picker icon colors */
input[type="date"]::-webkit-calendar-picker-indicator,
input[type="time"]::-webkit-calendar-picker-indicator {
    cursor: pointer;
    filter: invert(1); /* Default dark mode - inverted */
}

.dark input[type="date"]::-webkit-calendar-picker-indicator,
.dark input[type="time"]::-webkit-calendar-picker-indicator {
    filter: invert(0); /* Light mode - not inverted */
}

/* Theme icon animation */
#theme-icon {
    transition: transform 0.5s ease;
}

.rotate-360 {
    transform: rotate(360deg);
}

/* Prevent theme transition on page load */
.notransition * {
    transition: none !important;
}

html {
    scroll-behavior: smooth;
}

/* Smooth transitions - ENHANCED */
body, 
#dashboard-wrapper, 
main, 
aside, 
.revenue-chart-container, 
.stat-card, 
.revenue-chart,
.flex.min-h-screen {
    transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
}

/* Additional fix for flex container */
.flex.min-h-screen {
    background-color: #0f172a;
}

.dark .flex.min-h-screen {
    background-color: #f8fafc;
}

/* ========== CURRENT DEFAULT DARK MODE STYLES FOR TABLE ========== */
/* These rules apply when the 'html' element DOES NOT have the 'dark' class */
.dark-table {
    background-color: rgba(30, 41, 59, 0.6); /* Dark blue background */
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
}

.dark-table thead th {
    background-color: rgba(38, 43, 50, 0.8); /* Dark gray header background */
    color: #f9fafb; /* Off-white text */
    font-weight: 600;
    padding: 1rem;
    text-transform: uppercase;
    font-size: 0.875rem;
    letter-spacing: 0.05em;
    border-bottom: 2px solid rgba(255, 255, 255, 0.1);
}

.dark-table tbody td {
    background-color: rgba(38, 43, 50, 0.4); /* Semi-transparent dark gray cells */
    color: #e5e7eb; /* Light gray text */
    padding: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    transition: background-color 0.2s ease;
}

.dark-table tbody tr:hover td {
    background-color: rgba(55, 65, 81, 0.8); /* Darker gray on hover */
}

/* Action buttons visibility in dark mode */
.dark-table .actions-menu {
    background-color: #262B32;
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Adjusted shadow for consistency */
}

.dark-table .actions-toggle svg {
    color: #ffffff; /* White icon */
}

.dark-table .actions-menu button:hover {
    background-color: rgba(255, 255, 255, 0.05);
}

/* ========== LIGHT MODE STYLES FOR TABLE (when .dark class is added) ========== */
/* These rules apply when the 'html' element HAS the 'dark' class */
.dark .dark-table {
    background-color: white; /* White background */
    backdrop-filter: blur(10px); /* This might not be visible on white but keeping for consistency */
    border: 2px solid #000000; /* Black border */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.dark .dark-table thead th {
    background-color: #b81111; 
    color: #ffffff; /* White text */
    border-bottom: 2px solid #000000; /* Black border */
}

.dark .dark-table tbody td {
    background-color: white; /* White cells */
    color: #000000; /* Black text */
    border-bottom: 1px solid #000000; /* Black border */
}

.dark .dark-table tbody tr:hover td {
    background-color: #fee2e2; /* Light red/pink on hover */
}

/* Action buttons visibility in light mode */
.dark .dark-table .actions-menu {
    background-color: white;
    border: 1px solid #000000;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.dark .dark-table .actions-toggle svg {
    color: #000000;
}

.dark .dark-table .actions-menu button:hover {
    background-color: #f3f4f6;
}

/* Smooth transitions for theme changes (keep this as is) */
.dark-table,
.dark-table th,
.dark-table td {
    transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
}

    </style>
</head>

<body class="transition-colors duration-500">
    @yield('content')

    <script>
        // Existing variables
        window.APP_STORAGE_PATH = "{{ asset('storage') }}";
        window.DEFAULT_VEHICLE_IMAGE = "{{ asset('assets/default-vehicle.jpg') }}";

        // Action icons
        window.ICON_VIEW = "{{ asset('assets/file.png') }}";
        window.ICON_EDIT = "{{ asset('assets/edit.png') }}";
        window.ICON_DELETE = "{{ asset('assets/delete.png') }}";

        // Theme switching function for tables
        function updateTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            
            // Update light-card/dark-card elements if any exist
            const cards = document.querySelectorAll('.light-card, .dark-card');
            cards.forEach(card => {
                if (isDark) {
                    card.classList.remove('light-card');
                    card.classList.add('dark-card');
                } else {
                    card.classList.remove('dark-card');
                    card.classList.add('light-card');
                }
            });
        }

            // Update light-card/dark-card elements if any exist
            const cards = document.querySelectorAll('.light-card, .dark-card');
            cards.forEach(card => {
                if (isDark) {
                    card.classList.remove('light-card');
                    card.classList.add('dark-card');
                } else {
                    card.classList.remove('dark-card');
                    card.classList.add('light-card');
                }
            });

            // stat-card and revenue elements are handled by CSS
        }

        // Run on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateTheme();
            
            // Watch for theme changes
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

        // Also run immediately in case DOM is already loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', updateTheme);
        } else {
            updateTheme();
        }
    </script>
</body>
</html>