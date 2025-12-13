<?php
/**
 * Theme Name:   Yivic Lite
 * Theme URI:    https://github.com/manhphuc/yivic-lite
 * Description:  A clean and lightweight classic theme focused on readability and simple layout structure. Designed with modern HTML5 standards, customizer options, and flexible templates for blogs and personal sites.
 * Author:       Phuc Nguyen
 * Author URI:   https://github.com/manhphuc
 * Version:      1.0.4
 * Text Domain:  yivic-lite
 */

defined( 'ABSPATH' ) || exit;

/*
|--------------------------------------------------------------------------
| Theme Constants
|--------------------------------------------------------------------------
*/

defined( 'YIVIC_LITE_VERSION' ) || define( 'YIVIC_LITE_VERSION', '1.0.4' );
defined( 'YIVIC_LITE_SLUG' )    || define( 'YIVIC_LITE_SLUG', 'yivic-lite' );

/*
|--------------------------------------------------------------------------
| Autoload: Composer (if available) → Fallback to Theme Autoloader
|--------------------------------------------------------------------------
|
| 1. Prefer Composer's autoloader when the theme is in development mode
|    or installed manually by a developer.
|
| 2. If the theme is downloaded from WordPress.org (vendor removed),
|    gracefully fall back to our lightweight PSR-4 autoloader located in:
|       src/YivicLite_Theme_Autoloader.php
|
| This ensures the theme works in every environment without requiring
| Composer or any external build tools.
|
*/

$composerAutoload = __DIR__ . '/vendor/autoload.php';
$fallbackAutoload = __DIR__ . '/src/YivicLite_Theme_Autoloader.php';

if ( file_exists( $composerAutoload ) ) {

    // Prefer Composer when developing or running a packaged build
    require_once $composerAutoload;

} elseif ( file_exists( $fallbackAutoload ) ) {

    // Theme was downloaded from WP.org → vendor folder removed
    require_once $fallbackAutoload;

} else {

    // Edge-case: autoloader missing completely → warn only in debug mode
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
        error_log(
            '[Yivic Lite] ERROR: No autoloader found. Checked:' . PHP_EOL .
            '- ' . $composerAutoload . PHP_EOL .
            '- ' . $fallbackAutoload
        );
    }
}

/*
|--------------------------------------------------------------------------
| Bootstrap Theme Kernel
|--------------------------------------------------------------------------
*/

try {
    $config = require( __DIR__ . DIRECTORY_SEPARATOR . 'config.php' );
    $config = array_merge( $config, [
        'themeFilename' => __FILE__,
    ] );

    \Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme::initInstanceWithConfig( $config );
    \Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme::getInstance()->initTheme();

} catch ( Throwable $e ) {
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
        error_log( '[Yivic Lite] Bootstrap failed: ' . $e->getMessage() );
    }
}