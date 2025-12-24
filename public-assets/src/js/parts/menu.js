/**
 * Yivic Lite – Header navigation interactions
 *
 * Features:
 * - Search panel toggle (click + auto-open on keyboard focus)
 * - Focus management & keyboard trap for search (Esc to close, Shift+Tab to exit backward)
 * - Mobile off-canvas menu toggle (click + auto-open on keyboard focus)
 * - Focus trap for mobile menu (Esc to close, Shift+Tab on first item closes)
 * - Logo Shift+Tab intercept (auto-opens mobile menu on backward navigation)
 * - Submenu toggle (accordion style) for all depths with accessible keyboard behavior
 * - Sticky header (adds body padding to prevent layout jump)
 *
 * Notes:
 * - This file is JS-only by design (no SCSS changes required).
 * - Uses event delegation where it improves performance and reliability.
 * - Avoids :scope to stay compatible with older browsers.
 */
(function () {
    'use strict';

    // Tiny DOM helpers (fast, scoped, no dependencies)
    const $ = (sel, ctx = document) => ctx.querySelector(sel);
    const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

    // Shared focusable selector (kept consistent across modules)
    const FOCUSABLE_SELECTOR =
        'a[href], button:not([disabled]), textarea:not([disabled]), input:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])';

    // Tracks whether the most recent navigation was keyboard-based (Tab).
    // Used to enable "auto-open on focus" only for keyboard users.
    let lastInteractionWasKeyboard = false;
    document.addEventListener(
        'keydown',
        (e) => {
            if (e.key === 'Tab') lastInteractionWasKeyboard = true;
        },
        true
    );
    document.addEventListener(
        'mousedown',
        () => {
            lastInteractionWasKeyboard = false;
        },
        true
    );

    /**
     * Return focusable descendants inside a container.
     * Filters out hidden/unfocusable nodes with a basic, cheap visibility check.
     */
    const getFocusable = (container) => {
        if (!container) return [];
        return $$(FOCUSABLE_SELECTOR, container).filter((el) => {
            if (!el || el.disabled) return false;
            if (el.getAttribute('aria-hidden') === 'true') return false;

            // Basic visibility check (good enough for header UI)
            if (el === document.activeElement) return true;
            return !!(el.offsetWidth || el.offsetHeight || el.getClientRects().length);
        });
    };

    /**
     * Return all focusable elements on the page (visible).
     * Used when we need to move focus outside a trapped region.
     */
    const getAllFocusable = () => getFocusable(document);

    // ==========================================================
    // Search toggle
    // A11y:
    // - Click toggles open/close
    // - Keyboard focus on icon auto-opens (only if navigating with Tab)
    // - Esc closes
    // - Tab trap inside search (icon + panel controls)
    // - Shift+Tab on the icon: On mobile -> focus logo, On desktop -> focus previous element
    // ==========================================================
    (function initSearchToggle() {
        const searchBox = $('.yivic-lite-header__search');
        const searchIcon = $('.yivic-lite-header__search-icon');

        if (!searchBox || !searchIcon) return;

        // Panel may be either a dedicated wrapper or the inline input container
        const searchPanel = $('#yivic-lite-search-panel') || $('.yivic-lite-header__search-input', searchBox);

        const isSearchOpen = () => searchBox.classList.contains('yivic-lite-header__search--open');

        const focusSearchInput = () => {
            if (!searchPanel) return;

            const input = searchPanel.querySelector('input[type="search"], input[name="s"], input');
            if (!input) return;

            // Focus after painting so caret shows reliably
            requestAnimationFrame(() => {
                input.focus({ preventScroll: true });
                try {
                    const len = input.value.length;
                    input.setSelectionRange(len, len);
                } catch (_) {}
            });
        };

        const openSearch = () => {
            if (isSearchOpen()) return;

            searchBox.classList.add('yivic-lite-header__search--open');
            searchIcon.classList.add('yivic-lite-header__search-icon--close');
            searchIcon.setAttribute('aria-expanded', 'true');

            if (searchPanel) {
                searchPanel.hidden = false;
                searchPanel.setAttribute('aria-hidden', 'false');
            }

            focusSearchInput();
        };

        const closeSearch = (restoreFocusToIcon = true) => {
            if (!isSearchOpen()) return;

            searchBox.classList.remove('yivic-lite-header__search--open');
            searchIcon.classList.remove('yivic-lite-header__search-icon--close');
            searchIcon.setAttribute('aria-expanded', 'false');

            if (searchPanel) {
                searchPanel.hidden = true;
                searchPanel.setAttribute('aria-hidden', 'true');
            }

            if (restoreFocusToIcon) searchIcon.focus();
        };

        /**
         * Move focus to the logo on mobile or previous visible element on desktop.
         * Used when exiting backward via Shift+Tab from the search icon.
         */
        const focusPrevOutsideSearch = () => {
            const menuToggle = $('#yivicMenuToggle');
            const logo = $('.yivic-lite-header__logo-link') || $('.custom-logo-link');

            // Check if on mobile (menu toggle is visible)
            const isMobile = menuToggle && (
                menuToggle.offsetWidth ||
                menuToggle.offsetHeight ||
                menuToggle.getClientRects().length
            );

            // On mobile: focus the logo directly to avoid hidden menu items
            if (isMobile && logo) {
                logo.focus();
                return;
            }

            // On desktop: find the previous visible focusable element
            const all = getAllFocusable();
            const idx = all.indexOf(searchIcon);
            if (idx <= 0) return;

            for (let i = idx - 1; i >= 0; i--) {
                if (!searchBox.contains(all[i])) {
                    all[i].focus();
                    return;
                }
            }
        };

        // Click toggles open/close
        searchIcon.addEventListener('click', () => {
            if (isSearchOpen()) closeSearch(true);
            else openSearch();
        });

        // Auto-open on keyboard focus
        searchIcon.addEventListener('focus', () => {
            if (!lastInteractionWasKeyboard) return;
            if (isSearchOpen()) return;
            openSearch();
        });

        // Esc closes (capture so it works even if focus is inside the panel)
        document.addEventListener(
            'keydown',
            (e) => {
                if (!isSearchOpen()) return;
                if (e.key !== 'Escape') return;

                e.preventDefault();
                closeSearch(true);
            },
            true
        );

        // Trap Tab / Shift+Tab while open
        document.addEventListener(
            'keydown',
            (e) => {
                if (!isSearchOpen()) return;
                if (e.key !== 'Tab') return;

                const scope = [searchIcon, ...getFocusable(searchPanel)].filter(Boolean);
                if (!scope.length) return;

                const active = document.activeElement;

                // Exit backward: Shift+Tab on icon closes and focuses previous element outside search
                if (e.shiftKey && active === searchIcon) {
                    e.preventDefault();
                    closeSearch(false);
                    requestAnimationFrame(focusPrevOutsideSearch);
                    return;
                }

                const idx = scope.indexOf(active);

                // If focus escaped, pull it back into scope
                if (idx === -1) {
                    e.preventDefault();
                    (e.shiftKey ? scope[scope.length - 1] : scope[0]).focus();
                    return;
                }

                // Loop inside scope
                if (e.shiftKey && idx === 0) {
                    e.preventDefault();
                    scope[scope.length - 1].focus();
                    return;
                }

                if (!e.shiftKey && idx === scope.length - 1) {
                    e.preventDefault();
                    scope[0].focus();
                }
            },
            true
        );
    })();

    // ==========================================================
    // Mobile menu (off-canvas)
    // A11y:
    // - Click toggles open/close
    // - Keyboard focus on toggle auto-opens
    // - Esc closes
    // - Focus trap inside menu while open
    // - Shift+Tab on the first focusable closes and returns focus to toggle
    // ==========================================================
    (function initMobileMenu() {
        const menuWrapper = $('.yivic-lite-header__links');
        const menuOpenBtn = $('#yivicMenuToggle');
        const menuCloseBtn = $('#yivicMenuClose');

        if (!menuWrapper || !menuOpenBtn || !menuCloseBtn) return;

        // Prevent re-opening when we programmatically focus the toggle after closing
        let suppressNextToggleFocusOpen = false;

        const isMenuOpen = () => menuWrapper.classList.contains('yivic-lite-header__links--open');

        const openMenu = () => {
            if (isMenuOpen()) return;

            menuWrapper.classList.add('yivic-lite-header__links--open');
            menuOpenBtn.setAttribute('aria-expanded', 'true');
            menuOpenBtn.setAttribute('aria-label', 'Close menu');

            // Focus the close button first (common pattern for off-canvas)
            menuCloseBtn.focus();
        };

        const closeMenu = () => {
            if (!isMenuOpen()) return;

            menuWrapper.classList.remove('yivic-lite-header__links--open');
            menuOpenBtn.setAttribute('aria-expanded', 'false');
            menuOpenBtn.setAttribute('aria-label', 'Open menu');

            suppressNextToggleFocusOpen = true;
            menuOpenBtn.focus();
        };

        // Click behavior
        menuOpenBtn.addEventListener('click', () => {
            if (isMenuOpen()) closeMenu();
            else openMenu();
        });

        menuCloseBtn.addEventListener('click', closeMenu);

        // Auto-open on keyboard focus
        menuOpenBtn.addEventListener('focus', () => {
            if (suppressNextToggleFocusOpen) {
                suppressNextToggleFocusOpen = false;
                return;
            }
            if (!lastInteractionWasKeyboard) return;
            openMenu();
        });

        // Esc closes
        document.addEventListener(
            'keydown',
            (e) => {
                if (!isMenuOpen()) return;
                if (e.key !== 'Escape') return;

                e.preventDefault();
                closeMenu();
            },
            true
        );

        // Trap Tab / Shift+Tab inside menu while open
        document.addEventListener(
            'keydown',
            (e) => {
                if (!isMenuOpen()) return;
                if (e.key !== 'Tab') return;

                const scope = getFocusable(menuWrapper);
                if (!scope.length) return;

                const first = scope[0];
                const last = scope[scope.length - 1];
                const active = document.activeElement;

                // If focus escaped, pull it back in
                if (!menuWrapper.contains(active)) {
                    e.preventDefault();
                    (e.shiftKey ? last : first).focus();
                    return;
                }

                // Shift+Tab on first => close menu and return focus to toggle
                if (e.shiftKey && active === first) {
                    e.preventDefault();
                    closeMenu();
                    return;
                }

                // Tab on last => loop to first
                if (!e.shiftKey && active === last) {
                    e.preventDefault();
                    first.focus();
                }
            },
            true
        );

        // Handle viewport resize: close mobile menu when switching between desktop/mobile
        let prevToggleVisible = menuOpenBtn.offsetWidth > 0 ||
            menuOpenBtn.offsetHeight > 0 ||
            menuOpenBtn.getClientRects().length > 0;

        window.addEventListener('resize', () => {
            const currentToggleVisible = menuOpenBtn.offsetWidth > 0 ||
                menuOpenBtn.offsetHeight > 0 ||
                menuOpenBtn.getClientRects().length > 0;

            // If toggle visibility changed (desktop ↔ mobile transition)
            if (prevToggleVisible !== currentToggleVisible) {
                // Close menu if it's open to prevent focus issues
                if (isMenuOpen()) {
                    menuWrapper.classList.remove('yivic-lite-header__links--open');
                    menuOpenBtn.setAttribute('aria-expanded', 'false');
                    menuOpenBtn.setAttribute('aria-label', 'Open menu');
                }

                // Fix focus when transitioning to mobile while focus is in menu
                // If current focus is inside the menu wrapper and we're switching to mobile
                const activeElement = document.activeElement;
                if (currentToggleVisible && menuWrapper.contains(activeElement)) {
                    // Move focus to logo to prevent focus getting lost in off-canvas menu
                    const logo = $('.yivic-lite-header__logo-link') || $('.custom-logo-link');
                    if (logo) {
                        requestAnimationFrame(() => logo.focus());
                    }
                }
            }

            prevToggleVisible = currentToggleVisible;
        });
    })();

    // ==========================================================
    // Logo/Site Title - Shift+Tab Intercept (Mobile)
    // Purpose: Fix focus loss when user presses Shift+Tab from logo
    //
    // Issue: On mobile, when user navigates backward (Shift+Tab) from
    // the site title/logo, focus gets lost into the hidden off-canvas menu
    // instead of opening the menu properly.
    //
    // Solution: Intercept Shift+Tab on logo link, auto-open mobile menu,
    // and place focus on the last focusable item inside the menu.
    //
    // Behavior:
    // - Only activates on mobile (when toggle button is visible)
    // - Prevents default backward tab navigation
    // - Opens the mobile menu automatically
    // - Moves focus to the last menu item for natural backward flow
    // ==========================================================
    (function initLogoShiftTabFix() {
        const logo = $('.yivic-lite-header__logo-link') || $('.custom-logo-link');
        const menuWrapper = $('.yivic-lite-header__links');
        const menuOpenBtn = $('#yivicMenuToggle');

        if (!logo || !menuWrapper || !menuOpenBtn) return;

        const isMenuOpen = () => menuWrapper.classList.contains('yivic-lite-header__links--open');

        const openMenu = () => {
            if (isMenuOpen()) return;
            menuWrapper.classList.add('yivic-lite-header__links--open');
            menuOpenBtn.setAttribute('aria-expanded', 'true');
            menuOpenBtn.setAttribute('aria-label', 'Close menu');
        };

        /**
         * Check if mobile menu toggle button is currently visible.
         * Used to detect mobile viewport where this fix should activate.
         */
        const isToggleVisible = () => {
            return !!(
                menuOpenBtn.offsetWidth ||
                menuOpenBtn.offsetHeight ||
                menuOpenBtn.getClientRects().length
            );
        };

        // Intercept Shift+Tab on logo/site title
        logo.addEventListener('keydown', (e) => {
            // Only handle Shift+Tab key combination
            if (e.key !== 'Tab' || !e.shiftKey) return;

            // Only activate on mobile (when toggle button is visible)
            if (!isToggleVisible()) return;

            // Prevent default backward tab behavior to avoid focus getting lost
            e.preventDefault();

            // Open the mobile menu
            openMenu();

            // Move focus to the last focusable item in the menu after paint
            requestAnimationFrame(() => {
                const focusables = getFocusable(menuWrapper);
                if (focusables.length > 0) {
                    focusables[focusables.length - 1].focus();
                }
            });
        });
    })();

    // ==========================================================
    // Submenu toggle (accordion style, all depths)
    // A11y:
    // - Click on arrow toggles submenu
    // - Keyboard focus on arrow auto-opens (Tab navigation only)
    // - Tab on arrow enters submenu (first item)
    // - Shift+Tab can enter submenu last item ONLY when coming from outside that submenu
    // - Shift+Tab on first submenu item returns to the parent arrow (so you can go back to parent link)
    // - Focus leaving a <li> closes that <li> (after a microtask) to avoid flicker
    //
    // UX:
    // - Accordion behavior per level: opening a submenu closes its sibling submenus at the same depth.
    // ==========================================================
    (function initSubmenus() {
        const menuRoot = $('.yivic-lite-header__menu');
        if (!menuRoot) return;

        const ARROW_SELECTOR = '.yivic-lite-header__arrow, .yivic-arrow, .yivic-dropdownsIcon';
        const isArrow = (el) => el && el.closest && el.closest(ARROW_SELECTOR);

        // Track focus history so we can detect "came from submenu"
        let prevFocusedEl = null;
        let currentFocusedEl = null;
        document.addEventListener(
            'focusin',
            (e) => {
                prevFocusedEl = currentFocusedEl;
                currentFocusedEl = e.target;
            },
            true
        );

        /**
         * Return the direct submenu (<ul class="sub-menu">) of a given <li>.
         * Avoids :scope for broader browser compatibility.
         */
        const getDirectSubmenu = (li) => {
            if (!li) return null;
            for (const child of li.children) {
                if (child && child.tagName === 'UL' && child.classList.contains('sub-menu')) {
                    return child;
                }
            }
            return null;
        };

        /**
         * Return the arrow button that belongs to this <li> (not nested deeper).
         */
        const getArrowInLi = (li) => {
            if (!li) return null;
            const arrows = li.querySelectorAll(ARROW_SELECTOR);
            for (const a of arrows) {
                if (a.closest('li') === li) return a;
            }
            return null;
        };

        const openLi = (li) => {
            if (!li) return;
            li.classList.add('yivic-lite-header__item--open');
            const arrow = getArrowInLi(li);
            if (arrow) arrow.setAttribute('aria-expanded', 'true');
        };

        const closeLi = (li) => {
            if (!li) return;
            li.classList.remove('yivic-lite-header__item--open');
            const arrow = getArrowInLi(li);
            if (arrow) arrow.setAttribute('aria-expanded', 'false');
        };

        /**
         * Close sibling <li> submenus at the same depth (accordion behavior).
         * Also closes any open descendants inside those siblings to avoid multi-open stacks.
         */
        const closeSiblingLis = (li) => {
            if (!li) return;

            const parentUl = li.parentElement;
            if (!parentUl) return;

            const siblings = Array.from(parentUl.children).filter(
                (node) => node && node.tagName === 'LI' && node !== li
            );

            siblings.forEach((sib) => {
                closeLi(sib);

                const openDescendants = sib.querySelectorAll('li.yivic-lite-header__item--open');
                openDescendants.forEach((d) => closeLi(d));
            });
        };

        /**
         * Open a <li> and enforce accordion behavior at the same depth.
         */
        const openLiAccordion = (li) => {
            closeSiblingLis(li);
            openLi(li);
        };

        // Click toggles submenu (event delegation)
        menuRoot.addEventListener('click', (e) => {
            const arrow = e.target.closest(ARROW_SELECTOR);
            if (!arrow || !menuRoot.contains(arrow)) return;

            e.preventDefault();

            const li = arrow.closest('li');
            if (!li) return;

            // Toggle with accordion policy:
            // - opening closes siblings
            // - closing affects only the current branch
            if (li.classList.contains('yivic-lite-header__item--open')) {
                closeLi(li);
                return;
            }

            openLiAccordion(li);
        });

        // Auto-open when arrow receives focus via keyboard Tab navigation
        menuRoot.addEventListener('focusin', (e) => {
            if (!lastInteractionWasKeyboard) return;

            const arrow = e.target.closest(ARROW_SELECTOR);
            if (!arrow || !menuRoot.contains(arrow)) return;

            const li = arrow.closest('li');
            if (!li) return;

            openLiAccordion(li);
        });

        // Handle Tab / Shift+Tab on arrow to enter submenu (with "no trap" on reverse from submenu)
        menuRoot.addEventListener(
            'keydown',
            (e) => {
                if (e.key !== 'Tab') return;

                const arrow = isArrow(e.target);
                if (!arrow || !menuRoot.contains(arrow)) return;

                const li = arrow.closest('li');
                if (!li) return;

                const submenu = getDirectSubmenu(li);
                if (!submenu) return;

                // Keep submenu open before moving focus in, and close siblings (accordion)
                openLiAccordion(li);

                const focusables = getFocusable(submenu);
                if (!focusables.length) return;

                // Tab -> first submenu item
                if (!e.shiftKey) {
                    e.preventDefault();
                    focusables[0].focus();
                    return;
                }

                // Shift+Tab behavior:
                // If focus came from inside THIS submenu, do NOT force focus back in.
                // Let the browser default Shift+Tab arrow -> parent <a>.
                const cameFromThisSubmenu = !!(prevFocusedEl && submenu.contains(prevFocusedEl));
                if (cameFromThisSubmenu) return;

                // Otherwise, allow Shift+Tab to enter submenu last item (useful when navigating backward)
                e.preventDefault();
                focusables[focusables.length - 1].focus();
            },
            true
        );

        // Shift+Tab on first submenu item returns focus to the parent arrow
        menuRoot.addEventListener(
            'keydown',
            (e) => {
                if (e.key !== 'Tab' || !e.shiftKey) return;

                const target = e.target;
                if (!target || !menuRoot.contains(target)) return;

                const submenu = target.closest('ul.sub-menu');
                if (!submenu) return;

                const li = submenu.closest('li');
                if (!li) return;

                const focusables = getFocusable(submenu);
                if (!focusables.length) return;

                if (target === focusables[0]) {
                    const arrow = getArrowInLi(li);
                    if (!arrow) return;

                    e.preventDefault();
                    arrow.focus();
                }
            },
            true
        );

        // Auto-close an <li> when focus leaves it (including its submenu)
        menuRoot.addEventListener('focusout', (e) => {
            const fromEl = e.target;
            if (!fromEl || !fromEl.closest) return;

            const li = fromEl.closest('li');
            if (!li) return;

            const toEl = e.relatedTarget;

            // If focus stays inside this li, keep it open
            if (toEl && li.contains(toEl)) return;

            // Close after a tick to avoid flicker during rapid focus transitions
            setTimeout(() => {
                const active = document.activeElement;
                if (active && li.contains(active)) return;
                closeLi(li);
            }, 0);
        });
    })();

    // ==========================================================
    // Sticky header
    // - Adds a fixed class once the header reaches the top
    // - Adds body padding equal to header height to prevent jump
    // ==========================================================
    (function initStickyHeader() {
        const stickyBar = document.getElementById('yivicSticky');
        if (!stickyBar) return;

        const getAdminBarOffset = () => {
            if (!document.body.classList.contains('admin-bar')) return 0;
            return window.innerWidth <= 782 ? 46 : 32;
        };

        let startY = null;

        const applyAdminOffset = () => {
            const offset = getAdminBarOffset();
            document.documentElement.style.setProperty('--yivic-adminbar-offset', offset + 'px');
        };

        const computeStartY = () => {
            // Calculate the top position of stickyBar relative to document
            const rect = stickyBar.getBoundingClientRect();
            startY = rect.top + window.scrollY;
        };

        const handle = () => {
            if (startY === null) computeStartY();

            if (window.scrollY >= startY) {
                if (!document.body.classList.contains('yivic-lite-header--fixed')) {
                    document.body.classList.add('yivic-lite-header--fixed');
                    document.body.style.paddingTop = stickyBar.offsetHeight + 'px';
                }
            } else {
                if (document.body.classList.contains('yivic-lite-header--fixed')) {
                    document.body.classList.remove('yivic-lite-header--fixed');
                    document.body.style.paddingTop = '';
                }
            }
        };

        applyAdminOffset();
        computeStartY();
        handle();

        window.addEventListener('scroll', handle, { passive: true });
        window.addEventListener('resize', () => {
            applyAdminOffset();
            computeStartY();
            handle();
        }, { passive: true });

        window.addEventListener('load', () => {
            applyAdminOffset();
            computeStartY();
            handle();
        });
    })();

    // =============================================================
    // Keyboard-mode detection (A11y)
    // - Adds `html.yivic-kbd` when the user navigates via keyboard
    // - Removes it when the user interacts via pointer (mouse/touch)
    // - Used to show focus rings only for keyboard users
    // =============================================================
    (() => {
        const ROOT = document.documentElement;
        const CLASS_KBD = 'yivic-kbd';

        // Keys that indicate keyboard navigation intent.
        const KBD_KEYS = new Set([
            'Tab',
            'ArrowUp',
            'ArrowDown',
            'ArrowLeft',
            'ArrowRight',
            'Enter',
            ' ',
            'Escape',
        ]);

        const enableKbdMode = () => ROOT.classList.add(CLASS_KBD);
        const disableKbdMode = () => ROOT.classList.remove(CLASS_KBD);

        document.addEventListener(
            'keydown',
            (e) => {
                if (KBD_KEYS.has(e.key)) enableKbdMode();
            },
            true
        );

        // Use pointer events (covers mouse + touch + pen).
        document.addEventListener('pointerdown', disableKbdMode, true);
    })();

})();