<?php
/**
 * Filename clean-up.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

namespace Toi\ToiBox\Clean_Up;

/**
 * Clean up wp_head()
 *
 * Remove unnecessary <link>'s
 * Remove inline CSS and JS from WP emoji support
 * Remove inline CSS used by Recent Comments widget
 * Remove inline CSS used by posts with galleries
 * Remove self-closing tag
 *
 * You can enable/disable this feature in functions.php (or app/setup.php if you're using Sage):
 * add_theme_support('soil-clean-up');
 */

add_action( 'init', function () {
    // Originally from http://wpengineer.com/1438/wordpress-header/
    remove_action( 'wp_head', 'feed_links_extra', 3 );
    add_action( 'wp_head', 'ob_start', 1, 0 );
    add_action( 'wp_head', function () {
        $pattern = '/.*' . preg_quote( esc_url( get_feed_link( 'comments_' . get_default_feed() ) ), '/' ) . '.*[\r\n]+/';
        echo preg_replace( $pattern, '', ob_get_clean() );
    }, 3, 0 );
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'use_default_gallery_style', '__return_false' );
    add_filter( 'emoji_svg_url', '__return_false' );
    add_filter( 'show_recent_comments_widget_style', '__return_false' );
} );

/**
 * Remove the WordPress version from RSS feeds
 */
add_filter( 'the_generator', '__return_false' );

/**
 * Clean up output of stylesheet <link> tags
 *
 * - Remove unnecessary id='*'
 * - Remove redundant `type='text/css'`
 * - Remove redundant `media='all'`
 */
add_filter( 'style_loader_tag', function ( $input ) {
    preg_match_all( "!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches );
    if ( empty( $matches[2] ) ) {
        return $input;
    }
    // Only display media if it is meaningful
    $media = $matches[3][0] !== '' && $matches[3][0] !== 'all' ? ' media="' . $matches[3][0] . '"' : '';

    return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
} );

/**
 * Clean up output of <script> tags
 *
 * - Remove redundant `type='text/javascript'`
 */
add_filter( 'script_loader_tag', function ( $input ) {
    if ( is_admin() ) {
        return $input;
    }
    $input = str_replace( "type='text/javascript' ", '', $input );

    return str_replace( "'", '"', $input );
} );

/**
 * Wrap embedded media as suggested by Readability
 *
 * @link https://gist.github.com/965956
 * @link http://www.readability.com/publishers/guidelines#publisher
 */
add_filter( 'embed_oembed_html', function ( $cache ) {
    return '<div class="embed-responsive">' . $cache . '</div>';
} );

/**
 * Remove unnecessary self-closing tags
 */
array_map( function ( $input ) {
    return str_replace( ' />', '>', $input );
}, [
    'get_avatar',
    'comment_id_fields',
    'post_thumbnail_html',
] );

/**
 * Moves all scripts to wp_footer action
 */
add_action( 'wp_enqueue_scripts', function () {
    remove_action( 'wp_head', 'wp_print_scripts' );
    remove_action( 'wp_head', 'wp_print_head_scripts', 9 );
    remove_action( 'wp_head', 'wp_enqueue_scripts', 1 );
} );

/**
 * Disables trackbacks/pingbacks
 *
 * You can enable/disable this feature in functions.php (or app/setup.php if you're using Sage):
 * add_theme_support('soil-disable-trackbacks');
 */

/**
 * Disable pingback XMLRPC method
 */
add_filter( 'xmlrpc_methods', function ( $methods ) {
    unset( $methods['pingback.ping'] );

    return $methods;
} );

/**
 * Remove pingback header
 */
add_filter( 'wp_headers', function ( $headers ) {
    if ( isset( $headers['X-Pingback'] ) ) {
        unset( $headers['X-Pingback'] );
    }

    return $headers;
} );

/**
 * Kill trackback rewrite rule
 */
add_filter( 'rewrite_rules_array', function ( $rules ) {
    foreach ( $rules as $rule => $rewrite ) {
        if ( preg_match( '/trackback\/\?\$$/i', $rule ) ) {
            unset( $rules[ $rule ] );
        }
    }

    return $rules;
} );

/**
 * Kill bloginfo('pingback_url')
 */
add_filter( 'bloginfo_url', function ( $output, $show ) {
    if ( $show === 'pingback_url' ) {
        $output = '';
    }

    return $output;
}, 10, 2 );

/**
 * Disable XMLRPC call
 */
add_action( 'xmlrpc_call', function ( $action ) {
    if ( $action === 'pingback.ping' ) {
        wp_die( __( 'Pingbacks are not supported', '' ), 403 );
    }
} );
