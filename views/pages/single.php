<?php
/**
 * Single post page wrapper.
 *
 * This view is responsible for wrapping the single post
 * inside the main layout (header + footer are handled
 * by header.php/footer.php and the partials).
 *
 * @var WP_Query $query
 */

defined('ABSPATH') || exit;

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

// Render the single post content view.
$singleContent = YivicLite_WP_Theme::view()->render(
    'views/posts/single',
    [
        'query' => $query ?? null,
    ]
);

// Inject into the main layout.
echo YivicLite_WP_Theme::view()->render(
    'views/layouts/main',
    [
        'content' => $singleContent,
    ]
);