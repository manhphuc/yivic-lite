<?php
defined( 'ABSPATH' ) || exit;

/**
 * Default sidebar partial (used if no widgets).
 * Block: yivic-lite-sidebar
 * Elements: __widget, __title, __content
 */
?>
<div class="yivic-lite-sidebar">
    <div class="yivic-lite-sidebar__widget">
        <h2 class="yivic-lite-sidebar__title">
            <?php esc_html_e( 'Sidebar', 'yivic-lite' ); ?>
        </h2>
        <div class="yivic-lite-sidebar__content">
            <p><?php esc_html_e( 'Add widgets to "Sidebar 1" in Appearance → Widgets.', 'yivic-lite' ); ?></p>
        </div>
    </div>
</div>