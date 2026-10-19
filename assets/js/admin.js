/**
 * Watermelon Lab — Admin UI helpers
 */

(() => {
    const toggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');

    if (toggle && sidebar) {
        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('is-open');
        });

        document.addEventListener('click', (event) => {
            if (
                sidebar.classList.contains('is-open') &&
                !sidebar.contains(event.target) &&
                !toggle.contains(event.target)
            ) {
                sidebar.classList.remove('is-open');
            }
        });
    }

    document.querySelectorAll('[data-open-modal]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-open-modal');
            const modal = document.getElementById(id);
            if (modal) modal.classList.add('is-open');
        });
    });

    document.querySelectorAll('[data-close-modal]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const modal = btn.closest('.modal-backdrop');
            if (modal) modal.classList.remove('is-open');
        });
    });

    document.querySelectorAll('.modal-backdrop').forEach((backdrop) => {
        backdrop.addEventListener('click', (event) => {
            if (event.target === backdrop) backdrop.classList.remove('is-open');
        });
    });

    // Prefill edit modals from data-* attributes
    document.querySelectorAll('[data-edit]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const modalId = btn.getAttribute('data-open-modal');
            const modal = document.getElementById(modalId);
            if (!modal) return;

            modal.querySelectorAll('[data-field]').forEach((field) => {
                const key = field.getAttribute('data-field');
                if (!key) return;
                const value = btn.getAttribute('data-' + key);
                if (value !== null) {
                    field.value = value;
                }
            });
        });
    });
})();
