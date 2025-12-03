<?php
/**
 * Archive listing view.
 *
 * Displays post lists for category, tag, author, date,
 * taxonomy archives, and custom post types.
 *
 * @var WP_Query|null $query
 */

defined('ABSPATH') || exit;

// Ensure query exists
if (!isset($query) || !$query instanceof WP_Query) {
    global $wp_query;
    $query = $wp_query;
}
?>

<header class="yivic-lite-archive__header">
    <h1 class="yivic-lite-archive__title">
        <?php the_archive_title(); ?>
    </h1>

    <p class="yivic-lite-archive__description">
        <?php the_archive_description(); ?>
    </p>
</header>

<div class="yivic-lite-archive__list">
    <?php if ($query->have_posts()) : ?>

        <?php while ($query->have_posts()) : $query->the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('yivic-lite-archive__item'); ?>>

                <h2 class="yivic-lite-archive__item-title">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                    </a>
                </h2>

                <div class="yivic-lite-archive__item-meta">
                    <span><?php echo esc_html(get_the_date()); ?></span>
                </div>

                <div class="yivic-lite-archive__item-excerpt">
                    <?php the_excerpt(); ?>
                </div>

            </article>
        <?php endwhile; ?>

        <div class="yivic-lite-archive__pagination">
            <?php
            the_posts_pagination([
                'prev_text' => __('Previous', 'yivic-lite'),
                'next_text' => __('Next', 'yivic-lite'),
            ]);
            ?>
        </div>

    <?php else : ?>

        <p class="yivic-lite-archive__empty">
            <?php esc_html_e('No posts found.', 'yivic-lite'); ?>
        </p>

    <?php endif; ?>
</div>

<?php wp_reset_postdata(); ?>