<?php
/**
 * Search results listing view.
 *
 * Displays a list of posts matching the current search query.
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

// Capture search term once for reuse.
$searchTerm = get_search_query();
?>
    <header class="yivic-lite-search__header">
        <h1 class="yivic-lite-search__title">
            <?php
            if ( $searchTerm ) {
                /* translators: %s: search query. */
                printf(
                    esc_html__( 'Search results for "%s"', 'yivic-lite' ),
                    esc_html( $searchTerm )
                );
            } else {
                esc_html_e( 'Search results', 'yivic-lite' );
            }
            ?>
        </h1>
        <?php if ( $searchTerm ) : ?>
            <p class="yivic-lite-search__subtitle">
                <?php
                /* translators: %s: search query. */
                printf(
                    esc_html__( 'Showing results matching "%s".', 'yivic-lite' ),
                    esc_html( $searchTerm )
                );
                ?>
            </p>
        <?php endif; ?>
    </header>
    <div class="yivic-lite-search__list">
        <?php if ( $query->have_posts() ) : ?>
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                <?php echo YivicLite_WP_Theme::view()->render( 'views/posts/_post-item' ); ?>
            <?php endwhile; ?>
            <?php echo YivicLite_WP_Theme::view()->render('views/partials/pagination/_pagination', [
                'query' => $query
            ] ); ?>
        <?php else : ?>
            <p class="yivic-lite-search__empty">
                <?php esc_html_e( 'No results found. Try a different search term.', 'yivic-lite' ); ?>
            </p>
            <div class="yivic-lite-search__form">
                <?php get_search_form(); ?>
            </div>
        <?php endif; ?>
    </div>

<?php
// Reset global post data.
wp_reset_postdata();