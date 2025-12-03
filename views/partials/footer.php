<?php
defined( 'ABSPATH' ) || exit;

/**
 * Footer partial.
 * Block: yivic-lite-footer
 * Elements: __inner, __text
 */
?>
<footer class="yivic-lite-footer">
    <div class="yivic-lite-footer__inner">
        <p class="yivic-lite-footer__text">
            &copy; <?php echo esc_html( date( 'Y' ) ); ?>
            <?php bloginfo( 'name' ); ?>.
            <?php esc_html_e( 'Powered by WordPress &amp; Yivic Lite.', 'yivic-lite' ); ?>
        </p>
    </div>
</footer>