<?php
defined( 'ABSPATH' ) || exit;

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class( 'yivic-lite-page' ); ?>>
<?php wp_body_open(); ?>

<div class="yivic-lite-page__wrap">
    <?php echo YivicLite_WP_Theme::view()->render( 'views/partials/header' ); ?>