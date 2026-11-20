document.addEventListener('DOMContentLoaded', () => {
    const sidebarToggle = document.querySelector('[data-sidebar-toggle]');
    const sidebarBackdrop = document.querySelector('[data-sidebar-backdrop]');

    const setSidebarState = (open) => {
        document.body.classList.toggle('sidebar-open', open);
        sidebarToggle?.setAttribute('aria-expanded', String(open));
        sidebarToggle?.setAttribute('aria-label', open ? 'Fechar menu' : 'Abrir menu');
    };

    sidebarToggle?.addEventListener('click', () => {
        setSidebarState(! document.body.classList.contains('sidebar-open'));
    });

    sidebarBackdrop?.addEventListener('click', () => setSidebarState(false));

    document.querySelectorAll('.sidebar a').forEach((link) => {
        link.addEventListener('click', () => setSidebarState(false));
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setSidebarState(false);
        }
    });

    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (! window.confirm(form.dataset.confirm)) {
                event.preventDefault();
                return;
            }

            const button = form.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = true;
                button.textContent = 'Processando...';
            }
        });
    });
});
