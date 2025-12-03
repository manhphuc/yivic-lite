<?php
/**
 * Theme Name:   Yivic Lite
 * Theme URI:    https://github.com/manhphuc/yivic-lite
 * Description:  Lightweight Laravel-style WordPress theme using DI container & service architecture.
 * Author:       Phuc Nguyen
 * Author URI:   https://github.com/manhphuc
 * Version:      1.0.3
 * Text Domain:  yivic-lite
 */

defined( 'ABSPATH' ) || exit;

/*
|--------------------------------------------------------------------------
| Theme Constants
|--------------------------------------------------------------------------
*/

defined( 'YIVIC_LITE_VERSION' ) || define( 'YIVIC_LITE_VERSION', '1.0.3' );
defined( 'YIVIC_LITE_SLUG' )    || define( 'YIVIC_LITE_SLUG', 'yivic-lite' );

/*
|--------------------------------------------------------------------------
| Composer Autoload
|--------------------------------------------------------------------------
*/

$autoload_path = __DIR__ . '/vendor/autoload.php';

if ( file_exists( $autoload_path ) ) {
    require_once $autoload_path;
} else {
    // Fail gracefully in production – no admin error spam.
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
        error_log( '[Yivic Lite] Vendor autoload not found: ' . $autoload_path );
    }
}

/*
|--------------------------------------------------------------------------
| Bootstrap Theme Kernel
|--------------------------------------------------------------------------
*/

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

try {
    $config = require( __DIR__ . DIRECTORY_SEPARATOR . 'config.php' );
    $config = array_merge( $config, [
        'themeFilename' => __FILE__,
    ] );

    YivicLite_WP_Theme::initInstanceWithConfig( $config );
    YivicLite_WP_Theme::getInstance()->initTheme();

} catch ( Throwable $e ) {
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
        error_log( '[Yivic Lite] Bootstrap failed: ' . $e->getMessage() );
    }
}