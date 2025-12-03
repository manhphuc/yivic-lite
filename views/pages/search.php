<?php
/**
 * Search results page wrapper.
 *
 * Wraps the search listing inside the main layout.
 *
 * @var WP_Query $query
 */

defined( 'ABSPATH' ) || exit;

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

// Render the inner search results view.
$searchContent = YivicLite_WP_Theme::view()->render(
    'views/posts/search',
    [
        'query' => $query ?? null,
    ]
);

// Inject into the main layout.
echo YivicLite_WP_Theme::view()->render(
    'views/layouts/main',
    [
        'content' => $searchContent,
    ]
);