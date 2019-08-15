<?php

namespace Toi\ToiBox\Setup;

use Toi\ToiBox\Assets;

/**
 * Theme assets
 */
add_action( 'wp_enqueue_scripts', function () {
    // Enqueue Styles
    wp_enqueue_style( 'toibox/main', Assets\get_url( 'css/main.css' ), false, null );

    // Enqueue Scripts
    wp_register_script( 'toibox/vendor', Assets\get_url( 'js/vendor.js' ), [ 'jquery' ], null, true );
    wp_add_inline_script( 'toibox/vendor', file_get_contents( Assets\get_path( 'js/manifest.js' ) ), 'before' );
    wp_enqueue_script( 'toibox/main', Assets\get_url( 'js/main.js' ), [ 'jquery', 'toibox/vendor' ], null, true );

    if ( is_single() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}, 100 );

/**
 * Theme setup
 */
add_action( 'after_setup_theme', function () {
    /**
     * Enable features from Roots Soil when plugin is activated
     */
    add_theme_support( 'soil-clean-up' );
    add_theme_support( 'soil-disable-asset-versioning' );
//    add_theme_support( 'soil-disable-rest-api' );
    add_theme_support( 'soil-disable-trackbacks' );
//    add_theme_support( 'soil-google-analytics' );
    add_theme_support( 'soil-js-to-footer' );
//    add_theme_support( 'soil-nav-walker' );
    add_theme_support( 'soil-nice-search' );
    add_theme_support( 'soil-relative-urls' );

    /**
     * Enable plugins to manage the document title
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support( 'title-tag' );

    /**
     * Register navigation menus
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus( [
        'primary' => __( 'Primary Navigation', '' )
    ] );

    /**
     * Enable post thumbnails
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support( 'post-thumbnails' );

    /**
     * Enable HTML5 markup support
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support( 'html5', [ 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ] );

    /**
     * Enable selective refresh for widgets in customizer
     *
     * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#theme-support-in-sidebars
     */
    add_theme_support( 'customize-selective-refresh-widgets' );

    /**
     * Use main stylesheet for visual editor
     *
     * @see resources/assets/styles/layouts/_tinymce.scss
     */
    add_editor_style( Assets\get_url( 'css/main.css' ) );
}, 20 );

/**
 * Register sidebars
 */
add_action( 'widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ];
    register_sidebar( [
                          'name' => __( 'Primary', '' ),
                          'id'   => 'sidebar-primary'
                      ] + $config );
    register_sidebar( [
                          'name' => __( 'Footer', '' ),
                          'id'   => 'sidebar-footer'
                      ] + $config );
} );

