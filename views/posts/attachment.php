<?php
/**
 * Attachment content view.
 *
 * Renders a single attachment (image, document, etc.) with
 * its caption, description, meta information and optional comments.
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

        $mime_type   = get_post_mime_type();
        $parent_post = get_post( wp_get_post_parent_id( get_the_ID() ) );
        ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'yivic-lite-attachment' ); ?>>

            <header class="yivic-lite-attachment__header">
                <h1 class="yivic-lite-attachment__title">
                    <?php the_title(); ?>
                </h1>

                <div class="yivic-lite-attachment__meta">
					<span class="yivic-lite-attachment__meta-item yivic-lite-attachment__meta-item--date">
						<?php echo esc_html( get_the_date() ); ?>
					</span>

                    <?php if ( $parent_post ) : ?>
                        <span class="yivic-lite-attachment__meta-item yivic-lite-attachment__meta-item--parent">
							<?php
                            /* translators: %s: parent post title */
                            printf(
                                esc_html__( 'Attached to: %s', 'yivic-lite' ),
                                sprintf(
                                    '<a href="%1$s">%2$s</a>',
                                    esc_url( get_permalink( $parent_post ) ),
                                    esc_html( get_the_title( $parent_post ) )
                                )
                            );
                            ?>
						</span>
                    <?php endif; ?>

                    <?php if ( $mime_type ) : ?>
                        <span class="yivic-lite-attachment__meta-item yivic-lite-attachment__meta-item--mime">
							<?php echo esc_html( $mime_type ); ?>
						</span>
                    <?php endif; ?>
                </div>
            </header>

            <div class="yivic-lite-attachment__content">
                <?php
                // If this is an image attachment, render the image with a medium-large size.
                if ( wp_attachment_is_image( get_the_ID() ) ) :
                    ?>
                    <div class="yivic-lite-attachment__image">
                        <?php
                        echo wp_get_attachment_image(
                            get_the_ID(),
                            'large',
                            false,
                            [
                                'class' => 'yivic-lite-attachment__image-tag',
                            ]
                        );
                        ?>
                    </div>
                <?php endif; ?>

                <?php if ( has_excerpt() ) : ?>
                    <div class="yivic-lite-attachment__caption">
                        <?php the_excerpt(); ?>
                    </div>
                <?php endif; ?>

                <div class="yivic-lite-attachment__description">
                    <?php
                    /**
                     * Use the_content() so that any blocks or additional
                     * description for the attachment are rendered correctly.
                     */
                    the_content();

                    // Paginated content support if needed.
                    wp_link_pages(
                        [
                            'before' => '<div class="yivic-lite-attachment__pages">',
                            'after'  => '</div>',
                        ]
                    );
                    ?>
                </div>

                <div class="yivic-lite-attachment__download">
                    <a
                        class="yivic-lite-attachment__download-link"
                        href="<?php echo esc_url( wp_get_attachment_url() ); ?>"
                    >
                        <?php esc_html_e( 'Download file', 'yivic-lite' ); ?>
                    </a>
                </div>
            </div>

            <footer class="yivic-lite-attachment__footer">
                <?php
                // Display comments if open or if there are existing comments.
                if ( comments_open() || get_comments_number() ) :
                    ?>
                    <section class="yivic-lite-attachment__comments">
                        <?php comments_template(); ?>
                    </section>
                <?php endif; ?>
            </footer>
        </article>

    <?php
    endwhile;
else :
    ?>

    <p class="yivic-lite-attachment__empty">
        <?php esc_html_e( 'No attachment found.', 'yivic-lite' ); ?>
    </p>

<?php
endif;

// Reset global post data.
wp_reset_postdata();