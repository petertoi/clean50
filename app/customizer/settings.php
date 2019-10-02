<?php
/**
 * Filename settings.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

/**
 * Register the Honouree section settings
 */
add_action( 'customize_register', function ( $wp_customize ) {
    /**
     * @var WP_Customize_Manager $wp_customize
     */

    // Register a setting.
    $wp_customize->add_setting(
        '_toibox_honouree_archive_title',
        [
            'default'           => '',
            'sanitize_callback' => 'force_balance_tags',
        ]
    );

    // Create the setting field.
    $wp_customize->add_control(
        '_toibox_honouree_archive_title',
        [
            'label'       => esc_html__( 'Title', '' ),
            'description' => esc_html__( 'Title.', '' ),
            'section'     => '_toibox_honouree_section',
            'type'        => 'text',
        ]
    );

    // Register a setting.
    $wp_customize->add_setting(
        '_toibox_honouree_archive_content',
        [
            'default'           => '',
            'sanitize_callback' => 'force_balance_tags',
        ]
    );

    // Create the setting field.
    $wp_customize->add_control(
        '_toibox_honouree_archive_content',
        [
            'label'       => esc_html__( 'Intro Content', '' ),
            'description' => esc_html__( 'Intro Content.', '' ),
            'section'     => '_toibox_honouree_section',
            'type'        => 'textarea',
        ]
    );

    // Register a setting.
    $wp_customize->add_setting(
        '_toibox_honouree_archive_per_page',
        [
            'default'           => 16,
            'sanitize_callback' => 'absint',
        ]
    );

    // Create the setting field.
    $wp_customize->add_control(
        '_toibox_honouree_archive_per_page',
        [
            'label'       => esc_html__( 'Per Page', '' ),
            'description' => esc_html__( 'Number of Honourees to display on each page of the archive.', '' ),
            'section'     => '_toibox_honouree_section',
            'type'        => 'number',
            'input_attrs' => [
                'min'  => 4,
                'max'  => 40,
                'step' => 4,
            ]
        ]
    );

    // Register a setting.
    $wp_customize->add_setting(
        '_toibox_honouree_fallback_image',
        []
    );
    // Create the setting field.
    $wp_customize->add_control(

        new WP_Customize_Media_Control(
            $wp_customize,
            '_toibox_honouree_fallback_image',
            [
                'label'     => __( 'Fallback Image', '' ),
                'section'   => '_toibox_honouree_section',
                'settings'  => '_toibox_honouree_fallback_image',
                'mime_type' => 'image',
            ]
        )
    );
} );

/**
 * Register the Project section settings
 */
