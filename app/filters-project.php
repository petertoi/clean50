<?php
/**
 * Filename filters-projects.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

namespace Toi\ToiBox\Filters_Projects;

use function Toi\ToiBox\Snippets\get_award_year;

/**
 * Set _award-year meta to term->slug on save to aid with ordering clauses
 */
add_action( 'save_post_project', function ( $post_ID, $post, $update ) {
    $award_year = get_award_year( $post_ID );
    if ( false === $award_year ) {
        delete_post_meta( $post_ID, '_award-year' );
    } else {
        update_post_meta( $post_ID, '_award-year', $award_year->slug );
    }
}, 10, 3 );


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
 * Set orderby
 */
add_filter( 'pre_get_posts', function ( $query ) {
    /** @var \WP_Query $query */
    if ( is_admin() ) {
        return $query;
    }

    if ( 'project' !== $query->get( 'post_type' ) ) {
        return $query;
    }

    $meta_query = $query->get( 'meta_query ' );

    if ( empty( $meta_query ) ) {
        $meta_query = [];
    }

    $meta_query['relation'] = 'AND';

    $meta_query['award_year_order'] = [
        'key'     => '_award-year',
        'compare' => 'EXISTS',
    ];

    $query->set( 'meta_query', $meta_query );

    $query->set( 'orderby', [
        'award_year_order' => 'DESC',
        'title'            => 'ASC',
    ] );

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
