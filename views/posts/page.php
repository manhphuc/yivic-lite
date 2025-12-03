<?php
/**
 * Static page content view.
 *
 * Renders the full page content using the_content(),
 * so Gutenberg blocks are displayed correctly.
 *
 * @var WP_Query|null $query
 */

defined( 'ABSPATH' ) || exit;

// Ensure we have a valid query object.
if ( ! isset( $query ) || ! $query instanceof WP_Query ) {
    global $wp_query;
    $query = $wp_query;
}

if ( $query->have_posts() ) :
    while ( $query->have_posts() ) :
        $query->the_post();
        ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'yivic-lite-page' ); ?>>
            <header class="yivic-lite-page__header">
                <h1 class="yivic-lite-page__title">
                    <?php the_title(); ?>
                </h1>
            </header>

            <div class="yivic-lite-page__content">
                <?php
                the_content();

                // Support for paginated pages.
                wp_link_pages(
                    [
                        'before' => '<div class="yivic-lite-page__pagination">',
                        'after'  => '</div>',
                    ]
                );
                ?>
            </div>

            <footer class="yivic-lite-page__footer">
                <?php
                edit_post_link(
                    esc_html__( 'Edit this page', 'yivic-lite' ),
                    '<span class="yivic-lite-page__edit">',
                    '</span>'
                );
                ?>
            </footer>

            <?php if ( comments_open() || get_comments_number() ) : ?>
                <section class="yivic-lite-page__comments">
                    <?php comments_template(); ?>
                </section>
            <?php endif; ?>
        </article>

    <?php
    endwhile;
else :
    ?>

    <p class="yivic-lite-page__empty">
        <?php esc_html_e( 'This page has no content yet.', 'yivic-lite' ); ?>
    </p>

<?php
endif;

// Reset global post data.
wp_reset_postdata();