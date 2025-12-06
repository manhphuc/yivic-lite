<?php
/**
 * Author archive content view.
 *
 * Displays the author bio (if available) and a list of posts
 * written by that author, using the main query.
 *
 * @var WP_Query|null $query
 */
use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

defined( 'ABSPATH' ) || exit;

// Ensure we have a valid query object.
if ( ! isset( $query ) || ! $query instanceof WP_Query ) {
    global $wp_query;
    $query = $wp_query;
}

// Get the queried author object.
$author = get_queried_object();

$author_id          = 0;
$author_displayname = '';
$author_description = '';

if ( $author instanceof WP_User ) {
    $author_id          = (int) $author->ID;
    $author_displayname = $author->display_name;
    $author_description = get_the_author_meta( 'description', $author_id );
} else {
    // Fallback values.
    $author_id          = get_query_var( 'author' ) ? (int) get_query_var( 'author' ) : 0;
    $author_displayname = $author_id ? get_the_author_meta( 'display_name', $author_id ) : '';
    $author_description = $author_id ? get_the_author_meta( 'description', $author_id ) : '';
}
?>
    <section class="yivic-lite-author-archive">
        <header class="yivic-lite-author-archive__header">
            <?php echo YivicLite_WP_Theme::view()->render( 'views/partials/breadcrumb' ); ?>
            <h1 class="yivic-lite-author-archive__title">
                <?php
                if ( $author_displayname ) {
                    /* translators: %s: author display name */
                    printf(
                        esc_html__( 'Posts by %s', 'yivic-lite' ),
                        esc_html( $author_displayname )
                    );
                } else {
                    esc_html_e( 'Author archives', 'yivic-lite' );
                }
                ?>
            </h1>

            <?php if ( ! empty( $author_description ) ) : ?>
                <div class="yivic-lite-author-archive__bio">
                    <p class="yivic-lite-author-archive__bio-text">
                        <?php echo wp_kses_post( wpautop( $author_description ) ); ?>
                    </p>
                </div>
            <?php endif; ?>
        </header>
        <div class="yivic-lite-author-archive__list">
            <?php if ( $query->have_posts() ) : ?>
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <?php echo YivicLite_WP_Theme::view()->render( 'views/posts/_post-item' ); ?>
                <?php endwhile; ?>
                <?php echo YivicLite_WP_Theme::view()->render(
                        'views/partials/pagination/_pagination', [
                        'query' => $query,
                ] ); ?>
            <?php else : ?>
                <p class="yivic-lite-author-archive__empty">
                    <?php esc_html_e( 'This author has not published any posts yet.', 'yivic-lite' ); ?>
                </p>
            <?php endif; ?>
        </div>
    </section>
<?php
// Reset global post data.
wp_reset_postdata();