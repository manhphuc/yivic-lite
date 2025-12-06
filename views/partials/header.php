<?php
use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Yivic Lite – Custom Navigation Header (BEM + WP.org compliant)
 */
$header_bg = get_theme_mod( 'yivic_lite_header_bg_color', '#313b45' ); ?>
<header id="yivicHeader" class="yivic-lite-header">
    <div id="yivicSticky" class="yivic-lite-header__bar" style="background: <?php echo esc_attr( $header_bg ); ?>;">
        <div class="grid wide">
            <div class="row">
                <div class="col l-12 m-12 c-12">
                    <nav class="yivic-lite-header__nav" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'yivic-lite' ); ?>">
                        <?php echo YivicLite_WP_Theme::view()->render( 'views/partials/header/_toggle' ); ?>
                        <?php echo YivicLite_WP_Theme::view()->render( 'views/partials/header/_branding' ); ?>
                        <?php echo YivicLite_WP_Theme::view()->render( 'views/partials/header/_nav', [ 'header_bg' => $header_bg ] ); ?>
                        <?php echo YivicLite_WP_Theme::view()->render( 'views/partials/header/_search' ); ?>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>