add_action( 'customize_register', function ( $wp_customize ) {
    /**
     * @var WP_Customize_Manager $wp_customize
     */

    // Register a setting.
    $wp_customize->add_setting(
        '_toibox_project_archive_title',
        [
            'default'           => '',
            'sanitize_callback' => 'force_balance_tags',
        ]
    );

    // Create the setting field.
    $wp_customize->add_control(
        '_toibox_project_archive_title',
        [
            'label'       => esc_html__( 'Title', '' ),
            'description' => esc_html__( 'Title.', '' ),
            'section'     => '_toibox_project_section',
            'type'        => 'text',
        ]
    );

    // Register a setting.
    $wp_customize->add_setting(
        '_toibox_project_archive_content',
        [
            'default'           => '',
            'sanitize_callback' => 'force_balance_tags',
        ]
    );

    // Create the setting field.
    $wp_customize->add_control(
        '_toibox_project_archive_content',
        [
            'label'       => esc_html__( 'Intro Content', '' ),
            'description' => esc_html__( 'Intro Content.', '' ),
            'section'     => '_toibox_project_section',
            'type'        => 'textarea',
        ]
    );


    // Register a setting.
    $wp_customize->add_setting(
        '_toibox_project_archive_per_page',
        [
            'default'           => 8,
            'sanitize_callback' => 'absint',
        ]
    );

    // Create the setting field.
    $wp_customize->add_control(
        '_toibox_project_archive_per_page',
        [
            'label'       => esc_html__( 'Per Page', '' ),
            'description' => esc_html__( 'Number of Projects to display on each page of the archive.', '' ),
            'section'     => '_toibox_project_section',
            'type'        => 'number',
            'input_attrs' => [
                'min'  => 2,
                'max'  => 20,
                'step' => 2,
            ]
        ]
    );

    // Register a setting.
    $wp_customize->add_setting(
        '_toibox_project_fallback_image',
        []
    );

    // Create the setting field.
    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            '_toibox_project_fallback_image',
            [
                'label'     => __( 'Fallback Project Image', '' ),
                'section'   => '_toibox_project_section',
                'settings'  => '_toibox_project_fallback_image',
                'mime_type' => 'image',
            ]
        )
    );

    // Register a setting.
    $wp_customize->add_setting(
        '_toibox_project_sponsor',
        [
            'sanitize_callback' => 'absint',
        ]
    );

    $sponsors = get_posts( [
        'post_type'      => 'sponsor',
        'posts_per_page' => 100,
        'orderby'        => [ 'post_title', 'ASC' ],
    ] );

    $choices = [];
    foreach ( $sponsors as $sponsor ) {
        $choices[ $sponsor->ID ] = $sponsor->post_title;
    }

    // Create the setting field.
    $wp_customize->add_control(
        '_toibox_project_sponsor',
        [
            'label'       => esc_html__( 'Project Sponsor', '' ),
            'description' => esc_html__( 'Project Sponsor.', '' ),
            'section'     => '_toibox_project_section',
            'type'        => 'select',
            'choices'     => $choices,
        ]
    );
} );

/**
 * Register the Article (Post) section settings
 */
add_action( 'customize_register', function ( $wp_customize ) {
    /**
     * @var WP_Customize_Manager $wp_customize
     */

    // Register a setting.
    $wp_customize->add_setting(
        '_toibox_article_archive_title',
        [
            'default'           => '',
            'sanitize_callback' => 'force_balance_tags',
        ]
    );

    // Create the setting field.
    $wp_customize->add_control(
        '_toibox_article_archive_title',
        [
            'label'       => esc_html__( 'Title', '' ),
            'description' => esc_html__( 'Title.', '' ),
            'section'     => '_toibox_article_section',
            'type'        => 'text',
        ]
    );

    // Register a setting.
    $wp_customize->add_setting(
        '_toibox_article_archive_content',
        [
            'default'           => '',
            'sanitize_callback' => 'force_balance_tags',
        ]
    );

    // Create the setting field.
    $wp_customize->add_control(
        '_toibox_article_archive_content',
        [
            'label'       => esc_html__( 'Intro Content', '' ),
            'description' => esc_html__( 'Intro Content.', '' ),
            'section'     => '_toibox_article_section',
            'type'        => 'textarea',
        ]
    );

    // Register a setting.
    $wp_customize->add_setting(
        '_toibox_article_fallback_image',
        []
    );

    // Create the setting field.
    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            '_toibox_article_fallback_image',
            [
                'label'     => __( 'Fallback Article Image', '' ),
                'section'   => '_toibox_article_section',
                'settings'  => '_toibox_article_fallback_image',
                'mime_type' => 'image',
            ]
        )
    );

} );

/**
 * Register the Footer section settings
 */
add_action( 'customize_register', function ( $wp_customize ) {
    /**
     * @var WP_Customize_Manager $wp_customize
     */

    // Register a setting.
    $wp_customize->add_setting(
        '_toibox_footer_sponsor_intro',
        [
            'default'           => '',
            'sanitize_callback' => 'force_balance_tags',
        ]
    );

    // Create the setting field.
    $wp_customize->add_control(
        '_toibox_footer_sponsor_intro',
        [
            'label'   => esc_html__( 'Sponsor intro content.', '' ),
            'section' => '_toibox_footer_section',
            'type'    => 'text',
        ]
    );

    // Register a setting.
    $wp_customize->add_setting(
        '_toibox_copyright',
        [
            'default'           => '',
            'sanitize_callback' => 'force_balance_tags',
        ]
    );

    // Create the setting field.
    $wp_customize->add_control(
        '_toibox_copyright',
        [
            'label'       => esc_html__( 'Copyright Statement', '' ),
            'description' => esc_html__( 'Basic HTML tags are allowed.', '' ),
            'section'     => '_toibox_footer_section',
            'type'        => 'text',
        ]
    );
} );

