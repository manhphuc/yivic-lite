<?php
/**
 * Comments template
 *
 * Delegates rendering to the Laravel-style view partial
 * while keeping full compatibility with WordPress core.
 *
 * @package Yivic Lite
 */

defined( 'ABSPATH' ) || exit;

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

global $post;

// Render the dedicated comments partial.
echo YivicLite_WP_Theme::view()->render(
    'views/partials/comments',
    [
        'post'       => $post,
        'textDomain' => 'yivic-lite',
    ]
);