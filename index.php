<?php
defined( 'ABSPATH' ) || exit;

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

get_header();

// Render home page via view system
//echo YivicLite_WP_Theme::view()->render( 'views/pages/home', [
//    'query' => $wp_query,
//] );

get_footer();