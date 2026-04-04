function initSearchModal(): void {
    const details = document.querySelector(
        'details.header__seach--details',
    ) as HTMLDetailsElement | null;

    const closeBtn = document.querySelector(
        '[data-close-search-popup]',
    ) as HTMLElement | null;

    const quickSearchWrap = document.querySelector(
        'quick-search.quickSearchResultsWrap',
    ) as HTMLElement | null;

    // 🔑 FIX CRÍTICO: El <button> dentro del <form> dentro del <summary>
    // hace submit del form antes de que el <details> se abra.
    // Hay que interceptarlo en la fase de captura para bloquearlo primero.
    const searchButton = document.querySelector(
        '.header__search-full .search__button',
    ) as HTMLButtonElement | null;

    if (!details) {
        console.warn('No se encontró details.header__seach--details');
        return;
    }

    const applyOpenStyles = (): void => {
        document.body.classList.add('open-search');
        quickSearchWrap?.classList.add('is-show');
        quickSearchWrap?.classList.remove('is-hidden');
    };

    const applyCloseStyles = (): void => {
        document.body.classList.remove('open-search');
        quickSearchWrap?.classList.remove('is-show');
        quickSearchWrap?.classList.add('is-hidden');
    };

    const closeModal = (): void => {
        details.removeAttribute('open');
        applyCloseStyles();
    };

    // Escuchar el evento nativo del <details> — esto es más confiable
    // que manipular el atributo open directamente
    details.addEventListener('toggle', () => {
        if (details.open) {
            applyOpenStyles();

            // Hacer foco en el input de búsqueda al abrir
            const input = details.querySelector(
                '.search__input',
            ) as HTMLInputElement | null;
            input?.focus();
        } else {
            applyCloseStyles();
        }
    });

    // 🔑 FIX: Interceptar el click del botón de búsqueda en fase de captura
    // para evitar que el <form> haga navigate a /search
    searchButton?.addEventListener(
        'click',
        (e: MouseEvent) => {
            e.preventDefault();
            e.stopPropagation();

            // Abrir el details manualmente
            if (!details.open) {
                details.setAttribute('open', '');
            }
        },
        true, // useCapture = true, se ejecuta ANTES del submit del form
    );

    // Botón X para cerrar
    closeBtn?.addEventListener('click', (e: MouseEvent) => {
        e.preventDefault();
        e.stopPropagation();
        closeModal();
    });

    // Escape para cerrar
    document.addEventListener('keydown', (e: KeyboardEvent) => {
        if (e.key === 'Escape' && details.open) {
            closeModal();
        }
    });

    // Click fuera para cerrar
    document.addEventListener('click', (e: MouseEvent) => {
        const target = e.target as Node | null;
        if (!target) return;
        if (details.open && !details.contains(target)) {
            closeModal();
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSearchModal);
} else {
    initSearchModal();
}

export default initSearchModal;
