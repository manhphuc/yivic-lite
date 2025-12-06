<?php
/**
 * Main Customizer manager for Yivic Lite.
 *
 * Responsible for:
 * - Bootstrapping the Customizer layer.
 * - Loading existing theme mods.
 * - Instantiating individual section classes (General, Colors, etc.).
 */

namespace Yivic\YivicLite\Theme\Customizer;

use WP_Customize_Manager;

class CustomizerManager {

    /**
     * Singleton instance.
     *
     * @var CustomizerManager|null
     */
    protected static ?CustomizerManager $instance = null;

    /**
     * Cached theme mods array.
     *
     * @var array
     */
    protected array $theme_mods = [];

    /**
     * Private constructor to enforce singleton.
     */
    private function __construct() {}

    /**
     * Get singleton instance.
     *
     * @return CustomizerManager
     */
    public static function instance(): CustomizerManager {
        if ( null === static::$instance ) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Public entry point – call this once from theme bootstrap.
     *
     * This only attaches WordPress hooks; it does not perform heavy work yet.
     */
    public function init(): void {
        add_action(
            'customize_register',
            [ $this, 'on_customize_register' ]
        );
    }

    /**
     * customize_register callback.
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager instance.
     */
    public function on_customize_register( WP_Customize_Manager $wp_customize ): void {
        // Load all existing theme mods once.
        $this->theme_mods = get_theme_mods() ?: [];

        // Toggle which sections are enabled.
        $options = [
            'general' => true,
            // 'colors'  => true,
            // 'layout'  => false,
        ];

        if ( ! empty( $options['general'] ) ) {
            $this->register_general_section( $wp_customize );
        }

        // In the future, you can call other sections here:
        // if ( ! empty( $options['colors'] ) ) {
        //     $this->register_colors_section( $wp_customize );
        // }
    }

    /**
     * Register the "General" section in the Customizer.
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager instance.
     */
    protected function register_general_section( WP_Customize_Manager $wp_customize ): void {
        new GeneralSection( $wp_customize, $this->theme_mods );
    }
}