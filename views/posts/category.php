<?php
/**
 * Category archive content view.
 *
 * @var WP_Query|null $query
 */
use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

defined( 'ABSPATH' ) || exit;

echo YivicLite_WP_Theme::view()->render(
    'views/posts/_term-archive',
    [
        'section_class'     => 'yivic-lite-category-archive',
        'header_class'      => 'yivic-lite-category-archive__header',
        'title_class'       => 'yivic-lite-category-archive__title',
        'description_class' => 'yivic-lite-category-archive__description',
        'list_class'        => 'yivic-lite-category-archive__list',
        'empty_class'       => 'yivic-lite-category-archive__empty',
        'title_fallback'    => __( 'Category archives', 'yivic-lite' ),
        'empty_text'        => __( 'No posts found in this category.', 'yivic-lite' ),
        'query'             => $query ?? null,
    ]
);