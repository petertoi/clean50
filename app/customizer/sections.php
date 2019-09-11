<?php
/**
 * Filename sections.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

/**
 * Register the Copyright section
 */
add_action( 'customize_register', function ( $wp_customize ) {
    /**
     * @var WP_Customize_Manager $wp_customize
     */

    $wp_customize->add_section(
        '_toibox_copyright_section',
        array(
            'title'    => esc_html__( 'Copyright', '' ),
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
