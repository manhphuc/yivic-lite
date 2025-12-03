<?php

namespace Yivic\YivicLite\Theme\Interfaces;

/**
 * Contract for WordPress themes using the Yivic theme kernel.
 *
 * Implement this on your main theme class so it can
 * register hooks, services, etc.
 */
interface WPThemeInterface {
    /**
     * Register all hooks and bootstrap the theme.
     */
    public function initTheme(): void;
}