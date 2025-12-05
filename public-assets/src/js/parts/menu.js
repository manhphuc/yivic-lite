/**
 * Yivic Lite – Header navigation interactions
 * - Mobile menu toggle
 * - Search box toggle
 * - Submenu toggle (accordion style)
 * - Sticky header
 */

(function () {
    const $  = (sel, ctx = document) => ctx.querySelector(sel);
    const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

    // =========================
    // Search toggle
    // =========================
    const searchBox  = $('.yivic-lite-header__search');
    const searchIcon = $('.yivic-lite-header__search-icon');

    if (searchBox && searchIcon) {
        searchIcon.addEventListener('click', () => {
            const isOpen = searchBox.classList.toggle('yivic-lite-header__search--open');

            // toggle close icon state
            searchIcon.classList.toggle('yivic-lite-header__search-icon--close', isOpen);
            searchIcon.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }

    // =========================
    // Mobile menu (off–canvas)
    // =========================
    const menuWrapper  = $('.yivic-lite-header__links');
    const menuOpenBtn  = $('#yivicMenuToggle');
    const menuCloseBtn = $('#yivicMenuClose');

    if (menuWrapper && menuOpenBtn && menuCloseBtn) {
        const openMenu = () => {
            menuWrapper.classList.add('yivic-lite-header__links--open');
            menuOpenBtn.setAttribute('aria-expanded', 'true');
        };

        const closeMenu = () => {
            menuWrapper.classList.remove('yivic-lite-header__links--open');
            menuOpenBtn.setAttribute('aria-expanded', 'false');
        };

        menuOpenBtn.addEventListener('click', openMenu);
        menuCloseBtn.addEventListener('click', closeMenu);
    }

    // =========================
    // Submenu toggle (accordion style, all devices)
    // - Uses event delegation on the menu container
    // - Works for all levels (any <li> with arrow inside)
    // =========================
    const menuRoot = $('.yivic-lite-header__menu');
    if (menuRoot) {
        menuRoot.addEventListener('click', (event) => {
            const arrow = event.target.closest('.yivic-lite-header__arrow, .yivic-arrow');
            if (!arrow || !menuRoot.contains(arrow)) {
                return;
            }

            event.preventDefault();

            const li = arrow.closest('li');
            if (!li) return;

            const isOpen = li.classList.toggle('yivic-lite-header__item--open');
            arrow.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }

    // =========================
    // Sticky header
    // =========================
    const stickyBar = $('#yivicSticky');

    if (stickyBar) {
        const navTop = stickyBar.offsetTop;

        const handleScroll = () => {
            if (window.scrollY >= navTop) {
                document.body.style.paddingTop = stickyBar.offsetHeight + 'px';
                document.body.classList.add('yivic-lite-header--fixed');
            } else {
                document.body.style.paddingTop = '0';
                document.body.classList.remove('yivic-lite-header--fixed');
            }
        };

        window.addEventListener('scroll', handleScroll, { passive: true });
    }
})();