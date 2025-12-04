<?php

declare(strict_types=1);

namespace Yivic\YivicLite\Theme\WP;

use Exception;
use Yivic\YivicLite\Theme\Support\Container;
use Yivic\YivicLite\Theme\Traits\ConfigTrait;
use Yivic\YivicLite\Theme\Traits\WPAttributeTrait;
use Yivic\YivicLite\Theme\Services\ViewService;
use Yivic\YivicLite\Theme\Interfaces\WPThemeInterface;

/**
 * Core theme kernel for Yivic Lite.
 * Provides a lightweight dependency container, service
 * registration, configuration binding, and centralized
 * initialization similar to a small Laravel-style kernel.
 */
class YivicLite_WP_Theme extends Container implements WPThemeInterface {
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
        add_action( 'widgets_init', [ $this, 'registerSidebars' ] );
        add_action( 'init', [ $this, 'registerBlockFeatures' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueueAssets' ] );
    }

    /**
     * Setup general theme features.
     */
    public function setupTheme(): void {
        load_theme_textdomain(
            'yivic-lite',
            $this->basePath . '/languages'
        );

        // Core supports.
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'custom-logo' );
        add_theme_support( 'automatic-feed-links' );

        // Recommended block & HTML5 supports for modern themes.
        add_theme_support(
            'html5',
            [
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
                'navigation-widgets',
            ]
        );

        add_theme_support( 'wp-block-styles' );
        add_theme_support( 'responsive-embeds' );
        add_theme_support( 'align-wide' );

        // Optional header/background (recommended by Theme Check).
        add_theme_support(
            'custom-header',
            [
                'width'       => 1600,
                'height'      => 400,
                'flex-width'  => true,
                'flex-height' => true,
                'header-text' => false,
            ]
        );

        add_theme_support(
            'custom-background',
            [
                'default-color' => 'ffffff',
            ]
        );

        add_editor_style( 'public-assets/dist/css/admin.css' );

        // Register navigation menu locations so the theme
        // is fully compatible with the Menus screen.
        register_nav_menus(
            [
                'primary' => __( 'Primary Menu', 'yivic-lite' ),
                'footer'  => __( 'Footer Menu', 'yivic-lite' ),
            ]
        );
    }

    /**
     * Register widget areas.
     */
    public function registerSidebars(): void {
        register_sidebar(
            [
                'name'          => __( 'Primary Sidebar', 'yivic-lite' ),
                'id'            => 'sidebar-1',
                'description'   => __( 'Main sidebar area.', 'yivic-lite' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ]
        );
    }

    public function registerBlockFeatures(): void {
        // Do nothing on very old WordPress where block APIs do not exist.
        if ( ! function_exists( 'register_block_style' ) ) {
            return;
        }

        // Custom style cho core/image.
        register_block_style(
            'core/image',
            [
                'name'  => 'yivic-lite-frame',
                'label' => __( 'Framed image', 'yivic-lite' ),
            ]
        );

        if ( function_exists( 'register_block_pattern_category' ) ) {
            register_block_pattern_category(
                'yivic-lite',
                [
                    'label' => __( 'Yivic Lite', 'yivic-lite' ),
                ]
            );
        }

        if ( function_exists( 'register_block_pattern' ) ) {
            register_block_pattern(
                'yivic-lite/hero-intro',
                [
                    'title'       => __( 'Simple hero header', 'yivic-lite' ),
                    'description' => __( 'A centered hero heading with intro text.', 'yivic-lite' ),
                    'categories'  => [ 'yivic-lite' ],
                    'content'     => '<!-- wp:group {"align":"full","layout":{"type":"constrained"}} -->
                                        <div class="wp-block-group alignfull">
                                            <!-- wp:heading {"textAlign":"center","level":1} -->
                                            <h1 class="has-text-align-center">Yivic Lite</h1>
                                            <!-- /wp:heading -->
                                            <!-- wp:paragraph {"align":"center"} -->
                                            <p class="has-text-align-center">A lightweight theme focused on clean typography.</p>
                                            <!-- /wp:paragraph -->
                                        </div>
                                        <!-- /wp:group -->'
                    ,
                ]
            );
        }
    }


    /**
     * Register & enqueue theme assets.
     */
    public function enqueueAssets(): void {
        wp_enqueue_style(
            'yivic-lite-style',
            $this->baseUrl . '/public-assets/dist/css/main.css',
            [],
            $this->version ?? null
        );

        wp_enqueue_script(
            'yivic-lite-script',
            $this->baseUrl . '/public-assets/dist/js/main.js',
            [],
            $this->version ?? null,
            true
        );

        // Recommended: threaded comments support.
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }

    }
}