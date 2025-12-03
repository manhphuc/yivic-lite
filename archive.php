<?php
/**
 * Archive Template
 *
 * Handles category, tag, author, date, custom taxonomy,
 * and all other archive-based listings.
 */

defined('ABSPATH') || exit;

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

/**
 * Header markup + opening <body>
 */
get_header();

// Render archive wrapper view
echo YivicLite_WP_Theme::view()->render(
    'views/pages/archive',
    [
        'query' => $wp_query,
    ]
);

/**
 * Footer markup + closing </body> + scripts
 */
get_footer();