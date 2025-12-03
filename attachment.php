<?php
/**
 * Attachment Template
 *
 * Displays a single media attachment (image, document, etc.).
 */

defined( 'ABSPATH' ) || exit;

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

/**
 * Header markup + opening <body> are rendered
 * by header.php and the header partial.
 */
get_header();

// Render dedicated attachment view.
echo YivicLite_WP_Theme::view()->render(
    'views/pages/attachment',
    [
        'query' => $wp_query,
    ]
);

/**
 * Footer markup + closing tags come
 * from footer.php and the footer partial.
 */
get_footer();