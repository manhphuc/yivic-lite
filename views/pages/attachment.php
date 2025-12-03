<?php
/**
 * Attachment page wrapper.
 *
 * Wraps the attachment content inside the main layout.
 *
 * @var WP_Query $query
 */

defined( 'ABSPATH' ) || exit;

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

// Render the attachment content view.
$attachmentContent = YivicLite_WP_Theme::view()->render(
    'views/posts/attachment',
    [
        'query' => $query ?? null,
    ]
);

// Inject into the main layout (header/footer handled by theme).
echo YivicLite_WP_Theme::view()->render(
    'views/layouts/main',
    [
        'content' => $attachmentContent,
    ]
);