<?php
/**
 * Filename filters-core.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

namespace Toi\ToiBox\Filters_Core;

use Toi\ToiBox\Templates;

add_filter( 'body_class', function ( $classes ) {
    /**
     * Remove extraneous classes
     */
    $classes = array_diff( $classes, [
        'paged',
    ] );

    $classes = array_filter( $classes, function ( $class ) {
        $keep = true;
        if ( 1 === preg_match( '/^.*template-default.*$/', $class ) ) {
            $keep = false;
        } else if ( 1 === preg_match( '/^single-.*$/', $class ) ) {
            $keep = false;
        } else if ( 1 === preg_match( '/^.*paged-\d*$/', $class ) ) {
            $keep = false;
        } else if ( 1 === preg_match( '/^p(ost|age|arent-page)id-\d*$/', $class ) ) {
            $keep = false;
        }

        return $keep;
    } );

    // Add post/page slug if not present
    if ( is_single() || is_page() && ! is_front_page() ) {
        $slug = basename( get_permalink() );
        if ( ! in_array( $slug, $classes ) && '?' !== substr( $slug, 0, 1 ) ) {
            $classes[] = $slug;
        }
    }

    // Remove unnecessary classes
    $home_id_class = 'page-id-' . get_option( 'page_on_front' );
    $classes       = array_diff( $classes, [
        'page-template-default',
        $home_id_class
    ] );

    $classes[] = Templates\has_sidebar() ? 'sidebar' : 'no-sidebar';

    return $classes;
} );

add_filter( 'search_form_args', function ( $args ) {
    $args['aria_label'] = __( 'Sitewide', '' );

    return $args;
} );

add_filter( 'excerpt_length', function () {
    return 38;
} );

add_filter( 'excerpt_more', function () {
    return '&hellip;';
} );
