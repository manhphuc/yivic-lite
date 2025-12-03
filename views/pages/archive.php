<?php
/**
 * Archive Page Wrapper
 *
 * Wraps archive content inside the main layout.
 *
 * @var WP_Query $query
 */

defined('ABSPATH') || exit;

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

// Render the archive listing view
$archiveContent = YivicLite_WP_Theme::view()->render(
    'views/posts/archive',
    [
        'query' => $query ?? null,
    ]
);

// Inject into layout
echo YivicLite_WP_Theme::view()->render(
    'views/layouts/main',
    [
        'content' => $archiveContent,
    ]
);