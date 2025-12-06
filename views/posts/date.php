<?php
/**
 * Date archive content view.
 *
 * Displays posts for a specific day, month, or year.
 * Uses the standard loop + pagination so Gutenberg
 * blocks and excerpts behave as expected.
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

/**
 * Build a human-readable archive title based on the date context:
 * - Day archive: "Day: January 1, 2025"
 * - Month archive: "Month: January 2025"
 * - Year archive: "Year: 2025"
 */
if ( is_day() ) {
    $archive_title = sprintf(
    /* translators: %s: date formatted for day archives. */
        esc_html__( 'Day: %s', 'yivic-lite' ),
        esc_html( get_the_date() )
    );
} elseif ( is_month() ) {
    $archive_title = sprintf(
    /* translators: %s: date formatted for month archives. */
        esc_html__( 'Month: %s', 'yivic-lite' ),
        esc_html( get_the_date( 'F Y' ) )
    );
} elseif ( is_year() ) {
    $archive_title = sprintf(
    /* translators: %s: date formatted for year archives. */
        esc_html__( 'Year: %s', 'yivic-lite' ),
        esc_html( get_the_date( 'Y' ) )
    );
} else {
    $archive_title = esc_html__( 'Date archives', 'yivic-lite' );
}
?>
    <section class="yivic-lite-date-archive">
        <header class="yivic-lite-date-archive__header">
            <h1 class="yivic-lite-date-archive__title">
                <?php echo $archive_title; ?>
            </h1>
        </header>
        <div class="yivic-lite-date-archive__list">
            <?php if ( $query->have_posts() ) : ?>
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <?php echo YivicLite_WP_Theme::view()->render( 'views/posts/_post-item' ); ?>
                <?php endwhile; ?>
                <?php echo YivicLite_WP_Theme::view()->render(
                        'views/partials/pagination/_pagination', [
                        'query' => $query,
                ] ); ?>
            <?php else : ?>
                <p class="yivic-lite-date-archive__empty">
                    <?php esc_html_e( 'No posts found for this date.', 'yivic-lite' ); ?>
                </p>
            <?php endif; ?>
        </div>
    </section>
<?php
// Reset global post data.
wp_reset_postdata();