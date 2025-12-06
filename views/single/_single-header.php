<header class="yivic-lite-post__header">

    <?php if ( has_post_thumbnail() ) : ?>
        <figure class="yivic-lite-post__thumbnail yivic-lite-post__thumbnail--single">
            <?php the_post_thumbnail( 'large' ); ?>
        </figure>
    <?php endif; ?>

    <h1 class="yivic-lite-post__title">
        <?php the_title(); ?>
    </h1>

    <div class="yivic-lite-post__meta">

        <!-- Date (clickable) -->
        <span class="yivic-lite-post__meta-item yivic-lite-post__meta-item--date">
        <a href="<?php echo esc_url( get_day_link( get_the_time( 'Y' ), get_the_time( 'm' ), get_the_time( 'd' ) ) ); ?>">
            <?php echo esc_html( get_the_date() ); ?>
        </a>
    </span>

        <!-- Author (only author name is clickable — “by” remains outside the link) -->
        <span class="yivic-lite-post__meta-item yivic-lite-post__meta-item--author">
        <?php esc_html_e( 'by', 'yivic-lite' ); ?>
        <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
            <?php echo esc_html( get_the_author() ); ?>
        </a>
    </span>

    </div>

</header>