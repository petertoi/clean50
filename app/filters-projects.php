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
