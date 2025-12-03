<?php
/**
 * Date Archive Template
 *
 * Displays posts for a specific day, month or year.
 */

defined( 'ABSPATH' ) || exit;

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

/**
 * Header markup + opening <body> are rendered
 * by header.php and the header partial.
 */
get_header();

// Render dedicated date archive view.
echo YivicLite_WP_Theme::view()->render(
    'views/pages/date',
    [
        'query' => $wp_query,
    ]
);

/**
 * Footer markup + closing tags come
 * from footer.php and the footer partial.
 */
get_footer();