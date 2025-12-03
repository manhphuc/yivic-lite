<?php
defined('ABSPATH') || exit;

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

// Render footer partial
echo YivicLite_WP_Theme::view()->render('views/partials/footer');
?>

<?php wp_footer(); ?>
</body>
</html>