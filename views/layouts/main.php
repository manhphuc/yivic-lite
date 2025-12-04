<?php
defined( 'ABSPATH' ) || exit;

/**
 * Main layout wrapper.
 * Block: yivic-lite-layout
 * Elements: __container, __main, __sidebar
 *
 * Expects: $content = HTML of the loop / page.
 */
?>
<div class="yivic-lite-layout">
    <div class="yivic-lite-layout__container">

        <main class="yivic-lite-layout__main">
            <?php echo $content ?? ''; ?>
        </main>

        <aside class="yivic-lite-layout__sidebar">
            <?php
            if ( is_active_sidebar( 'sidebar-1' ) ) {
                dynamic_sidebar( 'sidebar-1' );
            } else {
                echo \Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme::view()
                    ->render( 'views/partials/sidebar' );
            }
            ?>
        </aside>

    </div>
</div>