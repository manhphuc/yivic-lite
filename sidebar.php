<?php
/**
 * Sidebar template.
 *
 * This file is required by WordPress core. It simply delegates
 * to our Laravel-style view partial.
 */

defined( 'ABSPATH' ) || exit;

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

echo YivicLite_WP_Theme::view()->render( 'views/partials/sidebar' );