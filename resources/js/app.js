import '../css/app.css';

const openModalIds = new Set();

const syncModalScrollLock = () => {
    const hasOpenModal = openModalIds.size > 0;
    document.documentElement.classList.toggle('platepal-modal-open', hasOpenModal);
    document.body.classList.toggle('platepal-modal-open', hasOpenModal);
};

window.PlatePalModals = {
    toggle(id, isOpen) {
        if (!id) {
            return;
        }

        if (isOpen) {
            openModalIds.add(id);
        } else {
            openModalIds.delete(id);
        }

        syncModalScrollLock();
    },
    reset() {
        openModalIds.clear();
        syncModalScrollLock();
    },
};

window.addEventListener('pagehide', () => window.PlatePalModals.reset());
document.addEventListener('livewire:navigating', () => window.PlatePalModals.reset());

const isPlainLeftClick = (event) => {
    return event.button === 0 && !event.metaKey && !event.ctrlKey && !event.shiftKey && !event.altKey;
};

const isNavigableUrl = (url) => {
    if (url.origin !== window.location.origin) {
        return false;
    }

    if (url.pathname === window.location.pathname && url.search === window.location.search && url.hash) {
        return false;
    }

    return !/\.[a-z0-9]{2,8}$/i.test(url.pathname);
};

const navigateWithLivewire = (url) => {
    if (window.Livewire?.navigate) {
        window.Livewire.navigate(url.toString());
        return true;
    }

    return false;
};

const optsOutOfSpaNavigation = (element) => {
    return element.closest('[data-no-spa], [data-no-navigate]')
        || element.hasAttribute('data-no-spa')
        || element.hasAttribute('data-no-navigate');
};

window.addEventListener('livewire:http-status:401', () => {
    window.location.assign('/login');
});

window.addEventListener('livewire:http-status:419', () => {
    window.location.assign('/login');
});

document.addEventListener('click', (event) => {
    if (event.defaultPrevented || !isPlainLeftClick(event)) {
        return;
    }

    const link = event.target.closest('a[href]');

    if (!link || link.target && link.target !== '_self' || link.hasAttribute('download')) {
        return;
    }

    if (optsOutOfSpaNavigation(link)) {
        return;
    }

    const href = link.getAttribute('href') || '';

    if (href.startsWith('#') || /^(mailto|tel|javascript):/i.test(href)) {
        return;
    }

    const url = new URL(link.href, window.location.href);

    if (!isNavigableUrl(url)) {
        return;
    }

    if (navigateWithLivewire(url)) {
        event.preventDefault();
    }
});

document.addEventListener('submit', (event) => {
    if (event.defaultPrevented) {
        return;
    }

    const form = event.target;

    if (!(form instanceof HTMLFormElement) || optsOutOfSpaNavigation(form)) {
        return;
    }

    if ((form.getAttribute('method') || 'GET').toUpperCase() !== 'GET' || form.target && form.target !== '_self') {
        return;
    }

    const url = new URL(form.action || window.location.href, window.location.href);

    if (!isNavigableUrl(url)) {
        return;
    }

    const params = new URLSearchParams(new FormData(form));
    url.search = params.toString();

    if (navigateWithLivewire(url)) {
        event.preventDefault();
    }
});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

// import './echo';
