<?php
use Yivic\YivicLite\Theme\Helpers\YivicLiteHelper;

defined( 'ABSPATH' ) || exit;
/**
 * Main layout wrapper.
 * Block: yivic-lite-layout
 * Elements: __container, __row, __main, __sidebar
 *
 * Expects:
 * - $content : HTML of the loop / page.
 * - Sidebar visibility is controlled by the Customizer option
 *   "yivic_lite_sidebar_display" via YivicLiteHelper::getLayoutColumns().
 */

// Get layout configuration (main + sidebar classes).
$layout = YivicLiteHelper::getLayoutColumns(); ?>
<div class="yivic-lite-layout">
    <div class="yivic-lite-layout__container grid wide">
        <div class="yivic-lite-layout__row row">
            <!-- Main content column -->
            <main class="<?php echo esc_attr( $layout['main'] ); ?>">
                <?php echo $content ?? ''; ?>
            </main>
            <?php if ( $layout['has_sidebar'] ) : ?>
                <!-- Sidebar column (only rendered when enabled in Customizer) -->
                <aside class="<?php echo esc_attr( $layout['sidebar'] ); ?>">
                    <?php if ( is_active_sidebar( 'sidebar-1' ) ) {
                        dynamic_sidebar( 'sidebar-1' );
                    } else {
                        echo \Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme::view()
                                ->render( 'views/partials/sidebar' );
                    } ?>
                </aside>
            <?php endif; ?>
        </div>
    </div>
</div>