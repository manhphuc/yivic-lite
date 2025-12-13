<?php

use Yivic\YivicLite\Theme\Services\ViewService;
use Yivic\YivicLite\Theme\Services\YivicLiteService;

// Make sure these constants are defined in functions.php
// defined('YIVIC_LITE_VERSION') || define('YIVIC_LITE_VERSION', '1.0.8');

$textDomain = 'yivic-lite';

return [
    'version'    => YIVIC_LITE_VERSION,
    'basePath'   => get_template_directory(),
    'baseUrl'    => get_template_directory_uri(),
    'textDomain' => $textDomain,
    'themeSlug'  => 'yivic-lite',

    'services' => [
        ViewService::class => [
            // Reserved for future view config (namespaces, cache, etc.).
        ],

        YivicLiteService::class => [
            'textDomain'      => $textDomain,
            // Example flags – you can change later:
            'autoAppendToHook' => true,
        ],
    ],
];