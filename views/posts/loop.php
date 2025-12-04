<?php
/** @var WP_Query $query */

if (!$query->have_posts()) : ?>
    <p><?php esc_html_e('No posts found.', 'yivic-lite'); ?></p>
<?php else : ?>

    <?php while ($query->have_posts()) : $query->the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('yivic-post'); ?>>

            <?php if ( has_post_thumbnail() ) : ?>
                <figure class="yivic-lite-post__thumbnail">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail( 'large' ); ?>
                    </a>
                </figure>
            <?php endif; ?>


            <h2 class="yivic-lite-post__title">
                <a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                </a>
            </h2>

            <div class="yivic-lite-post__meta">
                <?php the_time(get_option('date_format')); ?>
            </div>

            <div class="yivic-lite-post__excerpt">
                <?php the_excerpt(); ?>
            </div>

        </article>
    <?php endwhile; ?>

    <?php wp_reset_postdata(); ?>

<?php endif; ?>