<?php

declare(strict_types=1);

namespace Yivic\YivicLite\Theme\Helpers;

/**
 * Breadcrumb helper for Yivic Lite theme.
 *
 * This class is responsible for building a normalized list
 * of breadcrumb items based on the current query context.
 *
 * Each item has the structure:
 * [
 *     'label'     => string,          // Text to display
 *     'url'       => string|null,     // URL (null for current item)
 *     'is_current'=> bool,            // Whether this is the last / current item
 *     'type'      => string,          // Context type (home, category, post, page, search, etc.)
 * ]
 *
 * The final array is consumed by the breadcrumb view partial.
 */
class BreadcrumbHelper {

    /**
     * Build breadcrumb items for the current request.
     *
     * @return array<int, array<string,mixed>>
     */
    public static function getItems(): array {
        // Option flag: allow turning breadcrumb on/off from Customizer.
        $enabled = (bool) get_theme_mod( 'yivic_lite_show_breadcrumb', true );
        if ( ! $enabled ) {
            return [];
        }

        // Do not show breadcrumb on the front page.
        if ( is_front_page() ) {
            return [];
        }

        $items = [];

        // 1. Home link.
        $items[] = [
            'label'      => __( 'Home', 'yivic-lite' ),
            'url'        => home_url( '/' ),
            'is_current' => is_home() && ! is_singular(),
            'type'       => 'home',
        ];

        // 2. Blog posts index (when a static page is used as posts page).
        if ( is_home() && ! is_front_page() ) {
            $posts_page_id = (int) get_option( 'page_for_posts' );

            if ( $posts_page_id > 0 ) {
                $items[] = [
                    'label'      => get_the_title( $posts_page_id ),
                    'url'        => get_permalink( $posts_page_id ),
                    'is_current' => true,
                    'type'       => 'blog',
                ];
            }

            return self::markCurrent( $items );
        }

        // 3. Singular post (blog post).
        if ( is_singular( 'post' ) ) {
            // Optional: blog index if using a posts page.
            $posts_page_id = (int) get_option( 'page_for_posts' );
            if ( $posts_page_id > 0 ) {
                $items[] = [
                    'label'      => get_the_title( $posts_page_id ),
                    'url'        => get_permalink( $posts_page_id ),
                    'is_current' => false,
                    'type'       => 'blog',
                ];
            }

            // Main category chain (first category only, but you can extend later).
            $categories = get_the_category();
            if ( ! empty( $categories ) ) {
                $main_cat = $categories[0];

                // Parent categories chain.
                $ancestors = get_ancestors( $main_cat->term_id, 'category' );
                $ancestors = array_reverse( $ancestors );

                foreach ( $ancestors as $ancestor_id ) {
                    $ancestor = get_category( $ancestor_id );
                    if ( $ancestor && ! is_wp_error( $ancestor ) ) {
                        $items[] = [
                            'label'      => $ancestor->name,
                            'url'        => get_category_link( $ancestor->term_id ),
                            'is_current' => false,
                            'type'       => 'category',
                        ];
                    }
                }

                // Main category itself.
                $items[] = [
                    'label'      => $main_cat->name,
                    'url'        => get_category_link( $main_cat->term_id ),
                    'is_current' => false,
                    'type'       => 'category',
                ];
            }

            // Current post title.
            $items[] = [
                'label'      => get_the_title(),
                'url'        => null,
                'is_current' => true,
                'type'       => 'post',
            ];

            return self::markCurrent( $items );
        }

        // 4. Singular page (with parent hierarchy).
        if ( is_page() && ! is_front_page() ) {
            global $post;

            // Page parents chain.
            if ( isset( $post->post_parent ) && $post->post_parent ) {
                $parent_ids = array_reverse( get_post_ancestors( $post->ID ) );

                foreach ( $parent_ids as $parent_id ) {
                    $items[] = [
                        'label'      => get_the_title( $parent_id ),
                        'url'        => get_permalink( $parent_id ),
                        'is_current' => false,
                        'type'       => 'page',
                    ];
                }
            }

            // Current page.
            $items[] = [
                'label'      => get_the_title(),
                'url'        => null,
                'is_current' => true,
                'type'       => 'page',
            ];

            return self::markCurrent( $items );
        }

        // 5. Category archive.
        if ( is_category() ) {
            $current = get_queried_object();

            if ( $current && ! is_wp_error( $current ) ) {
                $ancestors = get_ancestors( $current->term_id, 'category' );
                $ancestors = array_reverse( $ancestors );

                foreach ( $ancestors as $ancestor_id ) {
                    $ancestor = get_category( $ancestor_id );
                    if ( $ancestor && ! is_wp_error( $ancestor ) ) {
                        $items[] = [
                            'label'      => $ancestor->name,
                            'url'        => get_category_link( $ancestor->term_id ),
                            'is_current' => false,
                            'type'       => 'category',
                        ];
                    }
                }

                $items[] = [
                    'label'      => single_cat_title( '', false ),
                    'url'        => null,
                    'is_current' => true,
                    'type'       => 'category',
                ];
            }

            return self::markCurrent( $items );
        }

        // 6. Tag archive.
        if ( is_tag() ) {
            $items[] = [
                'label'      => sprintf(
                /* translators: %s: tag name. */
                    __( 'Tag: %s', 'yivic-lite' ),
                    single_tag_title( '', false )
                ),
                'url'        => null,
                'is_current' => true,
                'type'       => 'tag',
            ];

            return self::markCurrent( $items );
        }

        // 7. Author archive.
        if ( is_author() ) {
            $author = get_queried_object();

            $items[] = [
                'label'      => sprintf(
                /* translators: %s: author display name. */
                    __( 'Author: %s', 'yivic-lite' ),
                    $author ? $author->display_name : ''
                ),
                'url'        => null,
                'is_current' => true,
                'type'       => 'author',
            ];

            return self::markCurrent( $items );
        }

        // 8. Date archives.
        if ( is_day() ) {
            $items[] = [
                'label'      => get_the_time( _x( 'F Y', 'monthly archives date format', 'yivic-lite' ) ),
                'url'        => get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ),
                'is_current' => false,
                'type'       => 'month',
            ];

            $items[] = [
                'label'      => get_the_time( _x( 'F j, Y', 'daily archives date format', 'yivic-lite' ) ),
                'url'        => null,
                'is_current' => true,
                'type'       => 'day',
            ];

            return self::markCurrent( $items );
        }

