<?php
/**
 * Static page wrapper view.
 *
 * This view is responsible for wrapping a single page
 * inside the main layout (header + footer are handled
 * by header.php/footer.php and the partials).
 *
 * @var WP_Query $query
 */

defined( 'ABSPATH' ) || exit;

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

// Render the page content view.
$pageContent = YivicLite_WP_Theme::view()->render(
    'views/posts/page',
    [
        'query' => $query ?? null,
    ]
);

// Inject into the main layout.
echo YivicLite_WP_Theme::view()->render(
    'views/layouts/main',
    [
        'content' => $pageContent,
    ]
);