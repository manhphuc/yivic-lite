<!-- Branding / Logo -->
<div class="yivic-lite-header__branding">
    <div class="yivic-lite-header__logo">
        <?php if ( has_custom_logo() ) : ?>
            <?php the_custom_logo(); ?>
        <?php else : ?>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="yivic-lite-header__logo-link">
                <span class="yivic-lite-header__site-title">
                    <?php bloginfo( 'name' ); ?>
                </span>
            </a>
        <?php endif; ?>
    </div>
</div>