<?php
/**
 * Search results listing view.
 *
 * Displays a list of posts matching the current search query.
 *
 * @var WP_Query|null $query
 */

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
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'yivic-lite-search__item' ); ?>>
                    <h2 class="yivic-lite-search__item-title">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h2>

                    <div class="yivic-lite-search__item-meta">
					<span class="yivic-lite-search__item-date">
						<?php echo esc_html( get_the_date() ); ?>
					</span>
                    </div>

                    <div class="yivic-lite-search__item-excerpt">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
            <?php endwhile; ?>

            <div class="yivic-lite-search__pagination">
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