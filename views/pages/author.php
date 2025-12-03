<?php
/**
 * Author archive page wrapper.
 *
 * Wraps the author archive inside the main layout
 * (header + footer are handled by header.php/footer.php).
 *
 * @var WP_Query $query
 */

defined( 'ABSPATH' ) || exit;

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

// Render the author archive content view.
$authorArchiveContent = YivicLite_WP_Theme::view()->render(
    'views/posts/author',
    [
        'query' => $query ?? null,
    ]
);

// Inject into the main layout.
echo YivicLite_WP_Theme::view()->render(
    'views/layouts/main',
    [
        'content' => $authorArchiveContent,
    ]
);