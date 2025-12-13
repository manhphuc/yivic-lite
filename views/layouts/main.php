<?php
use Yivic\YivicLite\Theme\Helpers\YivicLiteHelper;

defined( 'ABSPATH' ) || exit;

/**
 * Main layout wrapper.
 *
 * Expects:
 * - $content: Rendered HTML output from the theme view layer.
 * - Sidebar visibility is controlled by Customizer option `yivic_lite_sidebar_display`
 *   via YivicLiteHelper::getLayoutColumns().
 */

$layout = YivicLiteHelper::getLayoutColumns();
?>

<div class="yivic-lite-layout">
    <div class="yivic-lite-layout__container grid wide">
        <div class="yivic-lite-layout__row row">
            <main id="primary" class="<?php echo esc_attr( $layout['main'] ); ?>">
                <?php
                /**
                 * The `$content` variable is a rendered HTML string produced by the theme's
                 * view layer (templates already handle context-aware escaping).
                 */
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo $content ?? '';
                ?>
            </main>

            <?php if ( $layout['has_sidebar'] ) : ?>
                <aside class="<?php echo esc_attr( $layout['sidebar'] ); ?>">
                    <?php
                    if ( is_active_sidebar( 'yivic-lite-sidebar-1' ) ) {
                        dynamic_sidebar( 'yivic-lite-sidebar-1' );
                    } else {
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo \Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme::view()
                                ->render( 'views/partials/sidebar' );
                    }
                    ?>
                </aside>
            <?php endif; ?>
        </div>
    </div>
</div>
