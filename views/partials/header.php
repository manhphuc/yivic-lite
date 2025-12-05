<?php
defined( 'ABSPATH' ) || exit;

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

/**
 * Yivic Lite – Custom Navigation Header (BEM + WP.org compliant)
 */
?>
<header id="yivicHeader" class="yivic-lite-header">
    <div id="yivicSticky" class="yivic-lite-header__bar">
        <div class="grid wide">
            <div class="row">
                <div class="col l-12 m-12 c-12">
                    <nav class="yivic-lite-header__nav" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'yivic-lite' ); ?>">
                        <?php echo YivicLite_WP_Theme::view()->render( 'views/partials/header/_toggle' ); ?>
                        <?php echo YivicLite_WP_Theme::view()->render( 'views/partials/header/_branding' ); ?>
                        <?php echo YivicLite_WP_Theme::view()->render( 'views/partials/header/_nav' ); ?>
                        <?php echo YivicLite_WP_Theme::view()->render( 'views/partials/header/_search' ); ?>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>