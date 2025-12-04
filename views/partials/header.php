<?php
defined( 'ABSPATH' ) || exit;

/**
 * Header partial.
 *
 * Block: yivic-lite-header
 * Elements: __inner, __branding, __logo, __site-title, __site-description, __nav, __menu
 */
?>
<header class="yivic-lite-header">
    <div class="yivic-lite-header__inner">

        <div class="yivic-lite-header__branding">

            <?php if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) : ?>
                <div class="yivic-lite-header__logo">
                    <?php the_custom_logo(); ?>
                </div>
            <?php endif; ?>

            <div class="yivic-lite-header__text">
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

        </div>

        <nav class="yivic-lite-header__nav" aria-label="<?php esc_attr_e( 'Primary menu', 'yivic-lite' ); ?>">
            <?php if ( has_nav_menu( 'primary' ) ) : ?>

                <?php wp_nav_menu(
                    [
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => 'yivic-lite-header__menu',
                        'fallback_cb'    => false,
                    ]
                ); ?>

            <?php else : ?>

                <ul class="yivic-lite-header__menu yivic-lite-header__menu--fallback">
                    <?php wp_list_pages(
                        [
                            'title_li' => '',
                        ]
                    ); ?>
                </ul>

            <?php endif; ?>
        </nav>

    </div>
</header>