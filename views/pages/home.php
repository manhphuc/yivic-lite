<?php
/** @var WP_Query $query */

use Yivic\YivicLite\Theme\WP\YivicLite_WP_Theme;

// render loop view
$loop = YivicLite_WP_Theme::view()->render('views/posts/loop', [
    'query' => $query,
]);

// inject loop into layout
echo YivicLite_WP_Theme::view()->render('views/layouts/main', [
    'content' => $loop,
]);