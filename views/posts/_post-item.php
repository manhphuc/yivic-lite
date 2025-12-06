<?php
/**
 * Post item partial used in home / archive / category loops.
 *
 * Assumes global $post is set by $query->the_post() before rendering.
 */
defined( 'ABSPATH' ) || exit;

// Read Customizer options for archive card behaviour.
$show_excerpt              = (bool) get_theme_mod( 'yivic_lite_show_excerpt', true );
$show_thumb_archive        = (bool) get_theme_mod( 'yivic_lite_show_thumbnail_archive', true );
$show_archive_avatar       = (bool) get_theme_mod( 'yivic_lite_show_archive_author_avatar', true );
$show_archive_category     = (bool) get_theme_mod( 'yivic_lite_show_archive_category', true );
$show_archive_date         = (bool) get_theme_mod( 'yivic_lite_show_archive_date', true );

// Determine if this post has a thumbnail AND thumbnails are enabled.
$has_thumb = $show_thumb_archive && has_post_thumbnail();

// Base classes for article.
$yivic_post_classes = [ 'yivic-post' ];

// BEM modifiers for thumbnail state.
$yivic_post_classes[] = $has_thumb
        ? 'yivic-post--has-thumb'
        : 'yivic-post--no-thumb';

// First category for the badge.
$yivic_categories = get_the_category();
$yivic_main_cat   = ! empty( $yivic_categories ) ? $yivic_categories[0] : null;

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $yivic_post_classes ); ?>>

    <?php if ( $has_thumb ) : ?>
        <figure class="yivic-lite-post__thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail( 'large' ); ?>
            </a>
        </figure>
    <?php endif; ?>

    <h2 class="yivic-lite-post__title">
        <a href="<?php the_permalink(); ?>">
            <?php the_title(); ?>
        </a>
    </h2>

    <?php
    // Render meta bar only if at least one piece is enabled.
    if ( $show_archive_avatar || $show_archive_category || $show_archive_date ) :
        ?>
        <div class="yivic-lite-post__meta">

            <?php // Author avatar (optional). ?>
            <?php if ( $show_archive_avatar ) : ?>
                <span class="yivic-lite-post__meta-avatar">
                    <?php
                    echo get_avatar(
                            get_the_author_meta( 'ID' ),
                            24,
                            '',
                            get_the_author(),
                            [
                                    'class' => 'yivic-lite-post__meta-avatar-img',
                            ]
                    );
                    ?>
                </span>
            <?php endif; ?>

            <?php // Category badge (first category, optional). ?>
            <?php if ( $show_archive_category && $yivic_main_cat ) : ?>
                <a
                        class="yivic-lite-post__meta-category"
                        href="<?php echo esc_url( get_category_link( $yivic_main_cat->term_id ) ); ?>"
                >
                    <?php echo esc_html( $yivic_main_cat->name ); ?>
                </a>
            <?php endif; ?>

            <?php // Date (optional). ?>
            <?php if ( $show_archive_date ) : ?>
                <span class="yivic-lite-post__meta-date">
                    <?php echo esc_html( get_the_time( get_option( 'date_format' ) ) ); ?>
                </span>
            <?php endif; ?>

        </div>
    <?php endif; ?>

    <?php if ( $show_excerpt ) : ?>
        <div class="yivic-lite-post__excerpt">
            <?php the_excerpt(); ?>
        </div>
    <?php endif; ?>

</article>