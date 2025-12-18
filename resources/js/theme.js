document.addEventListener("DOMContentLoaded", () => {
    const toggleBtn = document.getElementById('theme-toggle');
    const wrapper = document.getElementById('dashboard-wrapper');
    const icon = document.getElementById('theme-icon');
    const btnText = toggleBtn.querySelector('span');
    const searchInput = document.getElementById('searchInput');

    // -------------------- Placeholder helper --------------------
    function setPlaceholderColor(color) {
        let style = document.getElementById("dynamic-placeholder-style");
        if (!style) {
            style = document.createElement("style");
            style.id = "dynamic-placeholder-style";
            document.head.appendChild(style);
        }
        style.innerHTML = `
            input::placeholder,
            textarea::placeholder,
            select::placeholder,
            .form-group input::placeholder,
            .form-group textarea::placeholder {
                color: ${color} !important;
            }
        `;
    }

    // -------------------- Apply color variables --------------------
    function applyColorVars(mode) {
        const root = document.documentElement;
        if (mode === 'light') {
            root.style.setProperty('--nav-bg', '#2F2F2F');
            root.style.setProperty('--nav-tab-text', '#ffffff');
            root.style.setProperty('--action-edit', '#2563EB');
            root.style.setProperty('--action-edit-hover', '#1E40AF');
            root.style.setProperty('--action-delete', '#B91C1C');
            root.style.setProperty('--action-delete-hover', '#991B1B');
            root.style.setProperty('--type-pill-bg', 'rgba(0,0,0,0.1)');
            root.style.setProperty('--type-pill-color', '#000000');
        } else {
            root.style.setProperty('--nav-bg', 'rgba(0,0,0,0.8)');
            root.style.setProperty('--nav-tab-text', '#ffffff');
            root.style.setProperty('--action-edit', '#2563EB');
            root.style.setProperty('--action-edit-hover', '#1E40AF');
            root.style.setProperty('--action-delete', '#B91C1C');
            root.style.setProperty('--action-delete-hover', '#991B1B');
            root.style.setProperty('--type-pill-bg', 'rgba(255,255,255,0.04)');
            root.style.setProperty('--type-pill-color', '#ffffff');
        }
    }

    // -------------------- Main function to apply theme --------------------
    function setTheme(mode) {
        // Apply CSS variables
        applyColorVars(mode);

        // -------------------- Cards --------------------
        document.querySelectorAll('.card-text').forEach(el => {
            el.style.backgroundColor = mode === 'light' ? 'rgba(255,255,255,0.3)' : 'rgba(38,43,50,0.85)';
            el.style.color = mode === 'light' ? 'black' : 'white';
        });

        // -------------------- Sidebar --------------------
        const sidebar = document.getElementById("sidebar");
        if (sidebar) {
            if (mode === "light") {
                sidebar.style.backgroundColor = "#2F2F2F";
                sidebar.style.color = "white";
            } else {
                sidebar.style.backgroundColor = "rgba(0,0,0,0.8)";
                sidebar.style.color = "white";
            }
        }

        // -------------------- Tables --------------------
        document.querySelectorAll('table').forEach(table => {
            table.classList.remove('dark-table', 'light-table');
            table.classList.add(mode === 'light' ? 'light-table' : 'dark-table');
        });

        // -------------------- Form Elements --------------------
        document.querySelectorAll('.form-group input, .form-group textarea, .form-group select').forEach(input => {
            if (mode === 'light') {
                input.classList.remove('text-white', 'bg-black/20');
                input.classList.add('text-black', 'bg-gray-200');
            } else {
                input.classList.remove('text-black', 'bg-gray-200');
                input.classList.add('text-white', 'bg-black/20');
            }
        });

        // -------------------- Form Labels --------------------
        document.querySelectorAll('.form-group label').forEach(label => {
            if (mode === 'light') {
                label.classList.remove('text-white');
                label.classList.add('text-black');
            } else {
                label.classList.remove('text-black');
                label.classList.add('text-white');
            }
        });

        // -------------------- Search input --------------------
        if (searchInput) {
            searchInput.classList.toggle('text-black', mode === 'light');
            searchInput.classList.toggle('text-white', mode !== 'light');
            searchInput.classList.toggle('bg-gray-200', mode === 'light');
            searchInput.classList.toggle('bg-black/20', mode !== 'light');
        }

        // -------------------- Placeholder color --------------------
        setPlaceholderColor(mode === 'light' ? "#999" : "#ccc");

        // -------------------- Detail Cards --------------------
        document.querySelectorAll('.detail-card').forEach(card => {
            if (mode === 'light') {
                card.style.backgroundColor = 'rgba(0,0,0,0.05)';
                card.style.color = '#000';
            } else {
                card.style.backgroundColor = 'rgba(255,255,255,0.05)';
                card.style.color = '#fff';
            }
        });

        // -------------------- Success/Error messages --------------------
        document.querySelectorAll('.error-message, .success-message').forEach(msg => {
            if (mode === 'light') {
                msg.classList.remove('bg-green-600/20', 'text-green-300', 'border-green-500', 'bg-red-600/20', 'text-red-300', 'border-red-500');
                if (msg.classList.contains('success-message')) {
                    msg.classList.add('bg-green-200/50', 'text-green-800', 'border-green-300');
                } else {
                    msg.classList.add('bg-red-200/50', 'text-red-800', 'border-red-300');
                }
            } else {
                msg.classList.remove('bg-green-200/50', 'text-green-800', 'border-green-300', 'bg-red-200/50', 'text-red-800', 'border-red-300');
                if (msg.classList.contains('success-message')) {
                    msg.classList.add('bg-green-600/20', 'text-green-300', 'border-green-500');
                } else {
                    msg.classList.add('bg-red-600/20', 'text-red-300', 'border-red-500');
                }
            }
        });
    }

    // -------------------- Load saved theme (no transition on page load) --------------------
    const savedTheme = localStorage.getItem('theme') || 'dark';

    // Temporarily disable transitions for page load
    wrapper.classList.add('notransition');

    if(savedTheme === 'light') {
        wrapper.classList.add('bg-white','text-black');
        wrapper.classList.remove('bg-[#1A1F24]','text-white');
        icon.src = "/assets/sun.png";
        btnText.textContent = 'Light Mode';
        setTheme('light');
    } else {
        wrapper.classList.add('bg-[#1A1F24]','text-white');
        wrapper.classList.remove('bg-white','text-black');
        icon.src = "/assets/moon.png";
        btnText.textContent = 'Dark Mode';
        setTheme('dark');
    }

    // Re-enable transitions after applying theme
    setTimeout(() => {
        wrapper.classList.remove('notransition');
    }, 50);

    // -------------------- Toggle theme --------------------
    toggleBtn.addEventListener('click', () => {
        icon.classList.add('rotate-360');
        setTimeout(() => icon.classList.remove('rotate-360'), 500);

        const isDark = wrapper.classList.contains('bg-[#1A1F24]');
        const newMode = isDark ? 'light' : 'dark';

        if(newMode === 'light') {
            wrapper.classList.replace('bg-[#1A1F24]', 'bg-white');
            wrapper.classList.replace('text-white', 'text-black');
            icon.src = "/assets/sun.png";
            btnText.textContent = 'Light Mode';
        } else {
            wrapper.classList.replace('bg-white', 'bg-[#1A1F24]');
            wrapper.classList.replace('text-black', 'text-white');
            icon.src = "/assets/moon.png";
            btnText.textContent = 'Dark Mode';
        }

        localStorage.setItem('theme', newMode);
        setTheme(newMode);
    });
});
