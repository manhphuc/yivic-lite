<header class="yivic-lite-post__header">
    <?php
    // Single header visibility options.
    $show_single_thumb  = (bool) get_theme_mod( 'yivic_lite_show_thumbnail_single', true );
    $show_single_date   = (bool) get_theme_mod( 'yivic_lite_single_show_date', true );
    $show_single_author = (bool) get_theme_mod( 'yivic_lite_single_show_author', true );
    ?>

    <?php if ( $show_single_thumb && has_post_thumbnail() ) : ?>
        <figure class="yivic-lite-post__thumbnail yivic-lite-post__thumbnail--single">
            <?php the_post_thumbnail( 'large' ); ?>
        </figure>
    <?php endif; ?>

    <h1 class="yivic-lite-post__title">
        <?php the_title(); ?>
    </h1>

    <?php if ( $show_single_date || $show_single_author ) : ?>
        <div class="yivic-lite-post__meta">

            <?php if ( $show_single_date ) : ?>
                <!-- Date (clickable archive link) -->
                <span class="yivic-lite-post__meta-item yivic-lite-post__meta-item--date">
                    <a href="<?php echo esc_url( get_day_link( get_the_time( 'Y' ), get_the_time( 'm' ), get_the_time( 'd' ) ) ); ?>">
                        <?php echo esc_html( get_the_date() ); ?>
                    </a>
                </span>
            <?php endif; ?>

            <?php if ( $show_single_author ) : ?>
                <!-- Author: "by" outside link, only name is clickable -->
                <span class="yivic-lite-post__meta-item yivic-lite-post__meta-item--author">
                    <?php esc_html_e( 'by', 'yivic-lite' ); ?>
                    <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
                        <?php echo esc_html( get_the_author() ); ?>
                    </a>
                </span>
            <?php endif; ?>

        </div>
    <?php endif; ?>
</header>