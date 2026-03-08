document.addEventListener('DOMContentLoaded', () => {
    // Toggle dropdowns
    document.querySelectorAll('[data-dropdown]').forEach(trigger => {
        const targetId = trigger.getAttribute('data-dropdown');
        const target = document.getElementById(targetId);
        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            target?.classList.toggle('open');
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown.open').forEach(d => d.classList.remove('open'));
    });

    // Simple confirm
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', (e) => {
            const msg = el.getAttribute('data-confirm') || 'Are you sure?';
            if (!confirm(msg)) {
                e.preventDefault();
            }
        });
    });

    // Auto-hide flash messages
    const flash = document.querySelector('.flash');
    if (flash) {
        setTimeout(() => flash.classList.add('hide'), 3500);
    }
});
