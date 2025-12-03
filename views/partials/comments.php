<?php
/**
 * Comments partial.
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
 */

defined( 'ABSPATH' ) || exit;

$textDomain = isset( $textDomain ) ? $textDomain : 'yivic-lite';

// If comments are closed and there are no comments, don't output anything.
if ( ! comments_open() && get_comments_number() === 0 ) {
    return;
}
?>

<section id="comments" class="yivic-lite-comments">

    <?php if ( have_comments() ) : ?>
        <h2 class="yivic-lite-comments__title">
            <?php
            $count = get_comments_number();
            if ( $count === 1 ) {
                esc_html_e( 'One comment', $textDomain );
            } else {
                /* translators: %s: number of comments */
                printf(
                    esc_html__( '%s comments', $textDomain ),
                    esc_html( (string) $count )
                );
            }
            ?>
        </h2>

        <ol class="yivic-lite-comments__list">
        <?php
        wp_list_comments(
            [
                'style'      => 'ol',
                'short_ping' => true,
                'avatar_size'=> 48,
                'callback'   => function( $comment, $args, $depth ) use ( $textDomain ) {
                    $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
                    ?>
                    <<?php echo $tag; ?> <?php comment_class( 'yivic-lite-comments__item' ); ?> id="comment-<?php comment_ID(); ?>">
                    <article class="yivic-lite-comments__body" id="div-comment-<?php comment_ID(); ?>">

                        <header class="yivic-lite-comments__meta">
                            <div class="yivic-lite-comments__author">
                                <?php echo get_avatar( $comment, 48 ); ?>
                                <span class="yivic-lite-comments__author-name">
                                            <?php comment_author(); ?>
                                        </span>
                            </div>

                            <div class="yivic-lite-comments__date">
                                <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
                                    <?php
                                    /* translators: 1: comment date, 2: comment time */
                                    printf(
                                        esc_html__( '%1$s at %2$s', $textDomain ),
                                        get_comment_date(),
                                        get_comment_time()
                                    );
                                    ?>
                                </a>
                            </div>
                        </header>

                        <?php if ( '0' === $comment->comment_approved ) : ?>
                            <p class="yivic-lite-comments__moderation">
                                <?php esc_html_e( 'Your comment is awaiting moderation.', $textDomain ); ?>
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
                                            'add_below' => 'div-comment',
                                            'depth'     => $depth,
                                            'max_depth' => $args['max_depth'],
                                            'reply_text'=> esc_html__( 'Reply', $textDomain ),
                                        ]
                                    )
                                );
                                ?>
                            </div>
                        </footer>
                    </article>
                    </<?php echo $tag; ?>>
                    <?php
                },
            ]
        );
        ?>
        </ol>

        <?php
        // Comment pagination (if many comments).
        the_comments_pagination(
            [
                'prev_text' => esc_html__( 'Previous comments', $textDomain ),
                'next_text' => esc_html__( 'Next comments', $textDomain ),
            ]
        );
        ?>

    <?php endif; ?>

    <?php if ( comments_open() ) : ?>
        <div class="yivic-lite-comments__form">
            <?php
            comment_form(
                [
                    'title_reply'          => esc_html__( 'Leave a comment', $textDomain ),
                    'title_reply_before'   => '<h3 class="yivic-lite-comments__form-title">',
                    'title_reply_after'    => '</h3>',
                    'class_container'      => 'yivic-lite-comments__form-wrapper',
                    'class_submit'         => 'yivic-lite-comments__submit',
                    'comment_field'        => '<p class="yivic-lite-comments__field yivic-lite-comments__field--comment">' .
                        '<label for="comment">' . esc_html__( 'Comment', $textDomain ) . '</label>' .
                        '<textarea id="comment" name="comment" rows="5" required></textarea>' .
                        '</p>',
                    'fields'               => [
                        'author' =>
                            '<p class="yivic-lite-comments__field yivic-lite-comments__field--author">' .
                            '<label for="author">' . esc_html__( 'Name', $textDomain ) . '</label>' .
                            '<input id="author" name="author" type="text" value="" required />' .
                            '</p>',
                        'email'  =>
                            '<p class="yivic-lite-comments__field yivic-lite-comments__field--email">' .
                            '<label for="email">' . esc_html__( 'Email', $textDomain ) . '</label>' .
                            '<input id="email" name="email" type="email" value="" required />' .
                            '</p>',
                        'url'    =>
                            '<p class="yivic-lite-comments__field yivic-lite-comments__field--url">' .
                            '<label for="url">' . esc_html__( 'Website', $textDomain ) . '</label>' .
                            '<input id="url" name="url" type="url" value="" />' .
                            '</p>',
                    ],
                ]
            );
            ?>
        </div>
    <?php elseif ( get_comments_number() ) : ?>
        <p class="yivic-lite-comments__closed">
            <?php esc_html_e( 'Comments are closed.', $textDomain ); ?>
        </p>
    <?php endif; ?>

</section>