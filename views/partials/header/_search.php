<!-- Search -->
<div class="yivic-lite-header__search search-box">
    <button
        class="yivic-lite-header__search-icon yivicSearch-icon yivicSearch"
        type="button"
        aria-label="<?php esc_attr_e( 'Search', 'yivic-lite' ); ?>"
    ></button>
    <div class="yivic-lite-header__search-input input-box">
        <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" >
            <label class="screen-reader-text" for="yivic-lite-header-search">
                <?php esc_html_e( 'Search for:', 'yivic-lite' ); ?>
            </label>
            <input
                id="yivic-lite-header-search"
                type="search"
                name="s"
                value="<?php echo esc_attr( get_search_query() ); ?>"
                placeholder="<?php esc_attr_e( 'Search…', 'yivic-lite' ); ?>"
            >
            <button type="submit" class="screen-reader-text">
                <?php esc_html_e( 'Search', 'yivic-lite' ); ?>
            </button>
        </form>
    </div>
</div>