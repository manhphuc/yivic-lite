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

        <?php if ( $query->have_posts() ) : ?>
            <div class="yivic-lite-date-archive__list">
                <?php
                while ( $query->have_posts() ) :
                    $query->the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'yivic-lite-date-archive__item' ); ?>>
                        <h2 class="yivic-lite-date-archive__item-title">
                            <a href="<?php the_permalink(); ?>" class="yivic-lite-date-archive__item-link">
                                <?php the_title(); ?>
                            </a>
                        </h2>

                        <div class="yivic-lite-date-archive__item-meta">
						<span class="yivic-lite-date-archive__item-date">
							<?php echo esc_html( get_the_date() ); ?>
						</span>

                            <span class="yivic-lite-date-archive__item-author">
							<?php
                            /* translators: %s: post author name */
                            printf(
                                esc_html__( 'by %s', 'yivic-lite' ),
                                esc_html( get_the_author() )
                            );
                            ?>
						</span>
                        </div>

                        <div class="yivic-lite-date-archive__item-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                    </article>
                <?php
                endwhile;
                ?>
            </div>

            <div class="yivic-lite-date-archive__pagination">
                <?php
                the_posts_pagination(
                    [
                        'prev_text' => esc_html__( 'Previous', 'yivic-lite' ),
                        'next_text' => esc_html__( 'Next', 'yivic-lite' ),
                    ]
                );
                ?>
            </div>

        <?php else : ?>

            <p class="yivic-lite-date-archive__empty">
                <?php esc_html_e( 'No posts found for this date.', 'yivic-lite' ); ?>
            </p>

        <?php endif; ?>

    </section>

<?php
// Reset global post data.
wp_reset_postdata();