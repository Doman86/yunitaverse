import Alpine from 'alpinejs';

window.Alpine = Alpine;

/**
 * Active locale, injected from the server on every page.
 */
const LOCALE = document.documentElement.lang === 'id' ? 'id' : 'en';

/**
 * Realtime clock + date (site timezone). Never from the database.
 * Date format follows the active language.
 */
Alpine.data('clock', (timezone = 'Asia/Jakarta') => ({
    time: '--:--:--',
    date: '',
    init() {
        const tick = () => {
            const now = new Date();

            this.time = now.toLocaleTimeString(LOCALE === 'id' ? 'id-ID' : 'en-GB', {
                timeZone: timezone,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false,
            });

            this.date = now.toLocaleDateString(LOCALE === 'id' ? 'id-ID' : 'en-GB', {
                timeZone: timezone,
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric',
            });
        };

        tick();
        setInterval(tick, 1000);
    },
}));

/**
 * Hidden admin access: tap the moon on the landing page 3 times.
 * Never advertised anywhere on the site.
 */
Alpine.data('secretMoon', (target, hint, unlockedHint) => ({
    clicks: 0,
    hint: '',
    timer: null,
    unlocked: false,
    hit() {
        if (this.unlocked) {
            return;
        }
        this.clicks++;
        clearTimeout(this.timer);
        this.timer = setTimeout(() => {
            this.clicks = 0;
            this.hint = '';
        }, 1600);

        if (this.clicks === 2) {
            this.hint = hint;
        }

        if (this.clicks >= 3) {
            this.unlocked = true;
            this.hint = unlockedHint;
            setTimeout(() => {
                window.location.href = target;
            }, 700);
        }
    },
}));

/**
 * Surprise box: draw one random surprise for the chosen mood.
 * typeLabels maps a surprise type key to a translated label from the server.
 */
Alpine.data('surpriseBox', (mood = null, typeLabels = {}) => ({
    item: null,
    loading: false,
    typeLabels,
    async draw() {
        if (this.loading) {
            return;
        }
        this.loading = true;
        try {
            const query = mood ? `?mood=${encodeURIComponent(mood)}` : '';
            const res = await fetch(`/mod/surprise${query}`, {
                headers: { Accept: 'application/json' },
            });
            const data = await res.json();
            this.item = null;
            setTimeout(() => {
                this.item = data;
                this.loading = false;
            }, 300);
        } catch {
            this.loading = false;
        }
    },
}));

/**
 * Minimal lightbox for photos.
 */
Alpine.data('lightbox', () => ({
    open: false,
    src: '',
    title: '',
    caption: '',
    show(src, title = '', caption = '') {
        this.src = src;
        this.title = title;
        this.caption = caption;
        this.open = true;
    },
    close() {
        this.open = false;
    },
}));

/**
 * Reveal-on-scroll helper for any [data-reveal] element.
 */
function initReveal() {
    const els = document.querySelectorAll('[data-reveal]');
    if (!('IntersectionObserver' in window)) {
        els.forEach((el) => el.classList.add('is-revealed'));
        return;
    }
    const io = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-revealed');
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -6% 0px' });
    els.forEach((el) => io.observe(el));
}

document.addEventListener('DOMContentLoaded', initReveal);

Alpine.start();
