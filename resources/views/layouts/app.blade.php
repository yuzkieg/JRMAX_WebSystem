<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
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
        
        /* Slightly lighter than background for dark mode */
.dark-card {
    background-color: rgba(38, 43, 50, 0.85); /* subtle lift from bg */
}

/* Light mode card */
.light-card {
    background-color: rgba(255, 255, 255, 0.3);
}

#theme-icon {
    transition: transform 0.5s ease;
}
.rotate-180 {
    transform: rotate(180deg);
}


        
    </style>
</head>
<body class="bg-white text-gray-900">
    @yield('content')
</body>
</html>
