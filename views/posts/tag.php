<?php
/**
 * Tag archive content view.
 *
 * @var WP_Query|null $query
 */

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

defined( 'ABSPATH' ) || exit;

echo YivicLite_WP_Theme::view()->render(
        'views/posts/_term-archive',
        [
                'section_class'     => 'yivic-lite-tag-archive',
                'header_class'      => 'yivic-lite-tag-archive__header',
                'title_class'       => 'yivic-lite-tag-archive__title',
                'description_class' => 'yivic-lite-tag-archive__description',
                'list_class'        => 'yivic-lite-tag-archive__list',
                'empty_class'       => 'yivic-lite-tag-archive__empty',
                'title_fallback'    => __( 'Tag archives', 'yivic-lite' ),
                'empty_text'        => __( 'No posts found for this tag.', 'yivic-lite' ),
                'query'             => $query ?? null,
        ]
);