<header class="yivic-header">
    <div class="yivic-header__inner">
        <div class="yivic-header__logo">
            <?php
            if (has_custom_logo()) {
                the_custom_logo();
            } else {
                ?>
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
                <?php
            }
            ?>
        </div>

        <nav class="yivic-header__nav">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'fallback_cb'    => false,
            ]);
            ?>
        </nav>
    </div>
</header>