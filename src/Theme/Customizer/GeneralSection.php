<?php
/**
 * General Settings section in Customizer.
 *
 * - Registers the panel / section.
 * - Adds settings + controls (copyright, sidebar, colors, archive/single toggles).
 */

namespace Yivic\YivicLite\Theme\Customizer;

use WP_Customize_Manager;

class GeneralSection {

    /**
     * WP_Customize_Manager instance.
     *
     * @var WP_Customize_Manager
     */
    protected WP_Customize_Manager $customizer;

    /**
     * Cached theme mods.
     *
     * @var array
     */
    protected array $theme_mods = [];

    /**
     * Constructor.
     *
     * @param WP_Customize_Manager $customizer Customizer manager instance.
     * @param array                $theme_mods Current theme mods.
     */
    public function __construct( WP_Customize_Manager $customizer, array $theme_mods = [] ) {
        $this->customizer = $customizer;
        $this->theme_mods = $theme_mods;

        $this->register_panel();
        $this->register_section();
        $this->register_settings_and_controls();
    }

    /**
     * Register main panel for Yivic Lite general settings.
     */
    protected function register_panel(): void {
        $this->customizer->add_panel(
            'yivic_lite_general_panel',
            [
                'title'       => __( 'Yivic Lite – General Settings', 'yivic-lite' ),
                'description' => __( 'Global layout and basic appearance options for Yivic Lite.', 'yivic-lite' ),
                'priority'    => 160,
            ]
        );
    }

    /**
     * Register the "General" section inside the panel.
     */
    protected function register_section(): void {
        $this->customizer->add_section(
            'yivic_lite_general_section',
            [
                'title'    => __( 'General', 'yivic-lite' ),
                'panel'    => 'yivic_lite_general_panel',
                'priority' => 10,
            ]
        );
    }

