<?php
/**
 * 404 page wrapper.
 *
 * Wraps the “not found” message inside the main layout
 * (header + footer are handled by header.php/footer.php).
 */

defined( 'ABSPATH' ) || exit;

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

// Render the inner 404 content view.
$notFoundContent = YivicLite_WP_Theme::view()->render(
    'views/posts/404',
    []
);

// Inject into the main layout.
echo YivicLite_WP_Theme::view()->render(
    'views/layouts/main',
    [
        'content' => $notFoundContent,
    ]
);