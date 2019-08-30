<?php
/**
 * Filename filters-theme-mod.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

namespace Toi\ToiBox\Filters_Theme_Mod;

add_action( 'wp_head', function () {
    $scripts = get_theme_mod( '_toibox_header_scripts' );
    if ( ! empty( $scripts ) ) {
        echo $scripts;
    }
} );

add_action( 'wp_body_open', function () {
    $scripts = get_theme_mod( '_toibox_body_open_scripts' );
    if ( ! empty( $scripts ) ) {
        echo $scripts;
    }
} );

add_action( 'wp_footer', function () {
    $scripts = get_theme_mod( '_toibox_footer_scripts' );
    if ( ! empty( $scripts ) ) {
        echo $scripts;
    }
}, 100 );
