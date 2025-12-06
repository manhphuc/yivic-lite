<?php $header_bg = $header_bg ?? '#313b45'; ?>
<!-- Off–canvas menu / mobile + desktop menu wrapper -->
<div class="yivic-lite-header__links nav-links" id="yivicMobileMenu" style="background: <?php echo esc_attr( $header_bg ); ?>;">
    <div class="yivic-lite-header__sidebar-logo sidebar-logo">
        <span class="yivic-lite-header__logo-name logo-name">
            <?php bloginfo( 'name' ); ?>
        </span>

        <button class="yivic-lite-header__close yivicCancel-icon" id="yivicMenuClose" type="button" >
            <img class="yivic-lite-header__close-icon"
                src="<?php echo esc_url( get_template_directory_uri() . '/public-assets/dist/img/yivic-lite-cancel-icon.svg' ); ?>"
                alt="<?php esc_attr_e( 'Close menu', 'yivic-lite' ); ?>"
            >
        </button>
    </div>

    <?php wp_nav_menu( [
        'theme_location' => 'yivic-lite-primary',
        'container'      => 'div',
        'container_class'=> 'yivic-lite-header__menu-container menu-categories-container',
        'menu_class'     => 'yivic-lite-header__menu links main-nav dropdown-menu sf-menu',
        'menu_id'        => 'yivic-lite-primary-menu',
        'fallback_cb'    => 'yivic_menu_fallback',
        'depth'          => 0,
    ] ); ?>
</div>