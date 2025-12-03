<?php
defined( 'ABSPATH' ) || exit;

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

// Visual footer block.
echo YivicLite_WP_Theme::view()->render( 'views/partials/footer' );
?>

</div><!-- .yivic-lite-page__wrap -->

<?php wp_footer(); ?>
</body>
</html>