<?php

namespace Toi\ToiBox\Setup;

use Toi\ToiBox\Assets;

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
        'primary' => __( 'Primary', '' ),
        'footer'  => __( 'Footer', '' ),
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

    /**
     * Image sizes
     *
     * @TODO Add codex link
     */

    // feature-lg
    // feature-md
    // feature-sm

    // square-lg
    // square-md
    // square-sm

    add_image_size( 'sponsor-carousel', 300, 60, false );
    add_image_size( 'block-articles-thumb', 285, 285, true );
    add_image_size( 'block-projects-feature', 570, 235, true );
    add_image_size( 'archive-honouree-thumb', 256, 256, true );
    add_image_size( 'archive-project-thumb', 570, 218, true );
    add_image_size( 'archive-post-thumb', 570, 218, true );

    add_image_size( 'single-honouree-thumb', 285, 285, true );
    add_image_size( 'single-project-feature', 1140, 392, true );
//    add_image_size( 'single-post-article', 285, 285, true );

    add_image_size( 'sprite-sm', 50, 50, true );
    add_image_size( 'sprite-md', 100, 100, true );
    add_image_size( 'sprite-lg', 150, 150, true );
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
                          'name' => __( 'Single: Honouree', '' ),
                          'id'   => 'sidebar-single-honouree'
                      ] + $config );
    register_sidebar( [
                          'name' => __( 'Single: Post', '' ),
                          'id'   => 'sidebar-single'
                      ] + $config );
} );

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
 * Critical CSS
 */
add_action( 'wp_head', function () {
    $classes  = get_body_class();
    $fallback = true;
    /**
     * Check for template specific Critical CSS
     */
    foreach ( $classes as $class ) {
        if ( file_exists( Assets\get_path( "css/critical/{$class}_critical.min.css" ) ) ) {
            printf(
                '<style>%s</style>',
                file_get_contents( Assets\get_path( "css/critical/{$class}_critical.min.css" ) )
            );
            $fallback = false;
            break;
        }
    }

    /**
     * Fallback: Load Home Critical CSS
     */
    if ( $fallback && file_exists( Assets\get_path( "css/critical/home_critical.min.css" ) ) ) {
        printf(
            '<style>%s</style>',
            file_get_contents( Assets\get_path( "css/critical/home_critical.min.css" ) )
        );
    }
}, 7 );

/**
 * Web Font Loader
 */
add_action( 'wp_head', function () {
    ?>
    <script>
		WebFontConfig = {
			google: { families: [ 'Poppins:700,600,500,400,400i' ] },
		};

		( function( d ) {
			var wf = d.createElement( 'script' ), s = d.scripts[ 0 ];
			wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js';
			wf.async = true;
			s.parentNode.insertBefore( wf, s );
		} )( document );
    </script>
    <?php
} );


/**
 * Admin Scripts
 */
add_action( 'admin_enqueue_scripts', function () {
    wp_enqueue_style( 'toibox/admin', Assets\get_url( 'css/admin.css' ), false, null );
    wp_enqueue_script( 'toibox/manifest', Assets\get_url( 'js/manifest.js' ), [], false, true );
    wp_enqueue_script( 'toibox/vendor', Assets\get_url( 'js/vendor.js' ), [], false, true );
} );

/**
 * ACF Admin Scripts
 */
add_action( 'acf/input/admin_enqueue_scripts', function () {
    wp_register_script( 'toibox/admin-acf', Assets\get_url( 'js/admin-acf.js' ), [ 'toibox/manifest', 'toibox/vendor' ], false, true );
    wp_localize_script( 'toibox/admin-acf', 'Clean50', [
        'honouree_types' => get_terms( [ 'taxonomy' => 'honouree-type', 'hide_empty' => false ] )
    ] );
    wp_enqueue_script( 'toibox/admin-acf' );

} );
