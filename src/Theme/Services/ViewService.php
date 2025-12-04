<?php

declare(strict_types=1);

namespace Yivic\YivicLite\Theme\Services;

use Yivic\YivicLite\Theme\Traits\ConfigTrait;
use Yivic\YivicLite\Theme\Traits\ServiceTrait;
use Yivic\YivicLite\Theme\Traits\WPAttributeTrait;

/**
 * Lightweight view renderer for the Yivic Lite theme.
 * Provides a simple Laravel-style render() method while remaining
 * fully compatible with WordPress theme requirements.
 */
class ViewService {
    use ConfigTrait;
    use ServiceTrait;
    use WPAttributeTrait;

    /**
     * Base filesystem path where view files are located.
     * Usually get_template_directory().
     */
    public string $basePath = '';

    /**
     * Base URL for the theme. Usually get_template_directory_uri().
     */
    public string $baseUrl = '';

    /**
     * Initialize the service using the container.
     * This method is automatically called after binding.
     */
    public function init(): void
    {
        $container = $this->getContainer();

        if (! $container) {
            $this->basePath = get_template_directory();
            $this->baseUrl  = get_template_directory_uri();
            return;
        }

        $this->basePath = property_exists($container, 'basePath') && ! empty($container->basePath)
            ? (string) $container->basePath
            : get_template_directory();

        $this->baseUrl = property_exists($container, 'baseUrl') && ! empty($container->baseUrl)
            ? (string) $container->baseUrl
            : get_template_directory_uri();
    }

    /**
     * Render a view file and return its output.
     *
     * @param string $viewFilePath Example: 'partials/header' or 'views/home/index'
     * @param array  $params       Variables available inside the view
     * @param bool   $echo         true = echo output immediately, false = return output string
     *
     * @return string|null
     */
    public function render( string $viewFilePath, array $params = [], bool $echo = false ): ?string {
        $extension = '.php';
        $fullPath  = '';

        /**
         * 1. If an absolute path is provided: /var/www/… or /some/path/view.php
         */
        if ( strpos( $viewFilePath, DIRECTORY_SEPARATOR ) === 0 || strpos( $viewFilePath, '/' ) === 0 ) {
            $fullPath = $viewFilePath . $extension;

            /**
             * 2. Try to locate view inside the theme using locate_template()
             */
        } else {
            $located = locate_template( $viewFilePath . $extension, false, false );

            if ( ! empty( $located ) ) {
                $fullPath = $located;
            } else {
                /**
                 * 3. Fallback to basePath/{view}.php
                 */
                $candidate = rtrim( $this->basePath, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR . $viewFilePath . $extension;

                if ( file_exists( $candidate ) ) {
                    $fullPath = $candidate;
                }
            }
        }

        /**
         * If still not found → trigger warning and stop.
         */
        if ( $fullPath === '' || ! file_exists( $fullPath ) ) {
            $errorMessage = sprintf(
                __( 'View file not found: %s', 'yivic-lite' ),
                $viewFilePath . $extension
            );

            // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error
            trigger_error( $errorMessage, E_USER_WARNING );

            return null;
        }

        /**
         * Extract parameters into the local scope.
         * EXTR_SKIP avoids overwriting internal variables.
         */
        if ( ! empty( $params ) ) {
            // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
            extract( $params, EXTR_SKIP );
        }

        /**
         * Capture the view output buffer.
         */
        ob_start();
        /** @psalm-suppress UnresolvableInclude */
        include $fullPath;
        $content = ob_get_clean();

        /**
         * Return or echo the content depending on $echo.
         */
        if ( $echo ) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $content;
            return null;
        }

        return $content;
    }
}