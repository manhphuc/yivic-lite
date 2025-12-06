<?php

namespace Yivic\YivicLite\Theme\Helpers;

/**
 * Layout helper for Yivic Lite theme.
 *
 * This class encapsulates logic used to determine
 * whether the sidebar should be displayed and
 * which CSS class layout should be applied to the
 * main content area and sidebar wrapper.
 *
 * The returned structure is used by template files
 * (single.php, page.php, archive.php, search.php, etc.)
 * to conditionally render the sidebar and grid classes.
 */
class YivicLiteHelper {

    /**
     * Get layout configuration for main content and sidebar.
     *
     * This method reads the Customizer setting:
     *   - yivic_lite_sidebar_display (true/false)
     *
     * When enabled, the layout is split into:
     *   - main : 8 columns (desktop)
     *   - sidebar : 4 columns
     *
     * When disabled, the main content becomes full width.
     *
     * @return array{
     *     has_sidebar: bool,
     *     main: string,
     *     sidebar: string
     * }
     */
    public static function getLayoutColumns(): array {

        // Read value from theme mods (Customizer).
        // Default = true (sidebar visible)
        $sidebar_enabled = (bool) get_theme_mod( 'yivic_lite_show_sidebar', true );

        // Sidebar ON → 8/12 main + 4/12 sidebar
        if ( $sidebar_enabled ) {
            return [
                'has_sidebar' => true,
                'main'       => 'yivic-lite-layout__main col l-8 m-12 c-12',
                'sidebar'    => 'yivic-lite-layout__sidebar col l-4 m-12 c-12',
            ];
        }

        // Sidebar OFF → full width main
        return [
            'has_sidebar' => false,
            'main'       => 'yivic-lite-layout__main col l-12 m-12 c-12',
            'sidebar'    => '',
        ];
    }
}