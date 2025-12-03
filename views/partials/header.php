<?php
defined( 'ABSPATH' ) || exit;

/**
 * Header partial.
 * Block: yivic-lite-header
 * Elements: __inner, __branding, __site-title, __site-description, __nav, __menu
 */
?>
<header class="yivic-lite-header">
    <div class="yivic-lite-header__inner">

        <div class="yivic-lite-header__branding">
            <div class="yivic-lite-header__site-title">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <?php bloginfo( 'name' ); ?>
                </a>
            </div>

            <?php if ( get_bloginfo( 'description' ) ) : ?>
                <p class="yivic-lite-header__site-description">
                    <?php bloginfo( 'description' ); ?>
                </p>
            <?php endif; ?>
        </div>

        <nav class="yivic-lite-header__nav">
            <?php
            wp_nav_menu( [
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'yivic-lite-header__menu',
                'fallback_cb'    => false,
            ] );
            ?>
        </nav>

    </div>
</header>