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
            $keep = true;
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

add_action( 'wp_update_nav_menu', function ( $menu_id ) {
    foreach ( get_nav_menu_locations() as $location => $assigned_menu_id ) {
        if ( $assigned_menu_id === $menu_id ) {
            delete_transient( "toibox_cached_menu_$location" );
        }
    }
}, 10, 1 );

add_filter( 'hybrid/breadcrumbs/trail', function ( $html, $crumbs, $breadcrumb ) {
    $dom = new \DOMDocument();
    libxml_use_internal_errors( true );
    $dom->loadHTML( $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
    libxml_clear_errors();

    foreach ( $dom->getElementsByTagName( '*' ) as $element ) {
        /* @var \DomElement $element */
        $element->removeAttribute( 'itemprop' );
        $element->removeAttribute( 'itemscope' );
        $element->removeAttribute( 'itemtype' );
    }

    $list_items = $dom->getElementsByTagName( 'li' );

    /* @var \DomElement $last_item */
    $last_item = $list_items->item( $list_items->length - 1 );
    $classes   = $last_item->getAttribute( 'class' );
    $last_item->setAttribute( 'class', $classes . ' active' );
    $last_item->setAttribute( 'aria-current', 'page' );

    $modified = $dom->saveHTML( $dom );

    return $modified;
}, 10, 3 );

/**
 * Add Post ID to order_by to fix issue where posts with same datetime are displayed unpredictably across pages
 */
add_filter( 'pre_get_posts', function ( $query ) {
    /** @var \WP_Query $query */
    if ( $query->is_admin() ) {
        return $query;
    }

    if ( ! $query->is_main_query() ) {
        return $query;
    }

    if ( ! $query->is_home() ) {
        return $query;
    }

    $query->set( 'orderby', [ 'post_date' => 'DESC', 'ID' => 'ASC' ] );

    return $query;

}, 10, 1 );

/**
 * Disable large image scaling
 */
 add_filter( 'big_image_size_threshold', '__return_false' );



/**
 * Filter to exclude sticky posts from search results.
 *
 * @param \WP_Query $query WordPress query object.
 */
add_action( 'pre_get_posts', function ( $query ) {
    $s = $query->get('s');
    if ( '' !== $s && $query->is_main_query() ) {
        $query->set( 'ignore_sticky_posts', true );
    }
}, 10, 1);

/**
 * Filter to exclude sticky posts from year filtered results.
 *
 * @param \WP_Query $query WordPress query object.
 */
add_action( 'pre_get_posts', function ( $query ) {
    $year = $query->get('year');
    if ( '' !== $year && $query->is_main_query() ) {
        $query->set( 'ignore_sticky_posts', true );
    }
}, 10, 1);
