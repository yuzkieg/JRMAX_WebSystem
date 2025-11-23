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
            #adminModal input::placeholder,
            #deleteConfirmModal input::placeholder,
            #searchInput::placeholder {
                color: ${color} !important;
            }
        `;
    }

    // -------------------- Main function to apply theme --------------------
    function setTheme(mode) {
        // -------------------- Cards --------------------
        document.querySelectorAll('.card-text').forEach(el => {
            el.style.backgroundColor = mode === 'light' ? 'rgba(255,255,255,0.3)' : 'rgba(38,43,50,0.85)';
            el.style.color = mode === 'light' ? 'black' : 'white';
        });

        // -------------------- Tables --------------------
        document.querySelectorAll('table').forEach(table => {
            table.classList.remove('dark-table', 'light-table');
            table.classList.add(mode === 'light' ? 'light-table' : 'dark-table');
        });

        // -------------------- Modals --------------------
        document.querySelectorAll('.modal-content').forEach(modal => {
            if (mode === 'light') {
                modal.classList.add('bg-white', 'text-black');
                modal.classList.remove('bg-[#262b32]', 'text-white');
            } else {
                modal.classList.add('bg-[#262b32]', 'text-white');
                modal.classList.remove('bg-white', 'text-black');
            }
        });

        // -------------------- Search input --------------------
        if (searchInput) {
            searchInput.classList.toggle('text-black', mode === 'light');
            searchInput.classList.toggle('text-white', mode !== 'light');
            searchInput.classList.toggle('bg-gray-200', mode === 'light');
            searchInput.classList.toggle('bg-black/20', mode !== 'light');
        }

        // -------------------- Modal input fields --------------------
        document.querySelectorAll('#adminModal input, #deleteConfirmModal input').forEach(input => {
            if (mode === 'light') {
                input.classList.remove('text-white', 'bg-black/20');
                input.classList.add('text-black', 'bg-gray-200');
            } else {
                input.classList.remove('text-black', 'bg-gray-200');
                input.classList.add('text-white', 'bg-black/20');
            }
        });

        // -------------------- Delete modal labels & paragraphs --------------------
        document.querySelectorAll('#deleteConfirmModal input, #deleteConfirmModal label, #deleteConfirmModal p').forEach(el => {
            if (mode === 'light') {
                if (el.tagName === 'INPUT') {
                    el.classList.remove('text-white', 'bg-black/20');
                    el.classList.add('text-black', 'bg-gray-200');
                } else {
                    el.classList.remove('text-gray-300', 'text-white', 'text-gray-400');
                    el.classList.add('text-black', 'text-gray-700');
                }
            } else {
                if (el.tagName === 'INPUT') {
                    el.classList.remove('text-black', 'bg-gray-200');
                    el.classList.add('text-white', 'bg-black/20');
                } else {
                    el.classList.remove('text-black', 'text-gray-700');
                    el.classList.add('text-gray-300');
                }
            }
        });

        // -------------------- Placeholder color --------------------
        setPlaceholderColor(mode === 'light' ? "#222" : "#ccc");

        // -------------------- Success/Error messages --------------------
        document.querySelectorAll('#successMessage, #errorMessage').forEach(msg => {
            if (!msg) return;
            if (mode === 'light') {
                if (msg.id === 'successMessage') {
                    msg.classList.remove('bg-green-600/20','text-green-300','border-green-500');
                    msg.classList.add('bg-green-200/50','text-green-800','border-green-300');
                } else {
                    msg.classList.remove('bg-red-600/20','text-red-300','border-red-500');
                    msg.classList.add('bg-red-200/50','text-red-800','border-red-300');
                }
            } else {
                if (msg.id === 'successMessage') {
                    msg.classList.remove('bg-green-200/50','text-green-800','border-green-300');
                    msg.classList.add('bg-green-600/20','text-green-300','border-green-500');
                } else {
                    msg.classList.remove('bg-red-200/50','text-red-800','border-red-300');
                    msg.classList.add('bg-red-600/20','text-red-300','border-red-500');
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