/**
 * Register the Social Profile section settings
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
            [
                'default' => '',
            ]
        );

        // Create the setting field.
        $wp_customize->add_control(
            sprintf( '_toibox_%s_url', $key ),
            [
                'label'       => $social_network['label'],
                'description' => $social_network['description'],
                'section'     => '_toibox_social_profiles_section',
                'type'        => 'text',
            ]
        );
    }

} );

/**
 * Register the Twitter API section settings
 */
add_action( 'customize_register', function ( $wp_customize ) {
    /**
     * @var WP_Customize_Manager $wp_customize
     */

    // Register a setting.
    $wp_customize->add_setting(
        '_toibox_twitter_api_consumer_key',
        [
            'default' => '',
        ]
    );

    // Create the setting field.
    $wp_customize->add_control(
        '_toibox_twitter_api_consumer_key',
        [
            'label'       => esc_html__( 'Twitter API Consumer Key', '' ),
            'description' => esc_html__( 'Twitter API Consumer Key', '' ),
            'section'     => '_toibox_twitter_api_section',
            'type'        => 'text',
        ]
    );

    // Register a setting.
    $wp_customize->add_setting(
        '_toibox_twitter_api_consumer_secret',
        [
            'default' => '',
        ]
    );

    // Create the setting field.
    $wp_customize->add_control(
        '_toibox_twitter_api_consumer_secret',
        [
            'label'       => esc_html__( 'Twitter API Consumer Secret', '' ),
            'description' => esc_html__( 'Twitter API Consumer Secret', '' ),
            'section'     => '_toibox_twitter_api_section',
            'type'        => 'text',
        ]
    );
    // Register a setting.
    $wp_customize->add_setting(
        '_toibox_twitter_api_screen_name',
        [
            'default' => '',
        ]
    );

    // Create the setting field.
    $wp_customize->add_control(
        '_toibox_twitter_api_screen_name',
        [
            'label'       => esc_html__( 'Twitter API Screen Name', '' ),
            'description' => esc_html__( 'Twitter API Screen Name', '' ),
            'section'     => '_toibox_twitter_api_section',
            'type'        => 'text',
        ]
    );

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
        [
            'default'           => '',
            'sanitize_callback' => 'force_balance_tags',
        ]
    );

    // Create the setting field.
    $wp_customize->add_control(
        '_toibox_header_scripts',
        [
            'label'       => esc_html__( 'Header Scripts', '' ),
            'description' => esc_html__( 'Additional scripts to add to the header. Basic HTML tags are allowed.', '' ),
            'section'     => '_toibox_additional_scripts_section',
            'type'        => 'textarea',
        ]
    );

    // Register a setting.
    $wp_customize->add_setting(
        '_toibox_body_open_scripts',
        [
            'default'           => '',
            'sanitize_callback' => 'force_balance_tags',
        ]
    );

    // Create the setting field.
    $wp_customize->add_control(
        '_toibox_body_open_scripts',
        [
            'label'       => esc_html__( 'Body Open Scripts', '' ),
            'description' => esc_html__( 'Additional scripts to add just after the <body> open tag. Basic HTML tags are allowed.', '' ),
            'section'     => '_toibox_additional_scripts_section',
            'type'        => 'textarea',
        ]
    );

    // Register a setting.
    $wp_customize->add_setting(
        '_toibox_footer_scripts',
        [
            'default'           => '',
            'sanitize_callback' => 'force_balance_tags',
        ]
    );

    // Create the setting field.
    $wp_customize->add_control(
        '_toibox_footer_scripts',
        [
            'label'       => esc_html__( 'Footer Scripts', '' ),
            'description' => esc_html__( 'Additional scripts to add to the footer. Basic HTML tags are allowed.', '' ),
            'section'     => '_toibox_additional_scripts_section',
            'type'        => 'textarea',
        ]
    );
} );
