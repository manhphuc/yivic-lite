<?php
/**
 * Generic term archive view (category, tag, custom taxonomy).
 *
 * Expected params (array):
 * - section_class       string  Wrapper <section> class
 * - header_class        string  Header wrapper class
 * - title_class         string  Title class
 * - description_class   string  Description text class
 * - list_class          string  Posts list wrapper class
 * - empty_class         string  Empty text class
 * - title_fallback      string  Fallback title when term has no name
 * - empty_text          string  Text when no posts found
 *
 * @var WP_Query|null $query
 */

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

defined( 'ABSPATH' ) || exit;

// Ensure we have a valid query object.
if ( ! isset( $query ) || ! $query instanceof WP_Query ) {
    global $wp_query;
    $query = $wp_query;
}

// Get current term object (category, tag, or any taxonomy).
$term = get_queried_object();

$term_name        = '';
$term_description = '';

if ( $term instanceof WP_Term ) {
    $term_name        = $term->name;
    $term_description = term_description( $term );
}

// Safe defaults for classes (avoid notices if a key is missing).
$section_class     = $section_class ?? 'yivic-lite-term-archive';
$header_class      = $header_class ?? $section_class . '__header';
$title_class       = $title_class ?? $section_class . '__title';
$description_class = $description_class ?? $section_class . '__description';
$list_class        = $list_class ?? $section_class . '__list';
$empty_class       = $empty_class ?? $section_class . '__empty';

$title_fallback = $title_fallback ?? __('Archives', 'yivic-lite');
$empty_text     = $empty_text ?? __('No posts found.', 'yivic-lite');
?>
    <section class="<?php echo esc_attr( $section_class ); ?>">
        <header class="<?php echo esc_attr( $header_class ); ?>">
            <h1 class="<?php echo esc_attr( $title_class ); ?>">
                <?php
                if ( $term_name ) {
                    echo esc_html( $term_name );
                } else {
                    echo esc_html( $title_fallback );
                }
                ?>
            </h1>
            <?php if ( ! empty( $term_description ) ) : ?>
                <div class="<?php echo esc_attr( $description_class ); ?>">
                    <?php echo wp_kses_post( wpautop( $term_description ) ); ?>
                </div>
            <?php endif; ?>
        </header>
        <div class="<?php echo esc_attr( $list_class ); ?>">
            <?php if ( $query->have_posts() ) : ?>
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <?php echo YivicLite_WP_Theme::view()->render( 'views/posts/_post-item' ); ?>
                <?php endwhile; ?>
                <?php echo YivicLite_WP_Theme::view()->render(
                    'views/partials/pagination/_pagination',
                    [
                        'query' => $query,
                    ]
                ); ?>
            <?php else : ?>
                <p class="<?php echo esc_attr( $empty_class ); ?>">
                    <?php echo esc_html( $empty_text ); ?>
                </p>
            <?php endif; ?>
        </div>
    </section>
<?php
// Reset global post data.
wp_reset_postdata();