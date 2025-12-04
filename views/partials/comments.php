<?php
/**
 * Comments template partial.
 *
 * Displays the list of comments, navigation, and the comment form.
 *
 * Block: yivic-lite-comments
 * Elements:
 *   - __title
 *   - __list
 *   - __item
 *   - __meta
 *   - __author
 *   - __date
 *   - __content
 *   - __reply
 *   - __form
 *
 * @package Yivic_Lite
 */

defined( 'ABSPATH' ) || exit;

// Theme Check prefers literal text domains instead of variables.
$text_domain = 'yivic-lite';

// If comments are closed and there are no comments, bail early.
if ( ! comments_open() && 0 === (int) get_comments_number() ) {
    return;
}
?>

<section id="comments" class="yivic-lite-comments">

    <?php if ( have_comments() ) : ?>

        <h2 class="yivic-lite-comments__title">
            <?php
            $count = (int) get_comments_number();

            if ( 1 === $count ) {
                esc_html_e( 'One comment', 'yivic-lite' );
            } else {
                /* translators: %s: number of comments. */
                printf(
                        esc_html__( '%s comments', 'yivic-lite' ),
                        esc_html( (string) $count )
                );
            }
            ?>
        </h2>

        <ol class="yivic-lite-comments__list">
        <?php
        wp_list_comments(
                [
                        'style'       => 'ol',
                        'short_ping'  => true,
                        'avatar_size' => 48,
                        'callback'    => function ( $comment, $args, $depth ) use ( $text_domain ) {

                            $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
                            ?>

                            <<?php echo esc_attr( $tag ); ?> <?php comment_class( 'yivic-lite-comments__item' ); ?> id="comment-<?php comment_ID(); ?>">

                            <article id="div-comment-<?php comment_ID(); ?>" class="yivic-lite-comments__body">

                                <header class="yivic-lite-comments__meta">

                                    <div class="yivic-lite-comments__author">
                                        <?php echo get_avatar( $comment, 48 ); ?>
                                        <span class="yivic-lite-comments__author-name">
											<?php echo esc_html( get_comment_author() ); ?>
										</span>
                                    </div>

                                    <div class="yivic-lite-comments__date">
                                        <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
                                            <?php
                                            /* translators: 1: comment date, 2: comment time. */
                                            printf(
                                                    esc_html__( '%1$s at %2$s', 'yivic-lite' ),
                                                    esc_html( get_comment_date() ),
                                                    esc_html( get_comment_time() )
                                            );
                                            ?>
                                        </a>
                                    </div>

                                </header>

                                <?php if ( '0' === $comment->comment_approved ) : ?>
                                    <p class="yivic-lite-comments__moderation">
                                        <?php esc_html_e( 'Your comment is awaiting moderation.', 'yivic-lite' ); ?>
                                    </p>
                                <?php endif; ?>

                                <div class="yivic-lite-comments__content">
                                    <?php comment_text(); ?>
                                </div>

                                <footer class="yivic-lite-comments__footer">
                                    <div class="yivic-lite-comments__reply">
                                        <?php
                                        comment_reply_link(
                                                array_merge(
                                                        $args,
                                                        [
                                                                'add_below'  => 'div-comment',
                                                                'depth'      => $depth,
                                                                'max_depth'  => $args['max_depth'],
                                                                'reply_text' => esc_html__( 'Reply', 'yivic-lite' ),
                                                        ]
                                                )
                                        );
                                        ?>
                                    </div>
                                </footer>

                            </article>

                            </<?php echo esc_attr( $tag ); ?>>

                            <?php
                        },
                ]
        );
        ?>
        </ol>

        <?php
        the_comments_pagination(
                [
                        'prev_text' => esc_html__( 'Previous comments', 'yivic-lite' ),
                        'next_text' => esc_html__( 'Next comments', 'yivic-lite' ),
                ]
        );
        ?>

    <?php endif; ?>

    <?php if ( comments_open() ) : ?>

        <div class="yivic-lite-comments__form">
            <?php
            comment_form(
                    [
                            'title_reply'        => esc_html__( 'Leave a comment', 'yivic-lite' ),
                            'title_reply_before' => '<h3 class="yivic-lite-comments__form-title">',
                            'title_reply_after'  => '</h3>',
                            'class_container'    => 'yivic-lite-comments__form-wrapper',
                            'class_submit'       => 'yivic-lite-comments__submit',
                            'comment_field'      =>
                                    '<p class="yivic-lite-comments__field yivic-lite-comments__field--comment">' .
                                    '<label for="comment">' . esc_html__( 'Comment', 'yivic-lite' ) . '</label>' .
                                    '<textarea id="comment" name="comment" rows="5" required></textarea>' .
                                    '</p>',
                            'fields'             => [
                                    'author' =>
                                            '<p class="yivic-lite-comments__field yivic-lite-comments__field--author">' .
                                            '<label for="author">' . esc_html__( 'Name', 'yivic-lite' ) . '</label>' .
                                            '<input id="author" name="author" type="text" value="" required />' .
                                            '</p>',
                                    'email'  =>
                                            '<p class="yivic-lite-comments__field yivic-lite-comments__field--email">' .
                                            '<label for="email">' . esc_html__( 'Email', 'yivic-lite' ) . '</label>' .
                                            '<input id="email" name="email" type="email" value="" required />' .
                                            '</p>',
                                    'url'    =>
                                            '<p class="yivic-lite-comments__field yivic-lite-comments__field--url">' .
                                            '<label for="url">' . esc_html__( 'Website', 'yivic-lite' ) . '</label>' .
                                            '<input id="url" name="url" type="url" value="" />' .
                                            '</p>',
                            ],
                    ]
            );
            ?>
        </div>

    <?php elseif ( get_comments_number() ) : ?>

        <p class="yivic-lite-comments__closed">
            <?php esc_html_e( 'Comments are closed.', 'yivic-lite' ); ?>
        </p>

    <?php endif; ?>

</section>