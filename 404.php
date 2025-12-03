<?php
/**
 * 404 Template
 *
 * Fallback template used when no content is found for the requested URL.
 */

defined( 'ABSPATH' ) || exit;

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

/**
 * Header markup + opening <body> are rendered
 * by header.php and the header partial.
 */
get_header();

// Render dedicated 404 page view.
echo YivicLite_WP_Theme::view()->render(
    'views/pages/404',
    []
);

/**
 * Footer markup + closing tags come
 * from footer.php and the footer partial.
 */
get_footer();