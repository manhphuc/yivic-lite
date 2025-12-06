<div class="yivic-lite-post__content">
    <?php
    /**
     * Use the_content() to render full block markup.
     * This is critical so headings, lists and other
     * Gutenberg blocks are displayed correctly.
     */
    the_content();

    // Paginated posts support.
    wp_link_pages(
        [
            'before' => '<div class="yivic-lite-post__pages">',
            'after'  => '</div>',
        ]
    );
    ?>
</div>