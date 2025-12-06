<?php
/**
 * Single post content view.
 *
 * Renders the full post content with headings, paragraphs
 * and block markup using the_content(), so Gutenberg blocks
 * are displayed exactly as expected.
 *
 * @var WP_Query|null $query
 */
use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

defined('ABSPATH') || exit;

// Ensure we have a valid query object.
if ( ! isset( $query ) || ! $query instanceof WP_Query ) {
    global $wp_query;
    $query = $wp_query;
}

if ( $query->have_posts() ) :
    while ( $query->have_posts() ) :
        $query->the_post();
        ?>
        <?php $classes = 'yivic-lite-post yivic-lite-post--single'; ?>

        <?php echo YivicLite_WP_Theme::view()->render( 'views/partials/breadcrumb' ); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?> >
            <?php echo YivicLite_WP_Theme::view()->render( 'views/single/_single-header' ); ?>
            <?php echo YivicLite_WP_Theme::view()->render( 'views/single/_single-content' ); ?>
            <?php echo YivicLite_WP_Theme::view()->render( 'views/single/_single-footer' ); ?>
        </article>
        <?php

        /**
         * Comments section.
         * Use the standard WordPress comments_template() so the theme
         * stays fully compatible with core expectations.
         */
        if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif;

    endwhile;

    // Post navigation (previous/next).
    echo '<div class="yivic-lite-post-nav">';
    the_post_navigation(
        [
            'prev_text' => esc_html__( 'Previous post', 'yivic-lite' ),
            'next_text' => esc_html__( 'Next post', 'yivic-lite' ),
        ]
    );
    echo '</div>';
else :
    ?>

    <p class="yivic-lite-post__empty">
        <?php esc_html_e( 'No content found.', 'yivic-lite' ); ?>
    </p>

<?php
endif;

// Reset global post data.
wp_reset_postdata();