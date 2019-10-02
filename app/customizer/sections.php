<?php
/**
 * Filename sections.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

/**
 * Register the Archive: Honouree section
 */
add_action( 'customize_register', function ( $wp_customize ) {
    /**
     * @var WP_Customize_Manager $wp_customize
     */

    $wp_customize->add_section(
        '_toibox_honouree_section',
        array(
            'title'    => esc_html__( 'Honourees', '' ),
            'priority' => 10,
            'panel'    => 'site-options',
        )
    );
} );

/**
 * Register the Project section
 */
add_action( 'customize_register', function ( $wp_customize ) {
    /**
     * @var WP_Customize_Manager $wp_customize
     */

    $wp_customize->add_section(
        '_toibox_project_section',
        array(
            'title'    => esc_html__( 'Projects', '' ),
            'priority' => 10,
            'panel'    => 'site-options',
        )
    );
} );

/**
 * Register the Articles section
 */
add_action( 'customize_register', function ( $wp_customize ) {
    /**
     * @var WP_Customize_Manager $wp_customize
     */

    $wp_customize->add_section(
        '_toibox_article_section',
        array(
            'title'    => esc_html__( 'Articles', '' ),
            'priority' => 10,
            'panel'    => 'site-options',
        )
    );
} );

/**
 * Register the Sponsor Footer section
 */
add_action( 'customize_register', function ( $wp_customize ) {
    /**
     * @var WP_Customize_Manager $wp_customize
     */

    $wp_customize->add_section(
        '_toibox_footer_section',
        array(
            'title'    => esc_html__( 'Footer', '' ),
            'priority' => 10,
            'panel'    => 'site-options',
        )
    );
} );

/**
 * Register the Social Profiles section
 */
add_action( 'customize_register', function ( $wp_customize ) {
    /**
     * @var WP_Customize_Manager $wp_customize
     */

    $wp_customize->add_section(
        '_toibox_social_profiles_section',
        array(
            'title'    => esc_html__( 'Social Profiles', '' ),
            'priority' => 10,
            'panel'    => 'site-options',
        )
    );

} );

/**
 * Register the Twitter API section
 */
add_action( 'customize_register', function ( $wp_customize ) {
    /**
     * @var WP_Customize_Manager $wp_customize
     */

    $wp_customize->add_section(
        '_toibox_twitter_api_section',
        array(
            'title'    => esc_html__( 'Twitter API', '' ),
            'priority' => 10,
            'panel'    => 'site-options',
        )
    );

} );

/**
 * Register the Additional Scripts section
 */
add_action( 'customize_register', function ( $wp_customize ) {
    /**
     * @var WP_Customize_Manager $wp_customize
     */

    $wp_customize->add_section(
        '_toibox_additional_scripts_section',
        array(
            'title'    => esc_html__( 'Additional Scripts', '' ),
            'priority' => 10,
            'panel'    => 'site-options',
        )
    );

} );