    /**
     * Register settings + controls.
     */
    protected function register_settings_and_controls(): void {

        /**
         * 1. Footer copyright text.
         */
        $this->customizer->add_setting(
            'yivic_lite_footer_copyright',
            [
                'default'           => __( '© 2025 Yivic Lite. Powered by WordPress & Yivic Lite.', 'yivic-lite' ),
                'sanitize_callback' => [ $this, 'sanitize_textarea' ],
                'transport'         => 'refresh',
            ]
        );

        $this->customizer->add_control(
            'yivic_lite_footer_copyright',
            [
                'label'       => __( 'Footer copyright text', 'yivic-lite' ),
                'description' => __( 'You can use plain text or basic HTML (like links).', 'yivic-lite' ),
                'section'     => 'yivic_lite_general_section',
                'type'        => 'textarea',
            ]
        );

        /**
         * 2. Header / menu background color.
         */
        $this->customizer->add_setting(
            'yivic_lite_header_bg_color',
            [
                'default'           => '#313b45',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'refresh',
            ]
        );

        $this->customizer->add_control(
            new \WP_Customize_Color_Control(
                $this->customizer,
                'yivic_lite_header_bg_color',
                [
                    'label'    => __( 'Header & menu background color', 'yivic-lite' ),
                    'section'  => 'yivic_lite_general_section',
                    'settings' => 'yivic_lite_header_bg_color',
                ]
            )
        );

        /**
         * 3. Sidebar visibility toggle (blog + pages).
         */
        $this->customizer->add_setting(
            'yivic_lite_show_sidebar',
            [
                'default'           => true,
                'sanitize_callback' => [ $this, 'sanitize_checkbox' ],
                'transport'         => 'refresh',
            ]
        );

        $this->customizer->add_control(
            'yivic_lite_show_sidebar',
            [
                'label'   => __( 'Display sidebar on blog and pages', 'yivic-lite' ),
                'section' => 'yivic_lite_general_section',
                'type'    => 'checkbox',
            ]
        );

        /**
         * 4. Archive: show post excerpt.
         */
        $this->customizer->add_setting(
            'yivic_lite_show_excerpt',
            [
                'default'           => true,
                'sanitize_callback' => [ $this, 'sanitize_checkbox' ],
                'transport'         => 'refresh',
            ]
        );

        $this->customizer->add_control(
            'yivic_lite_show_excerpt',
            [
                'label'       => __( 'Show post excerpt in archive', 'yivic-lite' ),
                'description' => __( 'Display the summary text below post titles on blog/archive pages.', 'yivic-lite' ),
                'section'     => 'yivic_lite_general_section',
                'type'        => 'checkbox',
            ]
        );

        /**
         * 5. Archive: show author avatar.
         */
        $this->customizer->add_setting(
            'yivic_lite_show_archive_author_avatar',
            [
                'default'           => true,
                'sanitize_callback' => [ $this, 'sanitize_checkbox' ],
                'transport'         => 'refresh',
            ]
        );

        $this->customizer->add_control(
            'yivic_lite_show_archive_author_avatar',
            [
                'label'       => __( 'Show author avatar in archive', 'yivic-lite' ),
                'description' => __( 'Disable this to hide the small circular author image in post lists.', 'yivic-lite' ),
                'section'     => 'yivic_lite_general_section',
                'type'        => 'checkbox',
            ]
        );

        /**
         * 6. Archive: show category badge.
         */
        $this->customizer->add_setting(
            'yivic_lite_show_archive_category',
            [
                'default'           => true,
                'sanitize_callback' => [ $this, 'sanitize_checkbox' ],
                'transport'         => 'refresh',
            ]
        );

        $this->customizer->add_control(
            'yivic_lite_show_archive_category',
            [
                'label'       => __( 'Show category badge in archive', 'yivic-lite' ),
                'description' => __( 'Disable this to hide the purple category label on post cards.', 'yivic-lite' ),
                'section'     => 'yivic_lite_general_section',
                'type'        => 'checkbox',
            ]
        );

        /**
         * 7. Archive: show date.
         */
        $this->customizer->add_setting(
            'yivic_lite_show_archive_date',
            [
                'default'           => true,
                'sanitize_callback' => [ $this, 'sanitize_checkbox' ],
                'transport'         => 'refresh',
            ]
        );

        $this->customizer->add_control(
            'yivic_lite_show_archive_date',
            [
                'label'       => __( 'Show date in archive', 'yivic-lite' ),
                'description' => __( 'Disable this to hide the date label on post cards.', 'yivic-lite' ),
                'section'     => 'yivic_lite_general_section',
                'type'        => 'checkbox',
            ]
        );

        /**
         * 8. Archive: show featured image.
         */
        $this->customizer->add_setting(
            'yivic_lite_show_thumbnail_archive',
            [
                'default'           => true,
                'sanitize_callback' => [ $this, 'sanitize_checkbox' ],
                'transport'         => 'refresh',
            ]
        );

        $this->customizer->add_control(
            'yivic_lite_show_thumbnail_archive',
            [
                'label'       => __( 'Show featured image on archive pages', 'yivic-lite' ),
                'description' => __( 'Disable to remove the thumbnail image in blog lists.', 'yivic-lite' ),
                'section'     => 'yivic_lite_general_section',
                'type'        => 'checkbox',
            ]
        );

        /**
         * 9. Single: show featured image.
         */
        $this->customizer->add_setting(
            'yivic_lite_show_thumbnail_single',
            [
                'default'           => true,
                'sanitize_callback' => [ $this, 'sanitize_checkbox' ],
                'transport'         => 'refresh',
            ]
        );

        $this->customizer->add_control(
            'yivic_lite_show_thumbnail_single',
            [
                'label'       => __( 'Show featured image on single post', 'yivic-lite' ),
                'description' => __( 'Disable this to hide the large top image inside single posts.', 'yivic-lite' ),
                'section'     => 'yivic_lite_general_section',
                'type'        => 'checkbox',
            ]
        );

        /**
         * 10. Single: show date.
         */
        $this->customizer->add_setting(
            'yivic_lite_single_show_date',
            [
                'default'           => true,
                'sanitize_callback' => [ $this, 'sanitize_checkbox' ],
                'transport'         => 'refresh',
            ]
        );

        $this->customizer->add_control(
            'yivic_lite_single_show_date',
            [
                'label'       => __( 'Show date on single post', 'yivic-lite' ),
                'description' => __( 'Disable this to hide the date below the single post title.', 'yivic-lite' ),
                'section'     => 'yivic_lite_general_section',
                'type'        => 'checkbox',
            ]
        );

        /**
         * 11. Single: show author name.
         */
        $this->customizer->add_setting(
            'yivic_lite_single_show_author',
            [
                'default'           => true,
                'sanitize_callback' => [ $this, 'sanitize_checkbox' ],
                'transport'         => 'refresh',
            ]
        );

        $this->customizer->add_control(
            'yivic_lite_single_show_author',
            [
                'label'       => __( 'Show author name on single post', 'yivic-lite' ),
                'description' => __( 'Disable this to hide the author name below the single post title.', 'yivic-lite' ),
                'section'     => 'yivic_lite_general_section',
                'type'        => 'checkbox',
            ]
        );

        /**
         * 12. Toggle: Show breadcrumb trail.
         */
        $this->customizer->add_setting(
            'yivic_lite_show_breadcrumb',
            [
                'default'           => true,
                'sanitize_callback' => [ $this, 'sanitize_checkbox' ],
                'transport'         => 'refresh',
            ]
        );

        $this->customizer->add_control(
            'yivic_lite_show_breadcrumb',
            [
                'label'       => __( 'Show breadcrumb navigation', 'yivic-lite' ),
                'description' => __( 'Disable this to hide the breadcrumb trail above content.', 'yivic-lite' ),
                'section'     => 'yivic_lite_general_section',
                'type'        => 'checkbox',
            ]
        );
    }

    // ---------------------------------------------------------------------
    // Sanitizers
    // ---------------------------------------------------------------------

    /**
     * Sanitize textarea input (allow basic HTML using wp_kses_post).
     *
     * @param string $value Raw value from Customizer.
     * @return string
     */
    public function sanitize_textarea( string $value ): string {
        return wp_kses_post( $value );
    }

    /**
     * Sanitize checkbox (true/false).
     *
     * @param mixed $value Raw value from Customizer.
     * @return bool
     */
    public function sanitize_checkbox( $value ): bool {
        return (bool) $value;
    }
}