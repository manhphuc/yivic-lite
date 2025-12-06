<?php
defined( 'ABSPATH' ) || exit;

/**
 * Footer partial.
 * Block: yivic-lite-footer
 * Elements: __inner, __text
 */

// Build default text with dynamic year + site name.
$default_copyright = sprintf(
/* translators: 1: current year, 2: site name. */
        __( '© %1$s %2$s. Powered by WordPress & Yivic Lite.', 'yivic-lite' ),
        date_i18n( 'Y' ),
        get_bloginfo( 'name' )
);

// Allow user override via Customizer.
$copyright = get_theme_mod(
        'yivic_lite_footer_copyright',
        $default_copyright
);
?>
<footer class="yivic-lite-footer" >
    <div class="yivic-lite-footer__inner">
        <p class="yivic-lite-footer__text">
            <?php echo wp_kses_post( $copyright ); ?>
        </p>
    </div>
</footer>