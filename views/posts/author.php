<?php
/**
 * Author archive content view.
 *
 * Displays the author bio (if available) and a list of posts
 * written by that author, using the main query.
 *
 * @var WP_Query|null $query
 */

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

        <?php if ( $query->have_posts() ) : ?>
            <div class="yivic-lite-author-archive__list">
                <?php
                while ( $query->have_posts() ) :
                    $query->the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'yivic-lite-author-archive__item' ); ?>>
                        <h2 class="yivic-lite-author-archive__item-title">
                            <a href="<?php the_permalink(); ?>" class="yivic-lite-author-archive__item-link">
                                <?php the_title(); ?>
                            </a>
                        </h2>

                        <div class="yivic-lite-author-archive__item-meta">
						<span class="yivic-lite-author-archive__item-date">
							<?php echo esc_html( get_the_date() ); ?>
						</span>
                        </div>

                        <div class="yivic-lite-author-archive__item-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                    </article>
                <?php
                endwhile;
                ?>
            </div>

            <div class="yivic-lite-author-archive__pagination">
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

            <p class="yivic-lite-author-archive__empty">
                <?php esc_html_e( 'This author has not published any posts yet.', 'yivic-lite' ); ?>
            </p>

        <?php endif; ?>

    </section>

<?php
// Reset global post data.
wp_reset_postdata();