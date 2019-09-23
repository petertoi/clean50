<?php
/**
 * Filename filters-projects.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

add_filter( 'pre_get_posts', function ( $query ) {
    /** @var \WP_Query $query */
    if ( is_admin() ) {
        return $query;
    }

    if ( ! is_post_type_archive( 'project' ) ) {
        return $query;
    }

    $per_page = get_theme_mod( '_toibox_project_archive_per_page' ) ?: 4;

    $query->set( 'posts_per_page', 40 );

    return $query;
} );

add_filter( 'term_link', function ( $termlink, $term, $taxonomy ) {
    global $wp_query;

    if ( 'award-year' !== $taxonomy ) {
        return $termlink;
    }

    if ( 'project' !== $wp_query->get( 'post_type' ) ) {
        return $termlink;
    }

    $link = get_post_type_archive_link( 'project' );
    $link = add_query_arg( 'year', $term->slug, $link );

    return $link;

}, 10, 3 );

add_filter( 'pre_get_posts', function ( $query ) {

    /** @var $query \WP_Query */
    if ( $query->is_admin ) {
        return $query;
    }

    if ( ! $query->is_post_type_archive( 'project' ) ) {
        return $query;
    }
    $year = $query->get( 'year' );
    $query->set( 'year', '' );

    $query->set( 'award-year', $year );

    return $query;

}, 10, 1 );
