<?php
/**
 * Filename filters-projects.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

/**
 * Project Archive posts per page
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

/**
 * Set Award Year term link to point to Honouree archive when in Honouree context
 */
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

/**
 * Use 'year' query string to filter by 'award-year' instead of default 'post_date'
 */
add_filter( 'request', function ( $query_vars ) {

    if ( empty( $query_vars['post_type'] ) || 'project' !== $query_vars['post_type'] ) {
        return $query_vars;
    }

    if ( empty( $query_vars['year'] ) ) {
        return $query_vars;
    }

    $query_vars['award-year'] = $query_vars['year'];
    unset( $query_vars['year'] );

    return $query_vars;

}, 10, 1 );

add_filter( 'template_include', function ( $template ) {
    return $template;
}, 10, 1 );
