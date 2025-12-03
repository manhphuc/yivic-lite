<?php
/**
 * Category archive page wrapper.
 *
 * Wraps the category archive inside the main layout
 * (header + footer are handled by header.php/footer.php).
 *
 * @var WP_Query $query
 */

defined( 'ABSPATH' ) || exit;

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

// Render the category archive content view.
$categoryArchiveContent = YivicLite_WP_Theme::view()->render(
    'views/posts/category',
    [
        'query' => $query ?? null,
    ]
);

// Inject into the main layout.
echo YivicLite_WP_Theme::view()->render(
    'views/layouts/main',
    [
        'content' => $categoryArchiveContent,
    ]
);