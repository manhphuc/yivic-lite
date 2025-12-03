<?php
/**
 * Single post content view.
 *
 * Renders the full post content with headings, paragraphs
 * and block markup using the_content(), so Gutenberg blocks
 * are displayed exactly as expected.
 *
 * @var WP_Query|null $query
 */

defined('ABSPATH') || exit;

// Ensure we have a valid query object.
if ( ! isset( $query ) || ! $query instanceof WP_Query ) {
    global $wp_query;
    $query = $wp_query;
}

if ( $query->have_posts() ) :
    while ( $query->have_posts() ) :
        $query->the_post();
        ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'yivic-lite-post' ); ?>>
            <header class="yivic-lite-post__header">
                <h1 class="yivic-lite-post__title">
                    <?php the_title(); ?>
                </h1>

                <div class="yivic-lite-post__meta">
                    <span class="yivic-lite-post__meta-item yivic-lite-post__meta-item--date">
                        <?php echo esc_html( get_the_date() ); ?>
                    </span>

                    <span class="yivic-lite-post__meta-item yivic-lite-post__meta-item--author">
                        <?php
                        /* translators: %s: post author name */
                        printf(
                            esc_html__( 'by %s', 'yivic-lite' ),
                            esc_html( get_the_author() )
                        );
                        ?>
                    </span>
                </div>
            </header>

            <div class="yivic-lite-post__content">
                <?php
                /**
                 * Use the_content() to render full block markup.
                 * This is critical so headings, lists and other
                 * Gutenberg blocks are displayed correctly.
                 */
                the_content();

                // Paginated posts support.
                wp_link_pages(
                    [
                        'before' => '<div class="yivic-lite-post__pages">',
                        'after'  => '</div>',
                    ]
                );
                ?>
            </div>

            <footer class="yivic-lite-post__footer">
                <div class="yivic-lite-post__categories">
                    <?php
                    $categories_list = get_the_category_list( ', ' );
                    if ( $categories_list ) :
                        ?>
                        <span class="yivic-lite-post__label">
                            <?php esc_html_e( 'Categories:', 'yivic-lite' ); ?>
                        </span>
                        <span class="yivic-lite-post__value">
                            <?php echo wp_kses_post( $categories_list ); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <div class="yivic-lite-post__tags">
                    <?php
                    $tags_list = get_the_tag_list( '', ', ' );
                    if ( $tags_list ) :
                        ?>
                        <span class="yivic-lite-post__label">
                            <?php esc_html_e( 'Tags:', 'yivic-lite' ); ?>
                        </span>
                        <span class="yivic-lite-post__value">
                            <?php echo wp_kses_post( $tags_list ); ?>
                        </span>
                    <?php endif; ?>
                </div>
            </footer>
        </article>

    <?php
    endwhile;

    // Post navigation (previous/next).
    the_post_navigation(
        [
            'prev_text' => esc_html__( 'Previous post', 'yivic-lite' ),
            'next_text' => esc_html__( 'Next post', 'yivic-lite' ),
        ]
    );

else :
    ?>

    <p class="yivic-lite-post__empty">
        <?php esc_html_e( 'No content found.', 'yivic-lite' ); ?>
    </p>

<?php
endif;

// Reset global post data.
wp_reset_postdata();