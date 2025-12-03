<?php

declare(strict_types=1);

namespace Yivic\YivicLite\Theme\WP;

use Exception;
use Yivic\YivicLite\Theme\Support\Container;
use Yivic\YivicLite\Theme\Traits\ConfigTrait;
use Yivic\YivicLite\Theme\Traits\WPAttributeTrait;
use Yivic\YivicLite\Theme\Services\ViewService;

/**
 * Core theme kernel for Yivic Lite.
 * Provides a lightweight dependency container, service
 * registration, configuration binding, and centralized
 * initialization similar to a small Laravel-style kernel.
 */
class YivicLite_WP_Theme extends Container {
    use ConfigTrait;
    use WPAttributeTrait;

    /**
     * Active singleton instance.
     *
     * @var static|null
     */
    protected static ?self $instance = null;

    /**
     * Theme version from config.
     */
    public ?string $version = null;

    /**
     * Absolute path to the theme directory.
     */
    public ?string $basePath = null;

    /**
     * URL to the theme directory.
     */
    public ?string $baseUrl = null;

    /**
     * Constructor with configuration.
     *
     * @param array $config
     * @throws Exception
     */
    public function __construct( array $config ) {
        $this->bindConfig($config);

        if ( ! empty( $config['services'] ?? [] ) ) {
            $this->registerServices( $config['services'] );
        }
    }

    /**
     * Initialize the singleton instance with configuration.
     *
     * @param array $config
     * @throws Exception
     */
    public static function initInstanceWithConfig( array $config ): void {
        if ( static::$instance === null ) {
            static::$instance = new static( $config );
        }

        if ( ! static::$instance instanceof static ) {
            throw new Exception( 'Theme container was not initialized correctly.' );
        }
    }

    /**
     * Retrieve the active singleton theme instance.
     *
     * @return static
     */
    public static function getInstance(): self {
        if ( ! static::$instance ) {
            throw new Exception( 'Theme has not been initialized. Call initInstanceWithConfig() first.' );
        }

        return static::$instance;
    }

    /**
     * Register theme service bindings.
     *
     * @param array $services
     */
    protected function registerServices( array $services ): void {
        foreach ( $services as $className => $serviceConfig ) {

            $this->singleton(
                $className,
                function ( Container $container ) use ( $className, $serviceConfig ) {

                    $instance = new $className();

                    if ( method_exists( $instance, 'bindConfig' ) ) {
                        $instance->bindConfig( $serviceConfig );
                    }

                    // Auto-inject container + run init()
                    if ( in_array( \Yivic\YivicLite\Theme\Traits\ServiceTrait::class, class_uses( $instance ), true ) ) {
                        $instance->setContainer( $container );
                        $instance->init();
                    }

                    return $instance;
                }
            );
        }
    }

    /**
     * Retrieve any registered service.
     *
     * @param string $alias
     * @return mixed
     */
    public function getService( string $alias ) {
        return $this->make( $alias );
    }

    /**
     * Shortcut to get the view renderer.
     *
     * @return ViewService
     */
    public static function view(): ViewService {
        return static::getInstance()->getService( ViewService::class );
    }

    /**
     * Main initialization for the theme.
     * This is where theme-level hooks and setup run.
     */
    public function initTheme(): void {
        add_action( 'after_setup_theme', [ $this, 'setupTheme' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueueAssets' ] );
    }

    /**
     * Setup general theme features.
     */
    public function setupTheme(): void {
        load_theme_textdomain(
            $this->textDomain,
            $this->basePath . '/languages'
        );

        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'custom-logo' );
        add_theme_support( 'automatic-feed-links' );
    }

    /**
     * Register & enqueue theme assets.
     */
    public function enqueueAssets(): void {
        wp_enqueue_style(
            'yivic-lite-style',
            $this->baseUrl . '/style.css',
            [],
            $this->version ?? null
        );
    }
}