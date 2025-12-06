<?php
/**
 * Post item partial used in home / archive / category loops.
 *
 * Assumes global $post is set
 * By $query->the_post() before rendering.
 */

defined( 'ABSPATH' ) || exit;

// Detect thumbnail for this post
$has_thumb = has_post_thumbnail();

// Base classes for article.
$yivic_post_classes = array( 'yivic-post' );

// BEM modifiers for thumbnail state.
$yivic_post_classes[] = $has_thumb
    ? 'yivic-post--has-thumb'
    : 'yivic-post--no-thumb';

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

    <div class="yivic-lite-post__meta">

        <?php // Author avatar. ?>
        <span class="yivic-lite-post__meta-avatar">
            <?php
            echo get_avatar(
                get_the_author_meta( 'ID' ),
                24,
                '',
                get_the_author(),
                array( 'class' => 'yivic-lite-post__meta-avatar-img' )
            );
            ?>
        </span>

        <?php // Category badge (first category). ?>
        <?php if ( $yivic_main_cat ) : ?>
            <a
                class="yivic-lite-post__meta-category"
                href="<?php echo esc_url( get_category_link( $yivic_main_cat->term_id ) ); ?>"
            >
                <?php echo esc_html( $yivic_main_cat->name ); ?>
            </a>
        <?php endif; ?>

        <?php // Date. ?>
        <span class="yivic-lite-post__meta-date">
            <?php echo esc_html( get_the_time( get_option( 'date_format' ) ) ); ?>
        </span>
    </div>

    <div class="yivic-lite-post__excerpt">
        <?php the_excerpt(); ?>
    </div>

</article>