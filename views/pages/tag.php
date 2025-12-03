<?php
/**
 * Tag archive page wrapper.
 *
 * Wraps the tag archive inside the main layout
 * (header + footer are handled by header.php/footer.php).
 *
 * @var WP_Query $query
 */

defined( 'ABSPATH' ) || exit;

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

// Render the tag archive content view.
$tagArchiveContent = YivicLite_WP_Theme::view()->render(
    'views/posts/tag',
    [
        'query' => $query ?? null,
    ]
);

// Inject into the main layout.
echo YivicLite_WP_Theme::view()->render(
    'views/layouts/main',
    [
        'content' => $tagArchiveContent,
    ]
);