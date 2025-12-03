<?php
/**
 * Tag Archive Template
 *
 * Displays a list of posts for the current tag.
 */

defined( 'ABSPATH' ) || exit;

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

/**
 * Header markup + opening <body> are rendered
 * by header.php and the header partial.
 */
get_header();

// Render dedicated tag page view.
echo YivicLite_WP_Theme::view()->render(
    'views/pages/tag',
    [
        'query' => $wp_query,
    ]
);

/**
 * Footer markup + closing tags come
 * from footer.php and the footer partial.
 */
get_footer();