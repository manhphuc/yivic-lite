<?php
/**
 * Search form template
 *
 * @package Yivic_Lite
 */

$yivic_search_id = esc_attr( uniqid( 'yivic-lite-header-search-' ) );
?>

<form
    role="search"
    method="get"
    class="yivic-lite-search-form"
    action="<?php echo esc_url( home_url( '/' ) ); ?>"
>
    <label class="screen-reader-text" for="<?php echo $yivic_search_id; ?>">
        <?php esc_html_e( 'Search for:', 'yivic-lite' ); ?>
    </label>

    <input
        id="<?php echo $yivic_search_id; ?>"
        type="search"
        name="s"
        class="yivic-lite-search-form__field"
        value="<?php echo esc_attr( get_search_query() ); ?>"
        placeholder="<?php esc_attr_e( 'Search…', 'yivic-lite' ); ?>"
    />

    <button type="submit" class="yivic-lite-search-form__submit screen-reader-text">
        <?php esc_html_e( 'Search', 'yivic-lite' ); ?>
    </button>
</form>
