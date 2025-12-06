<?php
/**
 * Breadcrumb partial.
 *
 * Renders a semantic breadcrumb trail using the BreadcrumbHelper.
 * Includes schema.org markup for better SEO.
 */
use Yivic\YivicLite\Theme\Helpers\BreadcrumbHelper;

defined( 'ABSPATH' ) || exit;

// Build items for current context.
$items = BreadcrumbHelper::getItems();

// Do not render if there is nothing (or only "Home" on front page).
if ( empty( $items ) || count( $items ) <= 1 ) {
    return;
}
?>
<nav class="yivic-lite-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'yivic-lite' ); ?>">
    <ol class="yivic-lite-breadcrumb__list" itemscope itemtype="https://schema.org/BreadcrumbList">
        <?php foreach ( $items as $index => $item ) : ?>
            <?php
            $position   = $index + 1;
            $is_current = ! empty( $item['is_current'] );
            $label      = isset( $item['label'] ) ? (string) $item['label'] : '';
            $url        = isset( $item['url'] ) ? $item['url'] : null;

            $item_classes = 'yivic-lite-breadcrumb__item';
            if ( $is_current ) {
                $item_classes .= ' yivic-lite-breadcrumb__item--current';
            }
            ?>
            <li class="<?php echo esc_attr( $item_classes ); ?>"
                itemprop="itemListElement"
                itemscope
                itemtype="https://schema.org/ListItem">
                <?php if ( $url && ! $is_current ) : ?>
                    <a href="<?php echo esc_url( $url ); ?>" class="yivic-lite-breadcrumb__link" itemprop="item">
                        <span itemprop="name"><?php echo esc_html( $label ); ?></span>
                    </a>
                <?php else : ?>
                    <span class="yivic-lite-breadcrumb__current" itemprop="name">
                        <?php echo esc_html( $label ); ?>
                    </span>
                <?php endif; ?>

                <meta itemprop="position" content="<?php echo (int) $position; ?>" />
            </li>
        <?php endforeach; ?>
    </ol>
</nav>