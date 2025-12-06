<?php
/**
 * Attachment content view.
 *
 * Renders a single attachment (image, document, etc.) with
 * its caption, description, meta information and optional comments.
 *
 * @var WP_Query|null $query
 */
use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

defined( 'ABSPATH' ) || exit;

// Ensure we have a valid query object.
if ( ! isset( $query ) || ! $query instanceof WP_Query ) {
    global $wp_query;
    $query = $wp_query;
} ?>
<div class="yivic-lite-attachment-archive__list">
    <?php if ( $query->have_posts() ) : ?>
        <?php while ( $query->have_posts() ) : $query->the_post();
            $mime_type   = get_post_mime_type();
            $parent_post = get_post( wp_get_post_parent_id( get_the_ID() ) ); ?>
            <?php echo YivicLite_WP_Theme::view()->render( 'views/posts/_post-item' ); ?>
        <?php endwhile; ?>
        <?php echo YivicLite_WP_Theme::view()->render(
                'views/partials/pagination/_pagination', [
                'query' => $query,
        ]);
    else : ?>
        <p class="yivic-lite-attachment__empty">
            <?php esc_html_e( 'No attachment found.', 'yivic-lite' ); ?>
        </p>
    <?php endif; ?>
</div>
<?php wp_reset_postdata();