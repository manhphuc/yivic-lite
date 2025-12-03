<?php
defined('ABSPATH') || exit;

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;
?>
    <!doctype html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <?php wp_head(); ?>
    </head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
// Render view partial: views/partials/header.php
echo YivicLite_WP_Theme::view()->render('views/partials/header');