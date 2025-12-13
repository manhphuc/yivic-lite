<?php
/** @var WP_Query|null $query */

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Posts loop view.
 *
 * Notes:
 * - `$query` can be injected by the caller. If missing, fall back to `$wp_query`.
 * - Child templates are responsible for escaping their own dynamic outputs.
 */

if ( ! isset( $query ) || ! $query instanceof WP_Query ) {
    global $wp_query;
    $query = $wp_query;
}
?>

<div class="yivic-lite-home__list">
    <?php if ( ! $query->have_posts() ) : ?>
        <p><?php esc_html_e( 'No posts found.', 'yivic-lite' ); ?></p>
    <?php else : ?>
        <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            <?php
            // Rendered HTML from theme templates.
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo YivicLite_WP_Theme::view()->render( 'views/posts/_post-item' );
            ?>
        <?php endwhile; ?>

        <?php
        // Rendered HTML from theme templates.
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo YivicLite_WP_Theme::view()->render(
                'views/partials/pagination/_pagination',
                [ 'query' => $query ]
        );
        ?>
    <?php endif; ?>
</div>

<?php wp_reset_postdata(); ?>
