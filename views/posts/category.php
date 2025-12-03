<?php
/**
 * Category archive content view.
 *
 * Displays the current category title, optional description,
 * and a list of posts that belong to the category.
 *
 * @var WP_Query|null $query
 */

defined( 'ABSPATH' ) || exit;

// Ensure we have a valid query object.
if ( ! isset( $query ) || ! $query instanceof WP_Query ) {
    global $wp_query;
    $query = $wp_query;
}

// Get the current category object.
$category = get_queried_object();

$category_name        = '';
$category_description = '';

if ( $category instanceof WP_Term ) {
    $category_name        = $category->name;
    $category_description = term_description( $category );
}
?>

    <section class="yivic-lite-category-archive">
        <header class="yivic-lite-category-archive__header">
            <h1 class="yivic-lite-category-archive__title">
                <?php
                if ( $category_name ) {
                    echo esc_html( $category_name );
                } else {
                    esc_html_e( 'Category archives', 'yivic-lite' );
                }
                ?>
            </h1>

            <?php if ( ! empty( $category_description ) ) : ?>
                <div class="yivic-lite-category-archive__description">
                    <?php
                    // Description is already filtered; allow safe HTML.
                    echo wp_kses_post( wpautop( $category_description ) );
                    ?>
                </div>
            <?php endif; ?>
        </header>

        <?php if ( $query->have_posts() ) : ?>
            <div class="yivic-lite-category-archive__list">
                <?php
                while ( $query->have_posts() ) :
                    $query->the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'yivic-lite-category-archive__item' ); ?>>
                        <h2 class="yivic-lite-category-archive__item-title">
                            <a href="<?php the_permalink(); ?>" class="yivic-lite-category-archive__item-link">
                                <?php the_title(); ?>
                            </a>
                        </h2>

                        <div class="yivic-lite-category-archive__item-meta">
						<span class="yivic-lite-category-archive__item-date">
							<?php echo esc_html( get_the_date() ); ?>
						</span>

                            <span class="yivic-lite-category-archive__item-author">
							<?php
                            /* translators: %s: post author name */
                            printf(
                                esc_html__( 'by %s', 'yivic-lite' ),
                                esc_html( get_the_author() )
                            );
                            ?>
						</span>
                        </div>

                        <div class="yivic-lite-category-archive__item-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                    </article>
                <?php
                endwhile;
                ?>
            </div>

            <div class="yivic-lite-category-archive__pagination">
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

            <p class="yivic-lite-category-archive__empty">
                <?php esc_html_e( 'No posts found in this category.', 'yivic-lite' ); ?>
            </p>

        <?php endif; ?>

    </section>

<?php
// Reset global post data.
wp_reset_postdata();