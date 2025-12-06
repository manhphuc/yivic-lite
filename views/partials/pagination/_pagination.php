<?php
/**
 * Pagination partial.
 *
 * @var WP_Query $query
 */
defined( 'ABSPATH' ) || exit;

// Guard: make sure we have a valid query object
if ( empty( $query ) || ! ( $query instanceof WP_Query ) ) {
    return;
}

// No pagination needed if only 1 page
if ( $query->max_num_pages <= 1 ) {
    return;
}

$yivic_paged = max( 1, get_query_var( 'paged' ) );

$yivic_pagination = paginate_links( [
    'total'     => (int) $query->max_num_pages,
    'current'   => $yivic_paged,
    'mid_size'  => 2,
    'prev_text' => '&laquo;',
    'next_text' => '&raquo;',
    'type'      => 'list', // outputs <ul>…</ul>
] );

if ( ! empty( $yivic_pagination ) ) : ?>
    <nav class="yivic-pagination" aria-label="<?php esc_attr_e( 'Posts pagination', 'yivic-lite' ); ?>">
        <?php echo wp_kses_post( $yivic_pagination ); ?>
    </nav>
<?php endif; ?>