        if ( is_month() ) {
            $items[] = [
                'label'      => get_the_time( _x( 'F Y', 'monthly archives date format', 'yivic-lite' ) ),
                'url'        => null,
                'is_current' => true,
                'type'       => 'month',
            ];

            return self::markCurrent( $items );
        }

        if ( is_year() ) {
            $items[] = [
                'label'      => get_the_time( _x( 'Y', 'yearly archives date format', 'yivic-lite' ) ),
                'url'        => null,
                'is_current' => true,
                'type'       => 'year',
            ];

            return self::markCurrent( $items );
        }

        // 9. Search results.
        if ( is_search() ) {
            $items[] = [
                'label'      => sprintf(
                /* translators: %s: search query. */
                    __( 'Search results for "%s"', 'yivic-lite' ),
                    get_search_query()
                ),
                'url'        => null,
                'is_current' => true,
                'type'       => 'search',
            ];

            return self::markCurrent( $items );
        }

        // 10. 404 page.
        if ( is_404() ) {
            $items[] = [
                'label'      => __( 'Page not found', 'yivic-lite' ),
                'url'        => null,
                'is_current' => true,
                'type'       => '404',
            ];

            return self::markCurrent( $items );
        }

        // Fallback: mark last item as current (usually "Home").
        return self::markCurrent( $items );
    }

    /**
     * Ensure only the last breadcrumb item is marked as current.
     *
     * @param array<int, array<string,mixed>> $items
     * @return array<int, array<string,mixed>>
     */
    protected static function markCurrent( array $items ): array {
        if ( empty( $items ) ) {
            return $items;
        }

        $last_index = count( $items ) - 1;

        foreach ( $items as $index => &$item ) {
            $item['is_current'] = ( $index === $last_index );
        }

        return $items;
    }
}