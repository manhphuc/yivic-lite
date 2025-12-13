<?php
/**
 * Single post view.
 *
 * Renders:
 * - Header/content/footer partials
 * - Comments template (core)
 * - Post navigation
 *
 * @var WP_Query|null $query
 */

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

defined( 'ABSPATH' ) || exit;

if ( ! isset( $query ) || ! $query instanceof WP_Query ) {
    global $wp_query;
    $query = $wp_query;
}

if ( $query->have_posts() ) :
    while ( $query->have_posts() ) :
        $query->the_post();

        $classes = 'yivic-lite-post yivic-lite-post--single';
        ?>

        <?php
        // Rendered HTML from theme templates.
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo YivicLite_WP_Theme::view()->render( 'views/partials/breadcrumb' );
        ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
            <?php
            // Rendered HTML from theme templates.
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo YivicLite_WP_Theme::view()->render( 'views/single/_single-header' );

            // Rendered HTML from theme templates.
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo YivicLite_WP_Theme::view()->render( 'views/single/_single-content' );

            // Rendered HTML from theme templates.
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo YivicLite_WP_Theme::view()->render( 'views/single/_single-footer' );
            ?>
        </article>

        <?php
        /**
         * Comments section.
         * Use the core template to stay compatible with WordPress expectations.
         */
        if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif;

        echo '<div class="yivic-lite-post-nav">';
        the_post_navigation(
                [
                        'prev_text' => esc_html__( 'Previous post', 'yivic-lite' ),
                        'next_text' => esc_html__( 'Next post', 'yivic-lite' ),
                ]
        );
        echo '</div>';

    endwhile;
else :
    ?>
    <p class="yivic-lite-post__empty">
        <?php esc_html_e( 'No content found.', 'yivic-lite' ); ?>
    </p>
<?php
endif;

wp_reset_postdata();
