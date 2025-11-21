document.addEventListener("DOMContentLoaded", () => {
    const toggleBtn = document.getElementById('theme-toggle');
    const wrapper = document.getElementById('dashboard-wrapper');
    const icon = document.getElementById('theme-icon');
    const btnText = toggleBtn.querySelector('span');
    const searchInput = document.getElementById('searchInput');

    // Function to apply theme to cards, tables, modals, and search input
    function setTheme(mode) {
        // Cards
        document.querySelectorAll('.card-text').forEach(el => {
            el.style.backgroundColor = mode === 'light' ? 'rgba(255,255,255,0.3)' : 'rgba(38,43,50,0.85)';
            el.style.color = mode === 'light' ? 'black' : 'white';
        });

        // Tables
        document.querySelectorAll('table').forEach(table => {
            table.classList.remove('dark-table', 'light-table');
            table.classList.add(mode === 'light' ? 'light-table' : 'dark-table');
        });

        // Modals
        document.querySelectorAll('.modal-content').forEach(modal => {
            if(mode === 'light') {
                modal.classList.add('bg-white', 'text-black');
                modal.classList.remove('bg-[#262b32]', 'text-white');
            } else {
                modal.classList.add('bg-[#262b32]', 'text-white');
                modal.classList.remove('bg-white', 'text-black');
            }
        });

        // Search input
        if(searchInput) {
            searchInput.classList.toggle('text-black', mode === 'light');
            searchInput.classList.toggle('text-white', mode !== 'light');
            searchInput.classList.toggle('placeholder-black', mode === 'light');
            searchInput.classList.toggle('placeholder-gray-300', mode !== 'light');
            searchInput.classList.toggle('bg-gray-200', mode === 'light');
            searchInput.classList.toggle('bg-black/20', mode !== 'light');
        }
    }

    // Load saved theme
    const savedTheme = localStorage.getItem('theme') || 'dark';
    if(savedTheme === 'light') {
        wrapper.classList.add('bg-white', 'text-black');
        wrapper.classList.remove('bg-[#1A1F24]', 'text-white');
        icon.src = "/assets/sun.png";
        btnText.textContent = 'Light Mode';
        setTheme('light');
    } else {
        wrapper.classList.add('bg-[#1A1F24]', 'text-white');
        wrapper.classList.remove('bg-white', 'text-black');
        icon.src = "/assets/moon.png";
        btnText.textContent = 'Dark Mode';
        setTheme('dark');
    }

    // Toggle theme
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

    // Observe for dynamically added card-text and table elements
    const observer = new MutationObserver(() => {
        const mode = localStorage.getItem('theme') || 'dark';
        setTheme(mode);
    });

    observer.observe(document.body, { childList: true, subtree: true });
});
