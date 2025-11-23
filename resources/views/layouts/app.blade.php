<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>JRMAX</title>

    @vite('resources/css/app.css')

    <style>
        .header-blur { backdrop-filter: blur(6px); }
        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-calendar-picker-indicator {
            filter: invert(1); cursor: pointer;
        }
        .book-now-blur { backdrop-filter: blur(6px); }

        html { scroll-behavior: smooth; }

        /* Slightly lighter than dark background */
        .dark-card {
            background-color: rgba(38, 43, 50, 0.85);
        }

        /* Slightly darker than light background */
        .light-card {
            background-color: rgba(255, 255, 255, 0.3);
        }

        #theme-icon {
            transition: transform 0.5s ease;
        }
        .rotate-360 {
            transform: rotate(360deg);
        }
/* Light Mode Table */
.light-table th,
.light-table td {
    background-color: rgba(255, 255, 255, 0.2); /* cell background */
    color: black; /* text color */
    border-color: rgba(0, 0, 0, 0.1); /* optional border */
}

/* Dark Mode Table */
.dark-table th,
.dark-table td {
    background-color: rgba(38, 43, 50, 0.6); /* cell background */
    color: white; /* text color */
    border-color: rgba(255, 255, 255, 0.1); /* optional border */
}

/* Optional: row hover effect */
.light-table tr:hover td,
.light-table tr:hover th {
    background-color: rgba(0, 0, 0, 0.05);
}

.dark-table tr:hover td,
.dark-table tr:hover th {
    background-color: rgba(255, 255, 255, 0.05);

/* Prevent theme transition on page load */
.notransition * {
    transition: none !important;
}

    
}
    </style>
</head>

<body class="transition-colors duration-500">
    @yield('content')

    <script src="{{ asset('js/theme.js') }}"></script>
</body>
</html>
