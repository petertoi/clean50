<?php
/**
 * Filename settings.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

/**
 * Register the Copyright section settings
 */
add_action( 'customize_register', function ( $wp_customize ) {
    /**
     * @var WP_Customize_Manager $wp_customize
     */

    // Register a setting.
    $wp_customize->add_setting(
        '_toibox_copyright',
        array(
            'default'           => '',
            'sanitize_callback' => 'force_balance_tags',
        )
    );

    // Create the setting field.
    $wp_customize->add_control(
        '_toibox_copyright',
        array(
            'label'       => esc_html__( 'Copyright Statement', '' ),
            'description' => esc_html__( 'Copyright statement. Basic HTML tags are allowed.', '' ),
            'section'     => '_toibox_copyright_section',
            'type'        => 'text',
        )
    );
} );

/**
 * Register the Social section settings
 */
add_action( 'customize_register', function ( $wp_customize ) {
    /**
     * @var WP_Customize_Manager $wp_customize
     */

    $social_networks = [
        'facebook'  => [
            'label'       => esc_html_x( 'Facebook URL', 'Field label for Facebook profile URL', '' ),
            'description' => _x( 'Please paste full URL including <code>https://</code>', 'Instructions for pasting an URL', '' ),
        ],
        'instagram' => [
            'label'       => esc_html_x( 'Instagram URL', 'Field label for Instagram profile URL', '' ),
            'description' => _x( 'Please paste full URL including <code>https://</code>', 'Instructions for pasting an URL', '' ),
        ],
        'youtube'   => [
            'label'       => esc_html_x( 'YouTube URL', 'Field label for YouTube profile URL', '' ),
            'description' => _x( 'Please paste full URL including <code>https://</code>', 'Instructions for pasting an URL', '' ),
        ],
        'linkedin'  => [
            'label'       => esc_html_x( 'LinkedIn URL', 'Field label for LinkedIn profile URL', '' ),
            'description' => _x( 'Please paste full URL including <code>https://</code>', 'Instructions for pasting an URL', '' ),
        ],
    ];

    foreach ( $social_networks as $key => $social_network ) {
        // Register a setting.
        $wp_customize->add_setting(
            sprintf( '_toibox_%s_url', $key ),
            array(
                'default' => '',
            )
        );

        // Create the setting field.
        $wp_customize->add_control(
            sprintf( '_toibox_%s_url', $key ),
            array(
                'label'       => $social_network['label'],
                'description' => $social_network['description'],
                'section'     => '_toibox_social_profiles_section',
                'type'        => 'text',
            )
        );
    }

} );

/**
 * Register the Additional scripts settings
 */
add_action( 'customize_register', function ( $wp_customize ) {
    /**
     * @var WP_Customize_Manager $wp_customize
     */

    // Register a setting.
    $wp_customize->add_setting(
        '_toibox_header_scripts',
        array(
            'default'           => '',
            'sanitize_callback' => 'force_balance_tags',
        )
    );

    // Create the setting field.
    $wp_customize->add_control(
        '_toibox_header_scripts',
        array(
            'label'       => esc_html__( 'Header Scripts', '' ),
            'description' => esc_html__( 'Additional scripts to add to the header. Basic HTML tags are allowed.', '' ),
            'section'     => '_toibox_additional_scripts_section',
            'type'        => 'textarea',
        )
    );

    // Register a setting.
    $wp_customize->add_setting(
        '_toibox_body_open_scripts',
        array(
            'default'           => '',
            'sanitize_callback' => 'force_balance_tags',
        )
    );

    // Create the setting field.
    $wp_customize->add_control(
        '_toibox_body_open_scripts',
        array(
            'label'       => esc_html__( 'Body Open Scripts', '' ),
            'description' => esc_html__( 'Additional scripts to add just after the <body> open tag. Basic HTML tags are allowed.', '' ),
            'section'     => '_toibox_additional_scripts_section',
            'type'        => 'textarea',
        )
    );

    // Register a setting.
    $wp_customize->add_setting(
        '_toibox_footer_scripts',
        array(
            'default'           => '',
            'sanitize_callback' => 'force_balance_tags',
        )
    );

    // Create the setting field.
    $wp_customize->add_control(
        '_toibox_footer_scripts',
        array(
            'label'       => esc_html__( 'Footer Scripts', '' ),
            'description' => esc_html__( 'Additional scripts to add to the footer. Basic HTML tags are allowed.', '' ),
            'section'     => '_toibox_additional_scripts_section',
            'type'        => 'textarea',
        )
    );
} );
