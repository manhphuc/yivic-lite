<?php
/**
 * 404 content view.
 *
 * Displays a clear “not found” message together with a search form
 * and a link back to the home page.
 */

defined( 'ABSPATH' ) || exit;
?>

<section class="yivic-lite-404">
    <header class="yivic-lite-404__header">
        <h1 class="yivic-lite-404__title">
            <?php esc_html_e( 'Page not found', 'yivic-lite' ); ?>
        </h1>

        <p class="yivic-lite-404__subtitle">
            <?php esc_html_e( 'It looks like nothing was found at this location. You can try searching or return to the home page.', 'yivic-lite' ); ?>
        </p>
    </header>

    <div class="yivic-lite-404__body">
        <div class="yivic-lite-404__search">
            <?php get_search_form(); ?>
        </div>

        <p class="yivic-lite-404__back-home">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="yivic-lite-404__home-link">
                <?php esc_html_e( 'Back to home page', 'yivic-lite' ); ?>
            </a>
        </p>
    </div>
</section>