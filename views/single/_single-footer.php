<footer class="yivic-lite-post__footer">
    <div class="yivic-lite-post__categories">
        <?php
        $categories_list = get_the_category_list( ', ' );
        if ( $categories_list ) :
            ?>
            <span class="yivic-lite-post__label">
                            <?php esc_html_e( 'Categories:', 'yivic-lite' ); ?>
                        </span>
            <span class="yivic-lite-post__value">
                            <?php echo wp_kses_post( $categories_list ); ?>
                        </span>
        <?php endif; ?>
    </div>

    <div class="yivic-lite-post__tags">
        <?php
        $tags_list = get_the_tag_list( '', ', ' );
        if ( $tags_list ) :
            ?>
            <span class="yivic-lite-post__label">
                            <?php esc_html_e( 'Tags:', 'yivic-lite' ); ?>
                        </span>
            <span class="yivic-lite-post__value">
                            <?php echo wp_kses_post( $tags_list ); ?>
                        </span>
        <?php endif; ?>
    </div>
</footer>