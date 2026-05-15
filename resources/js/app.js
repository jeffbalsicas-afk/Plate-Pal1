import '../css/app.css';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

window.addEventListener('pageshow', (event) => {
    if (event.persisted) {
        window.location.reload();
    }
});

document.addEventListener('livewire:navigated', () => {
    window.addEventListener('livewire:http-status:419', () => {
        window.location.href = '/login';
    }, { once: true });

    window.addEventListener('livewire:http-status:401', () => {
        window.location.href = '/login';
    }, { once: true });
});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

// import './echo';

const isPlainLeftClick = (event) => (
    event.button === 0
    && ! event.metaKey
    && ! event.ctrlKey
    && ! event.shiftKey
    && ! event.altKey
);

const shouldNavigateInstantly = (link, event) => {
    if (! link || ! isPlainLeftClick(event) || event.defaultPrevented) {
        return false;
    }

    if (
        link.target
        || link.hasAttribute('download')
        || link.hasAttribute('data-no-navigate')
        || link.getAttribute('href')?.startsWith('#')
    ) {
        return false;
    }

    const url = new URL(link.href, window.location.href);

    if (url.origin !== window.location.origin || ! ['http:', 'https:'].includes(url.protocol)) {
        return false;
    }

    if (url.pathname === window.location.pathname && url.search === window.location.search && url.hash) {
        return false;
    }

    return url.href !== window.location.href || url.hash !== window.location.hash;
};

document.addEventListener('click', (event) => {
    const link = event.target.closest('a[href]');

    if (! shouldNavigateInstantly(link, event) || ! window.Livewire?.navigate) {
        return;
    }

    event.preventDefault();
    window.Livewire.navigate(link.href);
});
