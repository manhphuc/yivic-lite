<?php
/**
 * Archive listing view.
 *
 * Displays post lists for category, tag, author, date,
 * taxonomy archives, and custom post types.
 *
 * @var WP_Query|null $query
 */
use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

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
    <?php if ( $query->have_posts() ) : ?>
        <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            <?php echo YivicLite_WP_Theme::view()->render( 'views/posts/_post-item' ); ?>
        <?php endwhile; ?>
        <?php echo YivicLite_WP_Theme::view()->render(
                'views/partials/pagination/_pagination', [
                'query' => $query,
        ] ); ?>
    <?php else : ?>
        <p class="yivic-lite-archive__empty">
            <?php esc_html_e('No posts found.', 'yivic-lite'); ?>
        </p>
    <?php endif; ?>
</div>
<?php wp_reset_postdata(); ?>