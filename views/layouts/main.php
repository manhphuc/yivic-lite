<?php
defined( 'ABSPATH' ) || exit;

/**
 * Main layout wrapper.
 * Block: yivic-lite-layout
 * Elements: __container, __row, __main, __sidebar
 *
 * Expects: $content = HTML of the loop / page.
 */
?>
<div class="yivic-lite-layout">
    <div class="yivic-lite-layout__container grid wide">
        <div class="yivic-lite-layout__row row">
            <main class="yivic-lite-layout__main col l-8 m-12 c-12">
                <?php echo $content ?? ''; ?>
            </main>

            <aside class="yivic-lite-layout__sidebar col l-4 m-12 c-12">
                <?php if ( is_active_sidebar( 'sidebar-1' ) ) {
                    dynamic_sidebar( 'sidebar-1' );
                } else {
                    echo \Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme::view()
                            ->render( 'views/partials/sidebar' );
                } ?>
            </aside>
        </div>
    </div>
</div>