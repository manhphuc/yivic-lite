<?php

/**
 * Single Post Template
 *
 * Fallback for displaying a single post entry.
 */

defined('ABSPATH') || exit;

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

/**
 * Header markup + opening <body> are rendered
 * by header.php and the header partial.
 */
get_header();

// Render dedicated single page view.
echo YivicLite_WP_Theme::view()->render(
    'views/pages/single',
    [
        'query' => $wp_query,
    ]
);

/**
 * Footer markup + closing tags come
 * from footer.php and the footer partial.
 */
get_footer();