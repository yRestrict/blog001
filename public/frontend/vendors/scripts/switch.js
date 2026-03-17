document.addEventListener('DOMContentLoaded', function () {
    /**
     * 1. DARK MODE LOGIC
     */
    const toggleSwitch = document.querySelector('.theme-switch input[type="checkbox"]');
    const logoDark = document.querySelector('.logo-dark');
    const logoWhite = document.querySelector('.logo-white');
    const currentTheme = localStorage.getItem('theme');

    if (currentTheme) {
        document.documentElement.setAttribute('data-theme', currentTheme);
        if (currentTheme === 'dark') {
            if (toggleSwitch) toggleSwitch.checked = true;
            document.body.classList.add("dark");
            if (logoDark) logoDark.classList.add('display-none');
            if (logoWhite) logoWhite.classList.add('display-block');
        }
    }

    if (toggleSwitch) {
        toggleSwitch.addEventListener('change', function (e) {
            if (e.target.checked) {
                document.documentElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                document.body.classList.add('dark');
                if (logoDark) logoDark.classList.add('display-none');
                if (logoWhite) logoWhite.classList.add('display-block');
            } else {
                document.documentElement.setAttribute('data-theme', 'light');
                localStorage.setItem('theme', 'light');
                document.body.classList.remove('dark');
                if (logoDark) logoDark.classList.remove('display-none');
                if (logoWhite) logoWhite.classList.remove('display-block');
            }
        });
    }

    /**
     * 2. TOGGLE PASSWORD VISIBILITY
     */
    document.querySelectorAll('.togglePassword').forEach(btn => {
        const inputGroup = btn.closest('.input-group');
        if (!inputGroup) return;

        const input = inputGroup.querySelector('.js-password');
        const icon = btn.querySelector('i');
        if (!input || !icon) return;

        // Função para mostrar/esconder o botão do olho
        const checkInputStatus = () => {
            btn.style.display = input.value.length > 0 ? 'flex' : 'none';
        };

        // Verifica o estado inicial (ex: se o navegador preencheu a senha automaticamente)
        checkInputStatus();

        // Monitora digitação
        input.addEventListener('input', checkInputStatus);

        // Ação de clique
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });

    /**
     * 3. SEARCH OVERLAY LOGIC
     */
    const searchToggle = document.querySelector('.search-toggle');
    const searchOverlay = document.querySelector('.search-overlay');
    const searchClose = document.querySelector('.search-close');
    const searchInput = searchOverlay ? searchOverlay.querySelector('input') : null;

    if (searchToggle && searchOverlay) {
        searchToggle.addEventListener('click', () => {
            searchOverlay.classList.remove('d-none');
            if (searchInput) setTimeout(() => searchInput.focus(), 10);
        });

        if (searchClose) {
            searchClose.addEventListener('click', () => searchOverlay.classList.add('d-none'));
        }

        searchOverlay.addEventListener('click', (e) => {
            if (e.target === searchOverlay) searchOverlay.classList.add('d-none');
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !searchOverlay.classList.contains('d-none')) {
                searchOverlay.classList.add('d-none');
            }
        });
    }

});